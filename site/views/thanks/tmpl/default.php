<?php
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

?>
<div class="redeuform-thanks">
  <h1><?php echo Text::_('COM_REDEUFORM_THANK_YOU_TITLE'); ?></h1>
  <p><?php echo Text::_('COM_REDEUFORM_THANK_YOU_MESSAGE'); ?></p>
  <a href="<?php echo Route::_('index.php'); ?>" class="btn">
    <?php echo Text::_('COM_REDEUFORM_BACK_HOME'); ?>
  </a>
</div>