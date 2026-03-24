<?php
defined('_JEXEC') or die;

class RedeuformControllerSubmissions extends JControllerAdmin
{
    public function getModel($name = 'Submission', $prefix = 'RedeuformModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }
}
