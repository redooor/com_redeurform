<?php
defined('_JEXEC') or die;

abstract class RedeurformHelper
{
    /**
     * Add sidebar navigation entries.
     * Works on both Joomla 3 (JHtmlSidebar) and Joomla 4 (no sidebar — handled by template).
     */
    public static function addSubmenu($vName)
    {
        // JHtmlSidebar exists in Joomla 3; Joomla 4 uses a different menu system
        if (class_exists('JHtmlSidebar')) {
            JHtmlSidebar::addEntry(
                JText::_('COM_REDEURFORM_SUBMISSIONS'),
                'index.php?option=com_redeurform&view=submissions',
                $vName === 'submissions'
            );
            JHtmlSidebar::addEntry(
                JText::_('COM_REDEURFORM_SETTINGS'),
                'index.php?option=com_redeurform&view=redeurform',
                $vName === 'redeurform'
            );
        }
    }

    /**
     * Returns true when running on Joomla 4+.
     */
    public static function isJoomla4()
    {
        return version_compare(JVERSION, '4.0', '>=');
    }
}
