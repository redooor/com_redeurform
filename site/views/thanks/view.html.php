<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

class RedeuformViewThanks extends HtmlView
{
  public function display($tpl = null)
  {
    // Set the page title
    Factory::getDocument()->setTitle(Text::_('COM_REDEUFORM_THANK_YOU'));
    parent::display($tpl);
  }
}
