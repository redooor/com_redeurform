<?php
/**
 * @package     com_redeurform
 * @copyright   Copyright (C) 2026 Redooor LLP. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class RedeurformController extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = array())
    {
        $view = JFactory::getApplication()->input->get('view', 'submissions');
        JFactory::getApplication()->input->set('view', $view);
        parent::display($cachable, $urlparams);
        return $this;
    }
}
