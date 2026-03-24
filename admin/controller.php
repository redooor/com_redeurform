<?php
defined('_JEXEC') or die;

class RedeuformController extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = array())
    {
        $view = JFactory::getApplication()->input->get('view', 'submissions');
        JFactory::getApplication()->input->set('view', $view);
        parent::display($cachable, $urlparams);
        return $this;
    }
}
