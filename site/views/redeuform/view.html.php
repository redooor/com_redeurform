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

        // Load component CSS — no external CDN dependency
        $doc->addStyleSheet(
            JUri::root(true) . '/media/com_redeuform/css/redeuform.css',
            array('version' => '1.0.4')
        );

        // reCAPTCHA v3 — loads a single lightweight script, no iframe widget
        $siteKey = $this->params->get('recaptcha_site_key', '');
        if (!empty($siteKey)) {
            $doc->addScript(
                'https://www.google.com/recaptcha/api.js?render=' . htmlspecialchars($siteKey)
            );
        }

        $this->siteKey = $siteKey;

        parent::display($tpl);
    }
}
