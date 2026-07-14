<?php
/**
 * @package     com_redeurform
 * @copyright   Copyright (C) 2026 Redooor LLP. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class RedeurformControllerForm extends JControllerLegacy
{
    public function submit()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app     = JFactory::getApplication();
        $session = JFactory::getSession();

        // ── Rate limiting ─────────────────────────────────────────────────────
        $lastSubmit = (int) $session->get('redeurform.last_submit', 0);
        if ((time() - $lastSubmit) < 60) {
            $app->enqueueMessage(JText::_('COM_REDEURFORM_ERROR_TOO_FAST'), 'warning');
            $app->redirect(JRoute::_('index.php?option=com_redeurform&view=redeurform', false));
            return;
        }

        $input = $app->input;
        $model = $this->getModel('Form', 'RedeurformModel');

        $data = array(
            'name'    => $input->getString('name', ''),
            'email'   => $input->getString('email', ''),
            'phone'   => $input->getString('phone', ''),
            'message' => $input->getString('message', ''),
        );

        // ── Backend field validation ───────────────────────────────────────────
        $errors = array();

        if (empty(trim($data['name']))) {
            $errors[] = JText::_('COM_REDEURFORM_ERROR_NAME_REQUIRED');
        } elseif (mb_strlen($data['name']) > 100) {
            $errors[] = JText::_('COM_REDEURFORM_ERROR_NAME_TOO_LONG');
        }
        if (empty(trim($data['email'])) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = JText::_('COM_REDEURFORM_ERROR_EMAIL_INVALID');
        }
        if (!empty($data['phone']) && mb_strlen($data['phone']) > 30) {
            $errors[] = JText::_('COM_REDEURFORM_ERROR_PHONE_TOO_LONG');
        }
        if (empty(trim($data['message']))) {
            $errors[] = JText::_('COM_REDEURFORM_ERROR_MESSAGE_REQUIRED');
        } elseif (mb_strlen(trim($data['message'])) > 255) {
            $errors[] = JText::_('COM_REDEURFORM_ERROR_MESSAGE_TOO_LONG');
        }

        // ── Cloudflare Turnstile verification ─────────────────────────────────
        $params    = JComponentHelper::getParams('com_redeurform');
        $siteKey   = trim($params->get('turnstile_site_key', ''));
        $secretKey = trim($params->get('turnstile_secret_key', ''));

        if (!empty($siteKey) && !empty($secretKey)) {
            $token = $input->getString('cf-turnstile-response', '');
            if (empty($token)) {
                $errors[] = JText::_('COM_REDEURFORM_ERROR_TURNSTILE_REQUIRED');
            } else {
                $verifyResult = $this->verifyTurnstile($secretKey, $token);
                if ($verifyResult !== true) {
                    $errors[] = $verifyResult;
                }
            }
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                $app->enqueueMessage($error, 'error');
            }
            $app->redirect(JRoute::_('index.php?option=com_redeurform&view=redeurform', false));
            return;
        }

        $session->set('redeurform.last_submit', time());

        $data['ip_address'] = $app->input->server->getString('REMOTE_ADDR', '');
        if ($model->saveSubmission($data)) {
            $model->sendEmail($data);
            $app->enqueueMessage(JText::_('COM_REDEURFORM_SUCCESS_MESSAGE'), 'message');
        } else {
            $app->enqueueMessage(JText::_('COM_REDEURFORM_ERROR_SAVE_FAILED'), 'error');
        }

        $app->redirect(JRoute::_('index.php?option=com_redeurform&view=redeurform', false));
    }

    private function verifyTurnstile($secretKey, $token)
    {
        $postData = http_build_query(array(
            'secret'   => $secretKey,
            'response' => $token,
            'remoteip' => JFactory::getApplication()->input->server->getString('REMOTE_ADDR', ''),
        ));

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL            => 'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $postData,
            CURLOPT_HTTPHEADER     => array('Content-Type: application/x-www-form-urlencoded'),
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT      => 'com_redeurform/1.1.0',
        ));

        $response  = curl_exec($ch);
        $curlErrno = curl_errno($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlErrno !== 0 || $response === false) {
            JFactory::getApplication()->enqueueMessage(
                JText::sprintf('COM_REDEURFORM_WARNING_TURNSTILE_CURL', $curlErrno, $curlError),
                'warning'
            );
            return true; // fail open on network error
        }

        $result = json_decode($response, true);

        if (JDEBUG) {
            JFactory::getApplication()->enqueueMessage(
                'Turnstile result: ' . print_r($result, true), 'notice'
            );
        }

        if (empty($result) || !is_array($result)) {
            return JText::_('COM_REDEURFORM_ERROR_TURNSTILE_FAILED');
        }

        if (empty($result['success'])) {
            $codes = isset($result['error-codes'])
                ? implode(', ', (array) $result['error-codes'])
                : 'unknown';
            return JText::sprintf('COM_REDEURFORM_ERROR_TURNSTILE_FAILED_CODE', $codes);
        }

        return true;
    }
}
