<?php
/**
 * @package     com_redeurform
 * @copyright   Copyright (C) 2026 Redooor LLP. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$isJ4 = RedeurformHelper::isJoomla4();

$jConfig         = JFactory::getConfig();
$params          = JComponentHelper::getParams('com_redeurform');
$mailerType      = $jConfig->get('mailer', 'mail');
$smtpHost        = $jConfig->get('smtphost', '—');
$smtpPort        = $jConfig->get('smtpport', '—');
$smtpAuth        = $jConfig->get('smtpauth', '0') ? JText::_('JYES') : JText::_('JNO');
$mailFrom        = $jConfig->get('mailfrom', '—');
$fromName        = $jConfig->get('fromname', '—');
$toEmail         = trim($params->get('receiving_email', ''));
$sendFrom        = trim($params->get('sendfrom_email', ''));
if (empty($sendFrom)) {
    $sendFrom = $jConfig->get('mailfrom', '—') . ' ' . JText::_('COM_REDEURFORM_MAIL_DIAG_GLOBAL_FALLBACK');
}
$tsiteKey        = trim($params->get('turnstile_site_key', ''));
$tsecretKey      = trim($params->get('turnstile_secret_key', ''));
$turnstileActive = !empty($tsiteKey) && !empty($tsecretKey);

function rfRenderField($field, $isJ4)
{
    $extraHelp = ($field->fieldname === 'email_template')
        ? JText::_('COM_REDEURFORM_EMAIL_TEMPLATE_HELP')
        : null;

    if ($isJ4) {
        echo '<div class="mb-3">';
        echo '<label class="form-label">' . $field->label . '</label>';
        echo $field->input;
        if ($field->description) {
            echo '<div class="form-text">' . JText::_($field->description) . '</div>';
        }
        if ($extraHelp) {
            echo '<div class="form-text">' . $extraHelp . '</div>';
        }
        echo '</div>';
    } else {
        echo '<div class="control-group">';
        echo '<div class="control-label">' . $field->label . '</div>';
        echo '<div class="controls">' . $field->input . '</div>';
        if ($field->description) {
            echo '<div class="controls"><span class="help-block">' . JText::_($field->description) . '</span></div>';
        }
        if ($extraHelp) {
            echo '<div class="controls"><span class="help-block">' . $extraHelp . '</span></div>';
        }
        echo '</div>';
    }
}
?>

<?php if (!$isJ4 && !empty($this->sidebar)): ?>
<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else: ?>
<div id="j-main-container">
<?php endif; ?>

<!-- Tab navigation -->
<ul class="nav nav-tabs" id="rf-tab-nav" style="margin-bottom:0;">
    <li class="nav-item active">
        <a class="nav-link active" href="#" data-rf-tab="rf-pane-email">
            <?php echo JText::_('COM_REDEURFORM_SETTINGS_FIELDSET_EMAIL'); ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-rf-tab="rf-pane-turnstile">
            <?php echo JText::_('COM_REDEURFORM_SETTINGS_FIELDSET_TURNSTILE'); ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-rf-tab="rf-pane-colours">
            <?php echo JText::_('COM_REDEURFORM_SETTINGS_FIELDSET_COLOURS'); ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-rf-tab="rf-pane-diag">
            <?php echo JText::_('COM_REDEURFORM_MAIL_DIAG_TITLE'); ?>
        </a>
    </li>
</ul>

<div style="border:1px solid #ddd; border-top:none; padding:20px;">

    <!-- Main settings form (wraps the first three tab panes) -->
    <form action="<?php echo JRoute::_('index.php?option=com_redeurform&view=redeurform'); ?>"
          method="post" name="adminForm" id="adminForm" class="form-validate">

        <!-- Tab: Email Configuration -->
        <div id="rf-pane-email" class="rf-pane">
            <?php foreach ($this->form->getFieldset('basic') as $field): ?>
                <?php rfRenderField($field, $isJ4); ?>
            <?php endforeach; ?>
        </div>

        <!-- Tab: Cloudflare Turnstile -->
        <div id="rf-pane-turnstile" class="rf-pane" style="display:none;">
            <?php foreach ($this->form->getFieldset('turnstile') as $field): ?>
                <?php rfRenderField($field, $isJ4); ?>
            <?php endforeach; ?>
        </div>

        <!-- Tab: Colour Scheme -->
        <div id="rf-pane-colours" class="rf-pane" style="display:none;">
            <?php foreach ($this->form->getFieldset('colours') as $field): ?>
                <?php rfRenderField($field, $isJ4); ?>
            <?php endforeach; ?>
        </div>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </form>

    <!-- Tab: Mail Configuration Diagnostics (outside adminForm — has its own form for test email) -->
    <div id="rf-pane-diag" class="rf-pane" style="display:none;">

        <table class="table table-striped table-condensed" style="max-width:640px;">
            <tbody>
                <tr>
                    <th style="width:240px;"><?php echo JText::_('COM_REDEURFORM_MAIL_DIAG_JOOMLA_MAILER'); ?></th>
                    <td><code><?php echo htmlspecialchars($mailerType); ?></code></td>
                </tr>
                <tr>
                    <th><?php echo JText::_('COM_REDEURFORM_MAIL_DIAG_SMTP_HOST'); ?></th>
                    <td><code><?php echo htmlspecialchars($smtpHost); ?></code></td>
                </tr>
                <tr>
                    <th><?php echo JText::_('COM_REDEURFORM_MAIL_DIAG_SMTP_PORT'); ?></th>
                    <td><code><?php echo htmlspecialchars($smtpPort); ?></code></td>
                </tr>
                <tr>
                    <th><?php echo JText::_('COM_REDEURFORM_MAIL_DIAG_SMTP_AUTH'); ?></th>
                    <td><?php echo $smtpAuth; ?></td>
                </tr>
                <tr>
                    <th><?php echo JText::_('COM_REDEURFORM_MAIL_DIAG_GLOBAL_FROM'); ?></th>
                    <td><code><?php echo htmlspecialchars($mailFrom); ?></code>
                        &nbsp;(<?php echo htmlspecialchars($fromName); ?>)</td>
                </tr>
                <tr>
                    <th><?php echo JText::_('COM_REDEURFORM_MAIL_DIAG_EFFECTIVE_FROM'); ?></th>
                    <td><code><?php echo htmlspecialchars($sendFrom); ?></code></td>
                </tr>
                <tr>
                    <th><?php echo JText::_('COM_REDEURFORM_MAIL_DIAG_RECEIVING'); ?></th>
                    <td><?php if ($toEmail): ?>
                        <code><?php echo htmlspecialchars($toEmail); ?></code>
                    <?php else: ?>
                        <span class="label label-warning"><?php echo JText::_('COM_REDEURFORM_MAIL_DIAG_NOT_SET'); ?></span>
                    <?php endif; ?></td>
                </tr>
                <tr>
                    <th><?php echo JText::_('COM_REDEURFORM_DIAG_TURNSTILE_STATUS'); ?></th>
                    <td><?php if ($turnstileActive): ?>
                        <span class="label label-success"><?php echo JText::_('COM_REDEURFORM_DIAG_TURNSTILE_ACTIVE'); ?></span>
                    <?php else: ?>
                        <span class="label label-default"><?php echo JText::_('COM_REDEURFORM_DIAG_TURNSTILE_DISABLED'); ?></span>
                    <?php endif; ?></td>
                </tr>
            </tbody>
        </table>

        <p class="help-block"><?php echo JText::_('COM_REDEURFORM_MAIL_DIAG_HERD_HINT'); ?></p>
        <p class="help-block"><?php echo JText::_('COM_REDEURFORM_DIAG_TURNSTILE_HINT'); ?></p>

        <form action="<?php echo JRoute::_('index.php?option=com_redeurform&task=redeurform.testEmail'); ?>"
              method="post" style="margin-top:12px;">
            <?php echo JHtml::_('form.token'); ?>
            <button type="submit" class="btn <?php echo $isJ4 ? 'btn-secondary' : 'btn-default'; ?>"
                <?php if (empty($toEmail)): ?>disabled="disabled"<?php endif; ?>>
                <span class="icon-mail"></span>
                <?php echo JText::_('COM_REDEURFORM_TEST_EMAIL_BUTTON'); ?>
            </button>
            <?php if (!empty($toEmail)): ?>
            <span class="help-inline">
                <?php echo JText::sprintf('COM_REDEURFORM_TEST_EMAIL_WILL_SEND_TO', htmlspecialchars($toEmail)); ?>
            </span>
            <?php endif; ?>
        </form>

    </div><!-- /#rf-pane-diag -->

</div><!-- /tab content wrapper -->

<script>
(function () {
    var tabs  = document.querySelectorAll('#rf-tab-nav a[data-rf-tab]');
    var panes = document.querySelectorAll('.rf-pane');

    function activate(tabEl) {
        // Update nav active state (both <li> for Bootstrap 2 and <a> for Bootstrap 5)
        tabs.forEach(function (t) {
            t.classList.remove('active');
            t.parentElement.classList.remove('active');
        });
        tabEl.classList.add('active');
        tabEl.parentElement.classList.add('active');

        // Show the matching pane, hide the rest
        var target = tabEl.getAttribute('data-rf-tab');
        panes.forEach(function (p) {
            p.style.display = (p.id === target) ? 'block' : 'none';
        });
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            activate(this);
        });
    });
}());
</script>

</div>
