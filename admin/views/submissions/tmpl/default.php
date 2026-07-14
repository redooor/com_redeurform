<?php
/**
 * @package     com_redeurform
 * @copyright   Copyright (C) 2026 Redooor LLP. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$isJ4 = RedeurformHelper::isJoomla4();
?>

<?php if (!$isJ4 && !empty($this->sidebar)): ?>
<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else: ?>
<div id="j-main-container">
<?php endif; ?>

    <form action="<?php echo JRoute::_('index.php?option=com_redeurform&view=submissions'); ?>" method="post" name="adminForm" id="adminForm">

        <!-- Search bar -->
        <?php if ($isJ4): ?>
        <div class="js-stools-container-bar mb-3">
            <div class="input-group">
                <input type="text" name="filter_search" id="filter_search"
                    placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"
                    value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                    class="form-control"
                    aria-label="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>" />
                <button type="submit" class="btn btn-primary" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
                    <span class="icon-search" aria-hidden="true"></span>
                </button>
                <a href="<?php echo JRoute::_('index.php?option=com_redeurform&view=submissions'); ?>"
                   class="btn btn-secondary" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>">
                    <span class="icon-times" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        <?php else: ?>
        <div id="filter-bar" class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                <label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
                <input type="text" name="filter_search" id="filter_search"
                    placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"
                    value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                    class="inputbox" />
            </div>
            <div class="btn-group pull-left">
                <button type="submit" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
                    <span class="icon-search"></span>
                </button>
                <a href="<?php echo JRoute::_('index.php?option=com_redeurform&view=submissions'); ?>"
                   class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>">
                    <span class="icon-remove"></span>
                </a>
            </div>
        </div>
        <div class="clearfix"></div>
        <?php endif; ?>

        <table class="table table-striped" id="submissionList">
            <thead>
                <tr>
                    <th width="1%"><?php echo JHtml::_('grid.checkall'); ?></th>
                    <th><?php echo JHtml::_('grid.sort', 'COM_REDEURFORM_FIELD_NAME',  'name',       $this->listDirn, $this->listOrder); ?></th>
                    <th><?php echo JHtml::_('grid.sort', 'COM_REDEURFORM_FIELD_EMAIL', 'email',      $this->listDirn, $this->listOrder); ?></th>
                    <th><?php echo JText::_('COM_REDEURFORM_FIELD_PHONE'); ?></th>
                    <th><?php echo JText::_('COM_REDEURFORM_FIELD_MESSAGE'); ?></th>
                    <th><?php echo JText::_('COM_REDEURFORM_FIELD_IP'); ?></th>
                    <th><?php echo JHtml::_('grid.sort', 'COM_REDEURFORM_FIELD_DATE',  'created_at', $this->listDirn, $this->listOrder); ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
                </tr>
            </tfoot>
            <tbody>
                <?php if (empty($this->items)): ?>
                    <tr><td colspan="7" class="center"><?php echo JText::_('COM_REDEURFORM_NO_SUBMISSIONS'); ?></td></tr>
                <?php else: ?>
                    <?php foreach ($this->items as $i => $item): ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
                        <td><?php echo $this->escape($item->name); ?></td>
                        <td><?php echo $this->escape($item->email); ?></td>
                        <td><?php echo $this->escape($item->phone); ?></td>
                        <td><?php echo nl2br($this->escape($item->message)); ?></td>
                        <td><?php echo $this->escape($item->ip_address); ?></td>
                        <td><?php echo JHtml::_('date', $item->created_at, JText::_('DATE_FORMAT_LC2')); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <input type="hidden" name="task"         value="" />
        <input type="hidden" name="boxchecked"   value="0" />
        <input type="hidden" name="filter_order"     value="<?php echo $this->escape($this->listOrder); ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $this->escape($this->listDirn); ?>" />
        <?php echo JHtml::_('form.token'); ?>

    </form>
</div>
