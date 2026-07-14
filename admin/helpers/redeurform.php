<?php
/**
 * @package     com_redeurform
 * @copyright   Copyright (C) 2026 Redooor LLP. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

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
            JHtmlSidebar::addEntry(
                JText::_('COM_REDEURFORM_HELP'),
                'index.php?option=com_redeurform&view=help',
                $vName === 'help'
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
