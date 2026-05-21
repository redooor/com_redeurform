<?php
defined('_JEXEC') or die;

class RedeuformControllerRedeuform extends JControllerForm
{
    protected $view_list = 'submissions';

    public function apply()
    {
        $this->save('apply');
    }

    public function save($key = null, $urlVar = null)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app   = JFactory::getApplication();
        $model = $this->getModel('Redeuform', 'RedeuformModel');
        $data  = $app->input->get('jform', array(), 'array');

        if ($model->save($data)) {
            $app->enqueueMessage(JText::_('COM_REDEUFORM_SETTINGS_SAVED'));
            if ($key === 'apply') {
                $app->redirect(JRoute::_('index.php?option=com_redeuform&view=redeuform', false));
            } else {
                $app->redirect(JRoute::_('index.php?option=com_redeuform&view=submissions', false));
            }
        } else {
            $app->enqueueMessage(JText::_('COM_REDEUFORM_SETTINGS_SAVE_FAILED'), 'error');
            $app->redirect(JRoute::_('index.php?option=com_redeuform&view=redeuform', false));
        }
    }

    public function testEmail()
    {
        JSession::checkToken('get') or JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app     = JFactory::getApplication();
        $params  = JComponentHelper::getParams('com_redeuform');
        $jConfig = JFactory::getConfig();

        $toEmail   = trim($params->get('receiving_email', ''));
        $fromEmail = trim($params->get('sendfrom_email', ''));
        if (empty($fromEmail)) {
            $fromEmail = $jConfig->get('mailfrom', '');
        }
        $fromName = $jConfig->get('fromname', $jConfig->get('sitename', 'Website'));

        if (empty($toEmail) || empty($fromEmail)) {
            $app->enqueueMessage(JText::_('COM_REDEUFORM_TEST_EMAIL_MISSING_CONFIG'), 'error');
            $app->redirect(JRoute::_('index.php?option=com_redeuform&view=redeuform', false));
            return;
        }

        $mailer = JFactory::getMailer();
        $mailer->setSender(array($fromEmail, $fromName));
        $mailer->addRecipient($toEmail);
        $mailer->addReplyTo($fromEmail, $fromName);
        $mailer->setSubject(JText::_('COM_REDEUFORM_TEST_EMAIL_SUBJECT'));
        $mailer->setBody(
            JText::sprintf('COM_REDEUFORM_TEST_EMAIL_BODY',
                $fromEmail,
                $toEmail,
                $jConfig->get('mailer', 'mail'),
                $jConfig->get('smtphost', '-'),
                $jConfig->get('smtpport', '-')
            )
        );
        $mailer->isHTML(false);

        $result = $mailer->Send();

        if ($result === true) {
            $app->enqueueMessage(JText::sprintf('COM_REDEUFORM_TEST_EMAIL_SUCCESS', $toEmail), 'message');
        } else {
            $app->enqueueMessage(JText::sprintf('COM_REDEUFORM_TEST_EMAIL_FAILED', (string) $result), 'error');
        }

        $app->redirect(JRoute::_('index.php?option=com_redeuform&view=redeuform', false));
    }
}
