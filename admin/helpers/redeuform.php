<?php
defined('_JEXEC') or die;

abstract class RedeuformHelper
{
    public static function addSubmenu($vName)
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_REDEUFORM_SUBMISSIONS'),
            'index.php?option=com_redeuform&view=submissions',
            $vName == 'submissions'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_REDEUFORM_SETTINGS'),
            'index.php?option=com_redeuform&view=redeuform',
            $vName == 'redeuform'
        );
    }
}
