<?php
/**
 * @package     com_redeurform
 * @copyright   Copyright (C) 2026 Redooor LLP. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/helpers/redeurform.php';

$controller = JControllerLegacy::getInstance('Redeurform');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
