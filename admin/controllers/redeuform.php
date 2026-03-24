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
}
