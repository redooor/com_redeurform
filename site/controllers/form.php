<?php
defined('_JEXEC') or die;

class RedeuformControllerForm extends JControllerLegacy
{
    public function submit()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app   = JFactory::getApplication();
        $input = $app->input;
        $model = $this->getModel('Form', 'RedeuformModel');

        $data = array(
            'name'    => $input->getString('name', ''),
            'email'   => $input->getString('email', ''),
            'phone'   => $input->getString('phone', ''),
            'message' => $input->getString('message', ''),
        );

        // ── Backend field validation ───────────────────────────────────────────
        $errors = array();

        if (empty(trim($data['name']))) {
            $errors[] = JText::_('COM_REDEUFORM_ERROR_NAME_REQUIRED');
        }
        if (empty(trim($data['email'])) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = JText::_('COM_REDEUFORM_ERROR_EMAIL_INVALID');
        }
        if (empty(trim($data['message']))) {
            $errors[] = JText::_('COM_REDEUFORM_ERROR_MESSAGE_REQUIRED');
        } elseif (mb_strlen(trim($data['message'])) > 255) {
            $errors[] = JText::_('COM_REDEUFORM_ERROR_MESSAGE_TOO_LONG');
        }

        // ── reCAPTCHA v3 verification ─────────────────────────────────────────
        $params     = JComponentHelper::getParams('com_redeuform');
        $siteKey    = trim($params->get('recaptcha_site_key', ''));
        $secretKey  = trim($params->get('recaptcha_secret_key', ''));

        // reCAPTCHA is active only when BOTH keys are provided
        $recaptchaEnabled = !empty($siteKey) && !empty($secretKey);

        if ($recaptchaEnabled) {
            $recaptchaToken = $input->getString('g-recaptcha-response', '');
            $threshold      = (float) $params->get('recaptcha_threshold', '0.5');

            if (empty($recaptchaToken)) {
                $errors[] = JText::_('COM_REDEUFORM_ERROR_RECAPTCHA_REQUIRED');
            } else {
                $verifyResult = $this->verifyRecaptchaV3($secretKey, $recaptchaToken, $threshold);

                if ($verifyResult === 'curl_failed') {
                    // Could not reach Google — fail open, log a warning
                    $app->enqueueMessage(JText::_('COM_REDEUFORM_WARNING_RECAPTCHA_SKIPPED'), 'warning');
                } elseif ($verifyResult !== true) {
                    // Explicit failure (low score, wrong action, bad token)
                    $errors[] = $verifyResult;
                }
            }
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                $app->enqueueMessage($error, 'error');
            }
            $app->redirect(JRoute::_('index.php?option=com_redeuform&view=redeuform', false));
            return;
        }

        // ── Save and send email ───────────────────────────────────────────────
        $data['ip_address'] = $app->input->server->getString('REMOTE_ADDR', '');
        if ($model->saveSubmission($data)) {
            $model->sendEmail($data);
            $app->enqueueMessage(JText::_('COM_REDEUFORM_SUCCESS_MESSAGE'), 'message');
        } else {
            $app->enqueueMessage(JText::_('COM_REDEUFORM_ERROR_SAVE_FAILED'), 'error');
        }

        $app->redirect(JRoute::_('index.php?option=com_redeuform&view=redeuform', false));
    }

    /**
     * Calls Google siteverify for reCAPTCHA v3.
     *
     * Returns:
     *   true          — token accepted
     *   'curl_failed' — could not reach Google (network error)
     *   string        — human-readable error message (token rejected)
     */
    private function verifyRecaptchaV3($secretKey, $token, $threshold)
    {
        $postData = http_build_query(array(
            'secret'   => $secretKey,
            'response' => $token,
            'remoteip' => JFactory::getApplication()->input->server->getString('REMOTE_ADDR', ''),
        ));

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL            => 'https://www.google.com/recaptcha/api/siteverify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $postData,
            // Send data as application/x-www-form-urlencoded, not multipart
            CURLOPT_HTTPHEADER     => array('Content-Type: application/x-www-form-urlencoded'),
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            // Accept Google's certificate on systems with outdated CA bundles
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (compatible; com_redeuform/1.0.5)',
        ));

        $response  = curl_exec($ch);
        $curlErrno = curl_errno($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlErrno !== 0 || $response === false) {
            // Network/TLS error — caller decides whether to fail open or closed
            JFactory::getApplication()->enqueueMessage(
                'reCAPTCHA cURL error ' . $curlErrno . ': ' . $curlError, 'warning'
            );
            return 'curl_failed';
        }

        $result = json_decode($response, true);

        if (empty($result) || !is_array($result)) {
            return JText::_('COM_REDEUFORM_ERROR_RECAPTCHA_FAILED');
        }

        // Log full result in debug mode to help diagnose environment issues
        if (JDEBUG) {
            JFactory::getApplication()->enqueueMessage(
                'reCAPTCHA result: ' . print_r($result, true), 'notice'
            );
        }

        if (empty($result['success'])) {
            $errorCodes = isset($result['error-codes']) ? implode(', ', $result['error-codes']) : 'unknown';
            // timeout-or-duplicate and invalid-input-response are token issues
            // browser-error usually means the token reached the server mangled
            return JText::sprintf('COM_REDEUFORM_ERROR_RECAPTCHA_FAILED_CODE', $errorCodes);
        }

        // Score check (threshold = 0 disables score filtering entirely)
        $score = isset($result['score']) ? (float) $result['score'] : 0.0;
        if ($threshold > 0 && $score < $threshold) {
            return JText::_('COM_REDEUFORM_ERROR_RECAPTCHA_FAILED');
        }

        // Action check — only enforce if Google returned one
        // (some server configs strip or do not return the action field)
        $returnedAction = isset($result['action']) ? $result['action'] : '';
        if (!empty($returnedAction) && $returnedAction !== 'contact_form') {
            return JText::_('COM_REDEUFORM_ERROR_RECAPTCHA_FAILED');
        }

        return true;
    }
}
