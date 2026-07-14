<?php
defined('_JEXEC') or die;

class RedeurformControllerSubmissions extends JControllerAdmin
{
    public function getModel($name = 'Submission', $prefix = 'RedeurformModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }
}
