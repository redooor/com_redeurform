<?php
// JTable uses arbitrary column names as dynamic properties (one per DB column).
#[\AllowDynamicProperties]
class JTable
{
    protected $_tbl     = '';
    protected $_tbl_key = '';
    protected $_db;
    private $_errors    = [];

    public function __construct($table, $key, $db)
    {
        $this->_tbl     = $table;
        $this->_tbl_key = $key;
        $this->_db      = $db;
    }

    public function setError($error)
    {
        $this->_errors[] = $error;
    }

    public function getError($i = null)
    {
        if ($i === null) {
            return end($this->_errors) ?: '';
        }
        return $this->_errors[$i] ?? '';
    }

    public function getErrors()
    {
        return $this->_errors;
    }
}
