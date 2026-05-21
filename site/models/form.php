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
        $params    = JComponentHelper::getParams('com_redeuform');
        $jConfig   = JFactory::getConfig();

        $toEmail  = trim($params->get('receiving_email', ''));
        $toName   = $jConfig->get('sitename', 'Admin');

        $fromEmail = trim($params->get('sendfrom_email', ''));
        if (empty($fromEmail)) {
            $fromEmail = $jConfig->get('mailfrom', '');
        }
        $fromName = $jConfig->get('fromname', $jConfig->get('sitename', 'Website'));

        if (empty($toEmail) || empty($fromEmail)) {
            return false;
        }

        $defaultTemplate =
            'From:    {name}' . "\n" .
            'Email:   {email}' . "\n" .
            'Phone:   {phone}' . "\n\n" .
            'Message:' . "\n" .
            '{message}' . "\n\n" .
            '---' . "\n" .
            'Sent to:   {receiving_email}' . "\n" .
            'Send-as:   {sendfrom_email}';

        $template = $params->get('email_template', $defaultTemplate);

        $body = strtr($template, array(
            '{name}'            => $data['name'],
            '{email}'           => $data['email'],
            '{phone}'           => isset($data['phone']) ? $data['phone'] : '',
            '{message}'         => $data['message'],
            '{receiving_email}' => $toEmail,
            '{sendfrom_email}'  => $fromEmail,
        ));

        $mailer = JFactory::getMailer();
        $mailer->setSender(array($fromEmail, $fromName));
        $mailer->addRecipient($toEmail, $toName);
        $mailer->addReplyTo($data['email'], $data['name']);
        $mailer->setSubject(JText::_('COM_REDEUFORM_EMAIL_SUBJECT'));
        $mailer->setBody($body);
        $mailer->isHTML(false);

        return $mailer->Send();
    }
}
