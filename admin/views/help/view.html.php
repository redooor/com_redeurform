<?php
/**
 * @package     com_redeurform
 * @copyright   Copyright (C) 2026 Redooor LLP. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_redeurform/helpers/redeurform.php';

class RedeurformViewHelp extends JViewLegacy
{
    public function display($tpl = null)
    {
        RedeurformHelper::addSubmenu('help');

        $this->addToolbar();

        if (!RedeurformHelper::isJoomla4() && class_exists('JHtmlSidebar')) {
            $this->sidebar = JHtmlSidebar::render();
        }

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolbarHelper::title(JText::_('COM_REDEURFORM_HELP'), 'info');
    }
}
