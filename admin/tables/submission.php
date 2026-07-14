<?php
/**
 * @package     com_redeurform
 * @copyright   Copyright (C) 2026 Redooor LLP. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class RedeurformTableSubmission extends JTable
{
    public function __construct(&$db)
    {
        parent::__construct('#__redeurform_submissions', 'id', $db);
    }

    public function check()
    {
        if (empty($this->name)) {
            $this->setError(JText::_('COM_REDEURFORM_ERROR_NAME_REQUIRED'));
            return false;
        }
        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->setError(JText::_('COM_REDEURFORM_ERROR_EMAIL_INVALID'));
            return false;
        }
        if (empty($this->message)) {
            $this->setError(JText::_('COM_REDEURFORM_ERROR_MESSAGE_REQUIRED'));
            return false;
        }
        if (mb_strlen($this->message) > 255) {
            $this->setError(JText::_('COM_REDEURFORM_ERROR_MESSAGE_TOO_LONG'));
            return false;
        }
        return true;
    }
}
