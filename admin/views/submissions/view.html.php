<?php
defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_redeuform/helpers/redeuform.php';

class RedeuformViewSubmissions extends JViewLegacy
{
    protected $items;
    protected $pagination;
    protected $state;
    protected $listOrder;
    protected $listDirn;

    public function display($tpl = null)
    {
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state      = $this->get('State');
        $this->listOrder  = $this->state->get('list.ordering', 'created_at');
        $this->listDirn   = $this->state->get('list.direction', 'desc');

        RedeuformHelper::addSubmenu('submissions');

        $this->addToolbar();

        // Joomla 3 sidebar
        if (!RedeuformHelper::isJoomla4() && class_exists('JHtmlSidebar')) {
            $this->sidebar = JHtmlSidebar::render();
        }

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolbarHelper::title(JText::_('COM_REDEUFORM_SUBMISSIONS'), 'envelope');
        JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'submissions.delete');
        JToolbarHelper::preferences('com_redeuform');
    }
}
