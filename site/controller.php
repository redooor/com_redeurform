<?php
defined('_JEXEC') or die;

class RedeurformController extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = array())
    {
        $view = JFactory::getApplication()->input->get('view', 'redeurform');
        JFactory::getApplication()->input->set('view', $view);
        parent::display($cachable, $urlparams);
        return $this;
    }
}
