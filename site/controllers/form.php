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

        // Backend validation
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

        // Google reCAPTCHA v2 verification
        $recaptchaResponse = $input->getString('g-recaptcha-response', '');
        $params            = JComponentHelper::getParams('com_redeuform');
        $secretKey         = $params->get('recaptcha_secret_key', '');

        if (!empty($secretKey)) {
            if (empty($recaptchaResponse)) {
                $errors[] = JText::_('COM_REDEUFORM_ERROR_RECAPTCHA_REQUIRED');
            } else {
                $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
                $ch = curl_init($verifyUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, array(
                    'secret'   => $secretKey,
                    'response' => $recaptchaResponse,
                    'remoteip' => $app->input->server->getString('REMOTE_ADDR', ''),
                ));
                $result   = curl_exec($ch);
                curl_close($ch);
                $captchaResult = json_decode($result, true);
                if (empty($captchaResult['success'])) {
                    $errors[] = JText::_('COM_REDEUFORM_ERROR_RECAPTCHA_FAILED');
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

        // Save submission
        $data['ip_address'] = $app->input->server->getString('REMOTE_ADDR', '');
        if ($model->saveSubmission($data)) {
            $model->sendEmail($data);
            $app->enqueueMessage(JText::_('COM_REDEUFORM_SUCCESS_MESSAGE'), 'message');
        } else {
            $app->enqueueMessage(JText::_('COM_REDEUFORM_ERROR_SAVE_FAILED'), 'error');
        }

        $app->redirect(JRoute::_('index.php?option=com_redeuform&view=redeuform', false));
    }
}
