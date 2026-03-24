<?php
defined('_JEXEC') or die;

JLoader::registerPrefix('Redeuform', JPATH_ADMINISTRATOR . '/components/com_redeuform');

$controller = JControllerLegacy::getInstance('Redeuform');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
