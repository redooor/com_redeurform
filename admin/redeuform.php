<?php
defined('_JEXEC') or die;

require_once __DIR__ . '/helpers/redeuform.php';

$controller = JControllerLegacy::getInstance('Redeuform');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
