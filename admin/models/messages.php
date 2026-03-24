<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;

class RedeuformModelMessages extends ListModel
{
  public function __construct($config = array())
  {
    // Define fields that can be used for filtering/sorting if needed
    if (empty($config['filter_fields'])) {
      $config['filter_fields'] = array('id', 'name', 'email', 'created');
    }
    parent::__construct($config);
  }

  protected function populateState($ordering = null, $direction = null)
  {
    $app = Factory::getApplication();

    // 1. Capture the search term from the request
    $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
    $this->setState('filter.search', $search);

    parent::populateState($ordering, $direction);
  }

  protected function getListQuery()
  {
    $db    = Factory::getDbo();
    $query = $db->getQuery(true);

    $query->select('*')
      ->from($db->quoteName('#__redeuform_messages'));

    // 2. Apply search filter if a term is entered
    $search = $this->getState('filter.search');

    if (!empty($search)) {
      $search = $db->quote('%' . $db->escape($search, true) . '%');
      $query->where('(' . $db->quoteName('name') . ' LIKE ' . $search .
        ' OR ' . $db->quoteName('email') . ' LIKE ' . $search . ')');
    }

    $query->order('created DESC');
    return $query;
  }
}
