<?php
use PHPUnit\Framework\TestCase;

class AdminSettingsFormTest extends TestCase
{
    public function test_form_validator_is_loaded_for_joomla_four_toolbar_submission(): void
    {
        $view = file_get_contents(__DIR__ . '/../../../admin/views/redeurform/view.html.php');

        $this->assertStringContainsString("JHtml::_('behavior.formvalidator');", $view);
    }
}
