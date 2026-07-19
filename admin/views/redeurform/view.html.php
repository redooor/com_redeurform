<?php
/**
 * @package     com_redeurform
 * @copyright   Copyright (C) 2026 Redooor LLP. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_redeurform/helpers/redeurform.php';

class RedeurformViewRedeurform extends JViewLegacy
{
    protected $form;

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');

        // Joomla 4's toolbar calls document.formvalidator for forms carrying
        // the form-validate class. Load the validator before the toolbar's
        // Apply and Save buttons can submit this settings form.
        JHtml::_('behavior.formvalidator');

        RedeurformHelper::addSubmenu('redeurform');

        $this->addToolbar();

        if (!RedeurformHelper::isJoomla4() && class_exists('JHtmlSidebar')) {
            $this->sidebar = JHtmlSidebar::render();
        }

        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JUri::root(true) . '/media/com_redeurform/css/redeurform-admin.css');
        $doc->addStyleSheet(JUri::root(true) . '/media/com_redeurform/css/redeurform.css');

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolbarHelper::title(JText::_('COM_REDEURFORM_SETTINGS'), 'cog');
        JToolbarHelper::apply('redeurform.apply');
        JToolbarHelper::save('redeurform.save');
    }
}
