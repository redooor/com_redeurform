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

        // Backend field validation
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

        // reCAPTCHA v3 server-side verification
        $params    = JComponentHelper::getParams('com_redeuform');
        $secretKey = $params->get('recaptcha_secret_key', '');
        $threshold = (float) $params->get('recaptcha_threshold', '0.5');

        if (!empty($secretKey)) {
            $recaptchaToken = $input->getString('g-recaptcha-response', '');

            if (empty($recaptchaToken)) {
                $errors[] = JText::_('COM_REDEUFORM_ERROR_RECAPTCHA_REQUIRED');
            } else {
                $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, array(
                    'secret'   => $secretKey,
                    'response' => $recaptchaToken,
                    'remoteip' => $app->input->server->getString('REMOTE_ADDR', ''),
                ));
                $result       = curl_exec($ch);
                $curlError    = curl_errno($ch);
                curl_close($ch);

                if ($curlError || empty($result)) {
                    // cURL failed — fail open to avoid blocking legitimate users
                    JFactory::getApplication()->enqueueMessage(
                        JText::_('COM_REDEUFORM_WARNING_RECAPTCHA_SKIPPED'), 'warning'
                    );
                } else {
                    $captchaResult = json_decode($result, true);
                    $success       = !empty($captchaResult['success']);
                    $score         = isset($captchaResult['score']) ? (float) $captchaResult['score'] : 0.0;
                    $action        = isset($captchaResult['action']) ? $captchaResult['action'] : '';

                    if (!$success || $score < $threshold || $action !== 'contact_form') {
                        $errors[] = JText::_('COM_REDEUFORM_ERROR_RECAPTCHA_FAILED');
                    }
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

        // Save and email
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
