<?php
defined('_JEXEC') or die;

class RedeurformModelSubmission extends JModelAdmin
{
    public function getTable($type = 'Submission', $prefix = 'RedeurformTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        return false;
    }

    protected function canDelete($record)
    {
        return JFactory::getUser()->authorise('core.delete', 'com_redeurform');
    }
}
