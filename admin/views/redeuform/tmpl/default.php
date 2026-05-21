<?php defined('_JEXEC') or die;

$isJ4 = RedeuformHelper::isJoomla4();
?>

<?php if (!$isJ4 && !empty($this->sidebar)): ?>
<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else: ?>
<div id="j-main-container">
<?php endif; ?>

    <form action="<?php echo JRoute::_('index.php?option=com_redeuform&view=redeuform'); ?>"
          method="post" name="adminForm" id="adminForm" class="form-validate">

        <?php foreach ($this->form->getFieldsets() as $fieldset): ?>
        <div class="control-group">
            <h3><?php echo JText::_($fieldset->label); ?></h3>
            <?php foreach ($this->form->getFieldset($fieldset->name) as $field): ?>
            <div class="control-group">
                <div class="control-label"><?php echo $field->label; ?></div>
                <div class="controls"><?php echo $field->input; ?></div>
                <?php if ($field->description): ?>
                <div class="controls">
                    <span class="help-block"><?php echo JText::_($field->description); ?></span>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <hr />
        <?php endforeach; ?>

        <div class="control-group">
            <div class="controls">
                <span class="help-block"><?php echo JText::_('COM_REDEUFORM_EMAIL_TEMPLATE_HELP'); ?></span>
            </div>
        </div>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </form>

    <!-- ── Mail diagnostics & test-send panel ─────────────────────────────── -->
    <hr />
    <h3><?php echo JText::_('COM_REDEUFORM_MAIL_DIAG_TITLE'); ?></h3>

    <?php
        $jConfig    = JFactory::getConfig();
        $params     = JComponentHelper::getParams('com_redeuform');
        $mailerType = $jConfig->get('mailer', 'mail');
        $smtpHost   = $jConfig->get('smtphost', '—');
        $smtpPort   = $jConfig->get('smtpport', '—');
        $smtpAuth   = $jConfig->get('smtpauth', '0') ? JText::_('JYES') : JText::_('JNO');
        $mailFrom   = $jConfig->get('mailfrom', '—');
        $fromName   = $jConfig->get('fromname', '—');
        $toEmail    = trim($params->get('receiving_email', ''));
        $sendFrom   = trim($params->get('sendfrom_email', ''));
        if (empty($sendFrom)) {
            $sendFrom = $jConfig->get('mailfrom', '—') . ' ' . JText::_('COM_REDEUFORM_MAIL_DIAG_GLOBAL_FALLBACK');
        }
        $tsiteKey       = trim($params->get('turnstile_site_key', ''));
        $tsecretKey     = trim($params->get('turnstile_secret_key', ''));
        $turnstileActive = !empty($tsiteKey) && !empty($tsecretKey);
    ?>

    <table class="table table-striped table-condensed" style="max-width:640px;">
        <tbody>
            <tr>
                <th style="width:240px;"><?php echo JText::_('COM_REDEUFORM_MAIL_DIAG_JOOMLA_MAILER'); ?></th>
                <td><code><?php echo htmlspecialchars($mailerType); ?></code></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_REDEUFORM_MAIL_DIAG_SMTP_HOST'); ?></th>
                <td><code><?php echo htmlspecialchars($smtpHost); ?></code></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_REDEUFORM_MAIL_DIAG_SMTP_PORT'); ?></th>
                <td><code><?php echo htmlspecialchars($smtpPort); ?></code></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_REDEUFORM_MAIL_DIAG_SMTP_AUTH'); ?></th>
                <td><?php echo $smtpAuth; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_REDEUFORM_MAIL_DIAG_GLOBAL_FROM'); ?></th>
                <td><code><?php echo htmlspecialchars($mailFrom); ?></code>
                    &nbsp;(<?php echo htmlspecialchars($fromName); ?>)</td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_REDEUFORM_MAIL_DIAG_EFFECTIVE_FROM'); ?></th>
                <td><code><?php echo htmlspecialchars($sendFrom); ?></code></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_REDEUFORM_MAIL_DIAG_RECEIVING'); ?></th>
                <td><?php if ($toEmail): ?>
                    <code><?php echo htmlspecialchars($toEmail); ?></code>
                <?php else: ?>
                    <span class="label label-warning"><?php echo JText::_('COM_REDEUFORM_MAIL_DIAG_NOT_SET'); ?></span>
                <?php endif; ?></td>
            </tr>
            <tr>
                <th><?php echo JText::_('COM_REDEUFORM_DIAG_TURNSTILE_STATUS'); ?></th>
                <td><?php if ($turnstileActive): ?>
                    <span class="label label-success"><?php echo JText::_('COM_REDEUFORM_DIAG_TURNSTILE_ACTIVE'); ?></span>
                <?php else: ?>
                    <span class="label label-default"><?php echo JText::_('COM_REDEUFORM_DIAG_TURNSTILE_DISABLED'); ?></span>
                <?php endif; ?></td>
            </tr>
        </tbody>
    </table>

    <p class="help-block"><?php echo JText::_('COM_REDEUFORM_MAIL_DIAG_HERD_HINT'); ?></p>
    <p class="help-block"><?php echo JText::_('COM_REDEUFORM_DIAG_TURNSTILE_HINT'); ?></p>

    <form action="<?php echo JRoute::_('index.php?option=com_redeuform&task=redeuform.testEmail'); ?>"
          method="post" style="margin-top:12px;">
        <?php echo JHtml::_('form.token'); ?>
        <button type="submit" class="btn btn-default"
            <?php if (empty($toEmail)): ?>disabled="disabled"<?php endif; ?>>
            <span class="icon-mail"></span>
            <?php echo JText::_('COM_REDEUFORM_TEST_EMAIL_BUTTON'); ?>
        </button>
        <?php if (!empty($toEmail)): ?>
        <span class="help-inline">
            <?php echo JText::sprintf('COM_REDEUFORM_TEST_EMAIL_WILL_SEND_TO', htmlspecialchars($toEmail)); ?>
        </span>
        <?php endif; ?>
    </form>

</div>
