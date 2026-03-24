<?php
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Component\ComponentHelper;

class RedeuFormModelContact extends BaseDatabaseModel
{
    /**
     * Get the component parameters (Admin Settings)
     * This makes it easy for the Controller or View to access settings.
     */
    public function getParams()
    {
        return ComponentHelper::getParams('com_redeuform');
    }

    /**
     * Optional: Validation logic for the form data
     * Returns true if valid, or an error message if not.
     */
    public function validate($data)
    {
        if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
            return false;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }
}
