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

function rfSanitizeColor($value, $default)
{
    $value = trim($value);
    if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value)) {
        return $value;
    }
    return $default;
}

$previewColors = array(
    'color_primary'      => rfSanitizeColor($params->get('color_primary',      '#4e9d6d'), '#4e9d6d'),
    'color_secondary'    => rfSanitizeColor($params->get('color_secondary',    '#f6eb14'), '#f6eb14'),
    'color_background'   => rfSanitizeColor($params->get('color_background',   '#ffffff'), '#ffffff'),
    'color_text'         => rfSanitizeColor($params->get('color_text',         '#1a1a1a'), '#1a1a1a'),
    'color_label'        => rfSanitizeColor($params->get('color_label',        '#4e9d6d'), '#4e9d6d'),
    'color_button'       => rfSanitizeColor($params->get('color_button',       '#4e9d6d'), '#4e9d6d'),
    'color_button_text'  => rfSanitizeColor($params->get('color_button_text',  '#ffffff'), '#ffffff'),
    'color_accent1'      => rfSanitizeColor($params->get('color_accent1',      '#4e9d6d'), '#4e9d6d'),
    'color_accent2'      => rfSanitizeColor($params->get('color_accent2',      '#6db88a'), '#6db88a'),
    'color_accent3'      => rfSanitizeColor($params->get('color_accent3',      '#f6eb14'), '#f6eb14'),
);

// Maps colour field names to the CSS custom property they control.
$previewVarMap = array(
    'color_primary'     => '--rf-primary',
    'color_secondary'   => '--rf-secondary',
    'color_background'  => '--rf-bg',
    'color_text'        => '--rf-text',
    'color_label'       => '--rf-label',
    'color_button'      => '--rf-btn-bg',
    'color_button_text' => '--rf-btn-text',
    'color_accent1'     => '--rf-accent1',
    'color_accent2'     => '--rf-accent2',
    'color_accent3'     => '--rf-accent3',
);

function rfPlaceholderTable()
{
    $placeholders = array(
        '{name}'            => JText::_('COM_REDEURFORM_PLACEHOLDER_NAME_DESC'),
        '{email}'           => JText::_('COM_REDEURFORM_PLACEHOLDER_EMAIL_DESC'),
        '{phone}'           => JText::_('COM_REDEURFORM_PLACEHOLDER_PHONE_DESC'),
        '{message}'         => JText::_('COM_REDEURFORM_PLACEHOLDER_MESSAGE_DESC'),
        '{receiving_email}' => JText::_('COM_REDEURFORM_PLACEHOLDER_RECEIVING_EMAIL_DESC'),
        '{sendfrom_email}'  => JText::_('COM_REDEURFORM_PLACEHOLDER_SENDFROM_EMAIL_DESC'),
    );

    echo '<div class="rf-admin-placeholder-table">';
    echo '<p class="rf-admin-placeholder-title">' . JText::_('COM_REDEURFORM_EMAIL_TEMPLATE_PLACEHOLDERS_TITLE') . '</p>';
    echo '<table class="table table-condensed">';
    echo '<tbody>';
    foreach ($placeholders as $token => $desc) {
        echo '<tr><td><code>' . htmlspecialchars($token) . '</code></td><td>' . htmlspecialchars($desc) . '</td></tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}

function rfRenderField($field, $isJ4)
{
    if ($isJ4) {
        echo '<div class="mb-3">';
        echo '<label class="form-label">' . $field->label . '</label>';
        echo $field->input;
        if ($field->description) {
            echo '<div class="form-text">' . JText::_($field->description) . '</div>';
        }
        echo '</div>';
    } else {
        echo '<div class="control-group">';
        echo '<div class="control-label">' . $field->label . '</div>';
        echo '<div class="controls">' . $field->input . '</div>';
        if ($field->description) {
            echo '<div class="controls"><span class="help-block">' . JText::_($field->description) . '</span></div>';
        }
        echo '</div>';
    }

    if ($field->fieldname === 'email_template') {
        rfPlaceholderTable();
    }
}
?>

<?php if (!$isJ4 && !empty($this->sidebar)): ?>
<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10 rf-admin-wrap">
<?php else: ?>
<div id="j-main-container" class="rf-admin-wrap">
<?php endif; ?>

<!-- Tab navigation -->
<ul class="nav nav-tabs rf-admin-tabs" id="rf-tab-nav">
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

<div class="rf-admin-card">

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
            <div class="rf-admin-colour-layout">

                <div class="rf-admin-colour-fields">
                    <?php foreach ($this->form->getFieldset('colours') as $field): ?>
                        <div data-rf-var="<?php echo htmlspecialchars($previewVarMap[$field->fieldname]); ?>">
                            <?php rfRenderField($field, $isJ4); ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="rf-admin-colour-preview">
                    <p class="rf-admin-preview-title"><?php echo JText::_('COM_REDEURFORM_COLOUR_PREVIEW_TITLE'); ?></p>

                    <style>
                        .rf-admin-colour-preview #redeurform-app {
                            <?php foreach ($previewColors as $field => $value): ?>
                            <?php echo $previewVarMap[$field]; ?>: <?php echo $value; ?>;
                            <?php endforeach; ?>
                        }
                    </style>

                    <div id="redeurform-app">
                        <div class="rf-container">
                            <div class="rf-header">
                                <h1 class="rf-title"><?php echo JText::_('COM_REDEURFORM_TITLE'); ?></h1>
                                <p class="rf-subtitle"><?php echo JText::_('COM_REDEURFORM_SUBTITLE'); ?></p>
                            </div>
                            <div class="rf-card">
                                <div class="rf-card-accent"></div>
                                <div class="rf-card-body">
                                    <div class="rf-form">
                                        <div class="rf-field">
                                            <label class="rf-label"><?php echo JText::_('COM_REDEURFORM_FIELD_NAME'); ?><span class="rf-required">*</span></label>
                                            <input type="text" class="rf-input" placeholder="<?php echo JText::_('COM_REDEURFORM_FIELD_NAME_PLACEHOLDER'); ?>" readonly />
                                        </div>
                                        <div class="rf-field">
                                            <label class="rf-label"><?php echo JText::_('COM_REDEURFORM_FIELD_EMAIL'); ?><span class="rf-required">*</span></label>
                                            <input type="text" class="rf-input" placeholder="<?php echo JText::_('COM_REDEURFORM_FIELD_EMAIL_PLACEHOLDER'); ?>" readonly />
                                        </div>
                                        <div class="rf-field">
                                            <label class="rf-label"><?php echo JText::_('COM_REDEURFORM_FIELD_MESSAGE'); ?><span class="rf-required">*</span></label>
                                            <textarea class="rf-textarea" placeholder="<?php echo JText::_('COM_REDEURFORM_FIELD_MESSAGE_PLACEHOLDER'); ?>" readonly></textarea>
                                        </div>
                                        <button type="button" class="rf-submit" tabindex="-1">
                                            <?php echo JText::_('COM_REDEURFORM_BUTTON_SEND'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </form>

    <!-- Tab: Mail Configuration Diagnostics (outside adminForm — has its own form for test email) -->
    <div id="rf-pane-diag" class="rf-pane" style="display:none;">

        <table class="table table-striped table-condensed rf-admin-diag-table" style="max-width:640px;">
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

    // Live colour preview — delegated so it works regardless of how Joomla's
    // "color" field type renders internally (native <input type="color">,
    // or a JS-enhanced picker in some Joomla versions).
    var previewApp      = document.querySelector('.rf-admin-colour-preview #redeurform-app');
    var colourContainer = document.querySelector('.rf-admin-colour-fields');

    function updatePreview(e) {
        var wrapper = e.target.closest ? e.target.closest('[data-rf-var]') : null;
        if (!wrapper || !e.target.value) return;
        previewApp.style.setProperty(wrapper.getAttribute('data-rf-var'), e.target.value);
    }

    if (previewApp && colourContainer) {
        // capture: true so we catch the event even if a widget dispatches it
        // without bubbling
        colourContainer.addEventListener('input', updatePreview, true);
        colourContainer.addEventListener('change', updatePreview, true);
    }
}());
</script>

</div>
