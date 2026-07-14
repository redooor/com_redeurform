<?php
/**
 * @package     com_redeurform
 * @copyright   Copyright (C) 2026 Redooor LLP. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class RedeurformViewRedeurform extends JViewLegacy
{
    protected $params;

    public function display($tpl = null)
    {
        $this->params = JComponentHelper::getParams('com_redeurform');
        $this->token  = JSession::getFormToken();

        JHtml::_('behavior.keepalive');

        $doc = JFactory::getDocument();

        // ── Component stylesheet ───────────────────────────────────────────────
        $doc->addStyleSheet(
            JUri::root(true) . '/media/com_redeurform/css/redeurform.css',
            array('version' => '1.1.0')
        );

        // ── Colour overrides via CSS custom properties ─────────────────────────
        // Reads admin-configured colours and injects them as a scoped :root block.
        // Falls back to the defaults baked into redeurform.css if params are empty.
        $colorPrimary    = $this->sanitizeColor($this->params->get('color_primary',   '#4e9d6d'));
        $colorSecondary  = $this->sanitizeColor($this->params->get('color_secondary',  '#f6eb14'));
        $colorBackground = $this->sanitizeColor($this->params->get('color_background', '#ffffff'));
        $colorText       = $this->sanitizeColor($this->params->get('color_text',       '#1a1a1a'));
        $colorLabel      = $this->sanitizeColor($this->params->get('color_label',      '#4e9d6d'));
        $colorButton     = $this->sanitizeColor($this->params->get('color_button',     '#4e9d6d'));
        $colorButtonText = $this->sanitizeColor($this->params->get('color_button_text','#ffffff'));
        $colorAccent1    = $this->sanitizeColor($this->params->get('color_accent1',    '#4e9d6d'));
        $colorAccent2    = $this->sanitizeColor($this->params->get('color_accent2',    '#6db88a'));
        $colorAccent3    = $this->sanitizeColor($this->params->get('color_accent3',    '#f6eb14'));

        $css = '#redeurform-app {' . "\n" .
               '  --rf-primary:      ' . $colorPrimary    . ";\n" .
               '  --rf-secondary:    ' . $colorSecondary  . ";\n" .
               '  --rf-bg:           ' . $colorBackground . ";\n" .
               '  --rf-text:         ' . $colorText       . ";\n" .
               '  --rf-label:        ' . $colorLabel      . ";\n" .
               '  --rf-btn-bg:       ' . $colorButton     . ";\n" .
               '  --rf-btn-text:     ' . $colorButtonText . ";\n" .
               '  --rf-accent1:      ' . $colorAccent1    . ";\n" .
               '  --rf-accent2:      ' . $colorAccent2    . ";\n" .
               '  --rf-accent3:      ' . $colorAccent3    . ";\n" .
               '}';

        $doc->addStyleDeclaration($css);

        // ── Cloudflare Turnstile ───────────────────────────────────────────────
        $siteKey = trim($this->params->get('turnstile_site_key', ''));
        if (!empty($siteKey)) {
            $doc->addScript('https://challenges.cloudflare.com/turnstile/v0/api.js');
        }

        $this->siteKey = $siteKey;

        parent::display($tpl);
    }

    /**
     * Sanitize a hex colour value — allows only #RGB and #RRGGBB formats.
     * Returns the default if the value is invalid.
     */
    private function sanitizeColor($value, $default = '#000000')
    {
        $value = trim($value);
        if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value)) {
            return $value;
        }
        return $default;
    }
}
