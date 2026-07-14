<?php
define('_JEXEC', 1);

// Joomla framework stubs — lightweight stand-ins so component files can be loaded
// without a real Joomla installation.
require_once __DIR__ . '/Stubs/JTable.php';
require_once __DIR__ . '/Stubs/JText.php';
require_once __DIR__ . '/Stubs/JModelLegacy.php';
require_once __DIR__ . '/Stubs/JControllerLegacy.php';
require_once __DIR__ . '/Stubs/JViewLegacy.php';

// Component classes under test
require_once __DIR__ . '/../admin/tables/submission.php';
require_once __DIR__ . '/../site/views/redeurform/view.html.php';
