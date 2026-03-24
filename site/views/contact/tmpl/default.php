<?php
defined('_JEXEC') or die;

use Joomla\CMS\Captcha\Captcha;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
?>

<div class="redeuform-container">
    <h2><?php echo Text::_('COM_REDEUFORM_CONTACT_US'); ?></h2>

    <form action="index.php?option=com_redeuform&controller=contact" method="post" class="form-validate">

        <div class="control-group">
            <label><?php echo Text::_('COM_REDEUFORM_NAME'); ?>:</label>
            <input type="text" name="name" placeholder="<?php echo Text::_('COM_REDEUFORM_NAME_PLACEHOLDER'); ?>" required />
        </div>

        <div class="control-group">
            <label><?php echo Text::_('COM_REDEUFORM_EMAIL'); ?>:</label>
            <input type="email" name="email" maxlength="40" placeholder="<?php echo Text::_('COM_REDEUFORM_EMAIL_PLACEHOLDER'); ?>" required />
        </div>

        <div class="control-group">
            <label><?php echo Text::_('COM_REDEUFORM_PHONE'); ?>:</label>
            <input type="text"
                name="phone"
                maxlength="40"
                pattern="^[\d\s\-+\(\)]+$"
                title="<?php echo Text::_('COM_REDEUFORM_PHONE_VALIDATION_MSG'); ?>"
                placeholder="<?php echo Text::_('COM_REDEUFORM_PHONE_PLACEHOLDER'); ?>" />
        </div>

        <div class="control-group">
            <label><?php echo Text::_('COM_REDEUFORM_MESSAGE'); ?>:</label>
            <textarea name="message" maxlength="50" rows="5"></textarea>
            <small><?php echo Text::_('COM_REDEUFORM_LIMIT_NOTE'); ?></small>
        </div>

        <?php
        // Fetch the default captcha plugin from global configuration
        $captchaPlugin = Factory::getConfig()->get('captcha');

        if ($captchaPlugin != '0') :
            // Initialize the captcha instance
            $captcha = Captcha::getInstance($captchaPlugin);
            // Display the captcha widget
            echo '<div class="control-group">' . $captcha->display('captcha', 'captcha', 'g-recaptcha') . '</div>';
        endif;
        ?>

        <input type="hidden" name="task" value="contact.submit" />
        <?php echo HTMLHelper::_('form.token'); ?>

        <button type="submit" class="btn btn-primary">
            <?php echo Text::_('COM_REDEUFORM_SEND'); ?>
        </button>
    </form>
</div>