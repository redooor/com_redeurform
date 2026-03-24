<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

class RedeuformControllerMessages extends AdminController
{
  public function delete()
  {
    // Check for CSRF token
    Session::checkToken() or die('Invalid Token');

    // Get the selected IDs from the checkboxes
    $pks = Factory::getApplication()->input->get('cid', array(), 'array');

    if (!empty($pks)) {
      $db = Factory::getDbo();
      $query = $db->getQuery(true);

      // Delete records where ID is in our selected list
      $query->delete($db->quoteName('#__redeuform_messages'))
        ->where($db->quoteName('id') . ' IN (' . implode(',', $pks) . ')');

      $db->setQuery($query);
      $db->execute();

      Factory::getApplication()->enqueueMessage(count($pks) . ' message(s) deleted.');
    }

    $this->setRedirect(Route::_('index.php?option=com_redeuform&view=messages', false));
  }
}
