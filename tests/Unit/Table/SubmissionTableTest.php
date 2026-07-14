<?php
use PHPUnit\Framework\TestCase;

class SubmissionTableTest extends TestCase
{
    private function make(): RedeurformTableSubmission
    {
        $db = null;
        return new RedeurformTableSubmission($db);
    }

    public function test_valid_data_passes(): void
    {
        $table          = $this->make();
        $table->name    = 'John Doe';
        $table->email   = 'john@example.com';
        $table->message = 'Hello world';

        $this->assertTrue($table->check());
    }

    public function test_missing_name_fails(): void
    {
        $table          = $this->make();
        $table->name    = '';
        $table->email   = 'john@example.com';
        $table->message = 'Hello';

        $this->assertFalse($table->check());
        $this->assertSame('COM_REDEURFORM_ERROR_NAME_REQUIRED', $table->getError());
    }

    public function test_invalid_email_fails(): void
    {
        $table          = $this->make();
        $table->name    = 'John';
        $table->email   = 'not-an-email';
        $table->message = 'Hello';

        $this->assertFalse($table->check());
        $this->assertSame('COM_REDEURFORM_ERROR_EMAIL_INVALID', $table->getError());
    }

    public function test_empty_email_fails(): void
    {
        $table          = $this->make();
        $table->name    = 'John';
        $table->email   = '';
        $table->message = 'Hello';

        $this->assertFalse($table->check());
        $this->assertSame('COM_REDEURFORM_ERROR_EMAIL_INVALID', $table->getError());
    }

    public function test_missing_message_fails(): void
    {
        $table          = $this->make();
        $table->name    = 'John';
        $table->email   = 'john@example.com';
        $table->message = '';

        $this->assertFalse($table->check());
        $this->assertSame('COM_REDEURFORM_ERROR_MESSAGE_REQUIRED', $table->getError());
    }

    public function test_message_over_255_chars_fails(): void
    {
        $table          = $this->make();
        $table->name    = 'John';
        $table->email   = 'john@example.com';
        $table->message = str_repeat('a', 256);

        $this->assertFalse($table->check());
        $this->assertSame('COM_REDEURFORM_ERROR_MESSAGE_TOO_LONG', $table->getError());
    }

    public function test_message_exactly_255_chars_passes(): void
    {
        $table          = $this->make();
        $table->name    = 'John';
        $table->email   = 'john@example.com';
        $table->message = str_repeat('a', 255);

        $this->assertTrue($table->check());
    }

    public function test_phone_is_optional(): void
    {
        $table          = $this->make();
        $table->name    = 'John';
        $table->email   = 'john@example.com';
        $table->message = 'Hello';
        // phone intentionally not set

        $this->assertTrue($table->check());
    }

    public function test_validation_stops_at_first_error(): void
    {
        $table          = $this->make();
        $table->name    = '';
        $table->email   = 'bad-email';
        $table->message = '';

        $this->assertFalse($table->check());
        // Only the first error (name) should be recorded
        $this->assertCount(1, $table->getErrors());
        $this->assertSame('COM_REDEURFORM_ERROR_NAME_REQUIRED', $table->getError());
    }
}
