<?php
/**
 * @package     com_redeurform
 * @copyright   Copyright (C) 2026 Redooor LLP. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class RedeurformModelRedeurform extends JModelAdmin
{
    public function getTable($type = 'Submission', $prefix = 'RedeurformTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            'com_redeurform.redeurform',
            'redeurform',
            array('control' => 'jform', 'load_data' => $loadData)
        );
        return $form;
    }

    protected function loadFormData()
    {
        return JComponentHelper::getParams('com_redeurform')->toArray();
    }

    public function save($data)
    {
        $params = JComponentHelper::getParams('com_redeurform');
        foreach ($data as $key => $value) {
            $params->set($key, $value);
        }

        $db    = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->update($db->quoteName('#__extensions'))
            ->set($db->quoteName('params') . ' = ' . $db->quote($params->toString()))
            ->where($db->quoteName('element') . ' = ' . $db->quote('com_redeurform'))
            ->where($db->quoteName('type') . ' = ' . $db->quote('component'));
        $db->setQuery($query);
        return $db->execute();
    }
}
