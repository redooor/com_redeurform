<?php
defined('_JEXEC') or die;

class RedeuformModelForm extends JModelLegacy
{
    public function saveSubmission(array $data)
    {
        $db  = $this->getDbo();
        $row = (object) array(
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => isset($data['phone']) ? $data['phone'] : '',
            'message'    => $data['message'],
            'ip_address' => isset($data['ip_address']) ? $data['ip_address'] : '',
            'created_at' => JFactory::getDate()->toSql(),
            'state'      => 1,
        );
        return $db->insertObject('#__redeuform_submissions', $row);
    }

    public function sendEmail(array $data)
    {
        $params      = JComponentHelper::getParams('com_redeuform');
        $toEmail     = $params->get('receiving_email', '');
        $fromEmail   = $params->get('sendfrom_email', JFactory::getConfig()->get('mailfrom'));
        $template    = $params->get('email_template', JText::_('COM_REDEUFORM_DEFAULT_EMAIL_TEMPLATE'));

        if (empty($toEmail)) {
            return false;
        }

        // Replace template placeholders
        $body = strtr($template, array(
            '{name}'    => htmlspecialchars($data['name']),
            '{email}'   => htmlspecialchars($data['email']),
            '{phone}'   => htmlspecialchars(isset($data['phone']) ? $data['phone'] : ''),
            '{message}' => htmlspecialchars($data['message']),
        ));

        $mailer = JFactory::getMailer();
        $mailer->setSender(array($fromEmail, $data['name']));
        $mailer->addRecipient($toEmail);
        $mailer->setSubject(JText::_('COM_REDEUFORM_EMAIL_SUBJECT'));
        $mailer->setBody($body);
        $mailer->isHTML(false);

        return $mailer->Send();
    }
}
