<?php

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

?>
<form action="index.php?option=com_redeuform" method="post" name="adminForm" id="adminForm">
  <!-- Search Bar -->
  <div id="filter-bar" class="btn-toolbar">
    <div class="filter-search btn-group pull-left">
      <input type="text" name="filter_search" id="filter_search"
        placeholder="Search name or email"
        value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
    </div>
    <div class="btn-group pull-left">
      <button type="submit" class="btn hasTooltip" title="Search"><i class="icon-search"></i></button>
      <button type="button" class="btn hasTooltip" title="Clear" onclick="document.getElementById('filter_search').value='';this.form.submit();">
        <i class="icon-remove"></i>
      </button>
    </div>
  </div>
  <div id="j-main-container">
    <table class="table table-striped">
      <thead>
        <tr>
          <th width="1%"><?php echo HTMLHelper::_('grid.checkall'); ?></th> <!-- Check All -->
          <th width="5%">ID</th>
          <th width="15%">Name</th>
          <th width="15%">Email</th>
          <th width="40%">Message</th>
          <th width="15%">Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($this->items as $i => $item) : ?>
          <tr>
            <td><?php echo HTMLHelper::_('grid.id', $i, $item->id); ?></td> <!-- Row Checkbox -->
            <td><?php echo $item->id; ?></td>
            <td><?php echo $item->name; ?></td>
            <td><?php echo $item->email; ?></td>
            <td><?php echo $item->message; ?></td>
            <td><?php echo $item->created; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="6">
            <?php echo $this->pagination->getListFooter(); ?>
          </td>
        </tr>
      </tfoot>
    </table>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo HTMLHelper::_('form.token'); ?>
  </div>
</form>