<?php
defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_redeuform/helpers/redeuform.php';

class RedeuformViewRedeuform extends JViewLegacy
{
    protected $form;

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');

        RedeuformHelper::addSubmenu('redeuform');

        $this->addToolbar();
        $this->sidebar = JHtmlSidebar::render();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolbarHelper::title(JText::_('COM_REDEUFORM_SETTINGS'), 'cog');
        JToolbarHelper::apply('redeuform.apply');
        JToolbarHelper::save('redeuform.save');
    }
}
