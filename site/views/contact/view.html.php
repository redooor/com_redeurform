<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;

/**
 * HTML View class for the ContactForm Component
 */
class RedeuformViewContact extends HtmlView
{
    /**
     * @var \Joomla\Registry\Registry
     */
    protected $params;

    /**
     * @var string
     */
    protected $page_title;

    /**
     * Display the view
     *
     * @param   string  $tpl  The name of the template file to parse
     * @return  void
     */
    public function display($tpl = null)
    {
        // 1. Get any parameters if you need to show them in the layout
        // For example, if you wanted to show the "Receiving Email" for some reason:
        $this->params = ComponentHelper::getParams('com_redeuform');

        // 2. Prepare any other data (e.g., page titles)
        $app = Factory::getApplication();
        $this->page_title = $app->get('sitename') . ' - Contact Us';

        // 3. Check for errors
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);
        }

        // 4. Call the parent display to load the /tmpl/default.php file
        parent::display($tpl);
    }
}
