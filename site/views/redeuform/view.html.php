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

        // Component CSS — no external CDN dependency
        $doc->addStyleSheet(
            JUri::root(true) . '/media/com_redeuform/css/redeuform.css',
            array('version' => '1.0.7')
        );

        // Cloudflare Turnstile widget script
        $siteKey = trim($this->params->get('turnstile_site_key', ''));
        if (!empty($siteKey)) {
            $doc->addScript('https://challenges.cloudflare.com/turnstile/v0/api.js');
        }

        $this->siteKey = $siteKey;

        parent::display($tpl);
    }
}
