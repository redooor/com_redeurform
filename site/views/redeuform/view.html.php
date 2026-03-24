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

        // Load Tailwind CSS via CDN
        $doc = JFactory::getDocument();
        $doc->addStyleSheet('https://cdn.tailwindcss.com');

        // Load reCAPTCHA if site key configured
        $siteKey = $this->params->get('recaptcha_site_key', '');
        if (!empty($siteKey)) {
            $doc->addScript('https://www.google.com/recaptcha/api.js');
        }

        $this->siteKey = $siteKey;

        parent::display($tpl);
    }
}
