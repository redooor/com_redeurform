<?php
defined('_JEXEC') or die;

class RedeuformModelRedeuform extends JModelAdmin
{
    public function getTable($type = 'Submission', $prefix = 'RedeuformTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            'com_redeuform.redeuform',
            'redeuform',
            array('control' => 'jform', 'load_data' => $loadData)
        );
        return $form;
    }

    protected function loadFormData()
    {
        return JComponentHelper::getParams('com_redeuform')->toArray();
    }

    public function save($data)
    {
        $params = JComponentHelper::getParams('com_redeuform');
        foreach ($data as $key => $value) {
            $params->set($key, $value);
        }

        $db    = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->update($db->quoteName('#__extensions'))
            ->set($db->quoteName('params') . ' = ' . $db->quote($params->toString()))
            ->where($db->quoteName('element') . ' = ' . $db->quote('com_redeuform'))
            ->where($db->quoteName('type') . ' = ' . $db->quote('component'));
        $db->setQuery($query);
        return $db->execute();
    }
}
