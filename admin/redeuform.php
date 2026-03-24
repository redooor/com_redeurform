<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

// Set the default view to 'messages'
$input = Factory::getApplication()->input;
$input->set('view', $input->get('view', 'messages'));

$controller = BaseController::getInstance('Redeuform');
$controller->execute($input->get('task'));
$controller->redirect();
