<?php
defined('_JEXEC') or die;

abstract class RedeuformHelper
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
                JText::_('COM_REDEUFORM_SUBMISSIONS'),
                'index.php?option=com_redeuform&view=submissions',
                $vName === 'submissions'
            );
            JHtmlSidebar::addEntry(
                JText::_('COM_REDEUFORM_SETTINGS'),
                'index.php?option=com_redeuform&view=redeuform',
                $vName === 'redeuform'
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
