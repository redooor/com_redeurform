<?php

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

class RedeuformViewMessages extends HtmlView
{
  /** @var array List of message items */
  protected $items;

  /** @var \Joomla\CMS\Pagination\Pagination Pagination object */
  protected $pagination;

  /** @var \Joomla\CMS\Object\CMSObject State object */
  protected $state;

  function display($tpl = null)
  {
    // Get data from the model
    $this->items = $this->get('Items');

    // Fetch the pagination object
    $this->pagination = $this->get('Pagination');

    // Pass state to view
    $this->state = $this->get('State');

    // Check for errors
    if (count($errors = $this->get('Errors'))) {
      throw new Exception(implode("\n", $errors), 500);
    }

    // Set the toolbar title in the admin
    ToolbarHelper::title('Redeuform: Messages', 'address');

    // Add the delete button. 'messages.delete' calls the delete method in the messages controller.
    ToolbarHelper::deleteList('Are you sure you want to delete these messages?', 'messages.delete');

    ToolbarHelper::preferences('com_redeuform'); // Link to your email settings

    parent::display($tpl);
  }
}
