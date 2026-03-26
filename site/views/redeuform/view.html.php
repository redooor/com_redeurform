<?php
defined('_JEXEC') or die;

class RedeuformViewRedeuform extends JViewLegacy
{
    protected $params;

    public function display($tpl = null)
    {
        $this->params = JComponentHelper::getParams('com_redeuform');
        $this->token  = JSession::getFormToken();

        JHtml::_('behavior.keepalive');

        $doc = JFactory::getDocument();

        // Prefix config MUST be declared before the Tailwind script tag
        $doc->addScriptDeclaration('window.tailwind = { config: { prefix: "tw-" } };');

        // Use the pinned versioned URL — the bare CDN URL returns a 302 redirect
        // which some servers/browsers do not follow for script tags
        $doc->addScript('https://cdn.tailwindcss.com/3.4.17');

        // Load reCAPTCHA if site key configured
        $siteKey = $this->params->get('recaptcha_site_key', '');
        if (!empty($siteKey)) {
            $doc->addScript('https://www.google.com/recaptcha/api.js');
        }

        $this->siteKey = $siteKey;

        parent::display($tpl);
    }
}
