<?php defined('_JEXEC') or die; ?>

<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>

<div id="j-main-container" class="span10">
    <form action="<?php echo JRoute::_('index.php?option=com_redeuform&view=redeuform'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">

        <?php foreach ($this->form->getFieldsets() as $fieldset): ?>
        <div class="control-group">
            <h3><?php echo JText::_($fieldset->label); ?></h3>
            <?php foreach ($this->form->getFieldset($fieldset->name) as $field): ?>
            <div class="control-group">
                <div class="control-label"><?php echo $field->label; ?></div>
                <div class="controls"><?php echo $field->input; ?></div>
                <?php if ($field->description): ?>
                <div class="controls">
                    <span class="help-block"><?php echo JText::_($field->description); ?></span>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <hr />
        <?php endforeach; ?>

        <div class="control-group">
            <div class="controls">
                <span class="help-block"><?php echo JText::_('COM_REDEUFORM_EMAIL_TEMPLATE_HELP'); ?></span>
            </div>
        </div>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
