<?php
defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_redeurform/helpers/redeurform.php';

class RedeurformViewRedeurform extends JViewLegacy
{
    protected $form;

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');

        RedeurformHelper::addSubmenu('redeurform');

        $this->addToolbar();

        if (!RedeurformHelper::isJoomla4() && class_exists('JHtmlSidebar')) {
            $this->sidebar = JHtmlSidebar::render();
        }

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolbarHelper::title(JText::_('COM_REDEURFORM_SETTINGS'), 'cog');
        JToolbarHelper::apply('redeurform.apply');
        JToolbarHelper::save('redeurform.save');
    }
}
