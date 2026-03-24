<?php
defined('_JEXEC') or die;

class RedeuformModelSubmissions extends JModelList
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array('id', 'name', 'email', 'created_at');
        }
        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db    = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('*')
              ->from($db->quoteName('#__redeuform_submissions'));

        // Search filter
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%' . $db->escape($search, true) . '%');
            $query->where('(' . $db->quoteName('name') . ' LIKE ' . $search .
                          ' OR ' . $db->quoteName('email') . ' LIKE ' . $search . ')');
        }

        // Ordering
        $orderCol = $this->state->get('list.ordering', 'created_at');
        $orderDir = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDir));

        return $query;
    }

    protected function populateState($ordering = 'created_at', $direction = 'desc')
    {
        $this->setState('filter.search', $this->getUserStateFromRequest(
            $this->context . '.filter.search', 'filter_search', '', 'string'
        ));
        parent::populateState($ordering, $direction);
    }
}
