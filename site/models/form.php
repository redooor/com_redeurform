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

        // ── Addressing ────────────────────────────────────────────────────────
        // Receiving email: where the notification lands (required)
        $toEmail   = trim($params->get('receiving_email', ''));
        $toName    = $jConfig->get('sitename', 'Admin');

        // Send-as email: the From address on the outgoing email
        // Falls back to the Joomla global mail sender if left blank
        $fromEmail = trim($params->get('sendfrom_email', ''));
        if (empty($fromEmail)) {
            $fromEmail = $jConfig->get('mailfrom', '');
        }
        $fromName  = $jConfig->get('fromname', $jConfig->get('sitename', 'Website'));

        if (empty($toEmail) || empty($fromEmail)) {
            // Cannot send without both addresses configured
            return false;
        }

        // ── Body ──────────────────────────────────────────────────────────────
        $defaultTemplate =
            'From:    {name}' . "\n" .
            'Email:   {email}' . "\n" .
            'Phone:   {phone}' . "\n" .
            "\n" .
            'Message:' . "\n" .
            '{message}' . "\n" .
            "\n" .
            '---' . "\n" .
            'Sent to:   {receiving_email}' . "\n" .
            'Send-as:   {sendfrom_email}';

        $template = $params->get('email_template', $defaultTemplate);

        $body = strtr($template, array(
            '{name}'             => $data['name'],
            '{email}'            => $data['email'],
            '{phone}'            => isset($data['phone']) ? $data['phone'] : '',
            '{message}'          => $data['message'],
            '{receiving_email}'  => $toEmail,
            '{sendfrom_email}'   => $fromEmail,
        ));

        // ── Send ──────────────────────────────────────────────────────────────
        $mailer = JFactory::getMailer();

        // From: the configured send-as address (site identity)
        $mailer->setSender(array($fromEmail, $fromName));

        // To: the configured receiving address (where admin reads it)
        $mailer->addRecipient($toEmail, $toName);

        // Reply-To: the submitter — so admin can just hit Reply
        $mailer->addReplyTo($data['email'], $data['name']);

        $mailer->setSubject(JText::_('COM_REDEUFORM_EMAIL_SUBJECT'));
        $mailer->setBody($body);
        $mailer->isHTML(false);

        return $mailer->Send();
    }
}
