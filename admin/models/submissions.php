<?php
/**
 * @package     com_redeurform
 * @copyright   Copyright (C) 2026 Redooor LLP. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class RedeurformModelSubmissions extends JModelList
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

        $query->select('*')->from($db->quoteName('#__redeurform_submissions'));

        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->quote('%' . $db->escape($search, true) . '%');
            $query->where('(' . $db->quoteName('name') . ' LIKE ' . $search .
                          ' OR ' . $db->quoteName('email') . ' LIKE ' . $search . ')');
        }

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
