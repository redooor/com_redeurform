<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

// Start the component by loading the default controller
$controller = BaseController::getInstance('Redeuform');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
