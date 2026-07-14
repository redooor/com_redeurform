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
        $allowed = array(
            'receiving_email', 'sendfrom_email', 'email_template',
            'turnstile_site_key', 'turnstile_secret_key',
            'color_primary', 'color_secondary', 'color_background',
            'color_text', 'color_label', 'color_button',
            'color_button_text', 'color_accent1', 'color_accent2', 'color_accent3',
        );
        $params = JComponentHelper::getParams('com_redeurform');
        foreach (array_intersect_key($data, array_flip($allowed)) as $key => $value) {
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
