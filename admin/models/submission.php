<?php
defined('_JEXEC') or die;

class RedeuformModelSubmission extends JModelAdmin
{
    public function getTable($type = 'Submission', $prefix = 'RedeuformTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        return false; // No form editing for submissions
    }

    protected function canDelete($record)
    {
        return JFactory::getUser()->authorise('core.delete', 'com_redeuform');
    }
}
