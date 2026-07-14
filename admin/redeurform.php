<?php
defined('_JEXEC') or die;

require_once __DIR__ . '/helpers/redeurform.php';

$controller = JControllerLegacy::getInstance('Redeurform');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
