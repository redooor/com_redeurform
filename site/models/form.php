<?php
/**
 * @package     com_redeurform
 * @copyright   Copyright (C) 2026 Redooor LLP. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class RedeurformModelForm extends JModelLegacy
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
        return $db->insertObject('#__redeurform_submissions', $row);
    }

    public function sendEmail(array $data)
    {
        $params    = JComponentHelper::getParams('com_redeurform');
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
        $safeName = str_replace(array("\r", "\n", "\t"), ' ', $data['name']);
        $mailer->addReplyTo($data['email'], $safeName);
        $mailer->setSubject(JText::_('COM_REDEURFORM_EMAIL_SUBJECT'));
        $mailer->setBody($body);
        $mailer->isHTML(false);

        return $mailer->Send();
    }
}
