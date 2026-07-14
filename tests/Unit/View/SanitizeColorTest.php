<?php
use PHPUnit\Framework\TestCase;

class SanitizeColorTest extends TestCase
{
    private function sanitize(string $value, string $default = '#000000'): string
    {
        $view = new RedeurformViewRedeurform();
        $ref  = new ReflectionMethod($view, 'sanitizeColor');
        $ref->setAccessible(true);
        return $ref->invoke($view, $value, $default);
    }

    public function test_valid_six_digit_hex_passes(): void
    {
        $this->assertSame('#4e9d6d', $this->sanitize('#4e9d6d'));
    }

    public function test_valid_three_digit_hex_passes(): void
    {
        $this->assertSame('#abc', $this->sanitize('#abc'));
    }

    public function test_uppercase_hex_passes(): void
    {
        $this->assertSame('#FFFFFF', $this->sanitize('#FFFFFF'));
    }

    public function test_surrounding_whitespace_is_trimmed(): void
    {
        $this->assertSame('#4e9d6d', $this->sanitize('  #4e9d6d  '));
    }

    public function test_missing_hash_returns_default(): void
    {
        $this->assertSame('#000000', $this->sanitize('4e9d6d'));
    }

    public function test_invalid_characters_return_default(): void
    {
        $this->assertSame('#000000', $this->sanitize('#GGGGGG'));
    }

    public function test_empty_string_returns_default(): void
    {
        $this->assertSame('#ffffff', $this->sanitize('', '#ffffff'));
    }

    public function test_xss_attempt_returns_default(): void
    {
        $this->assertSame('#000000', $this->sanitize('<script>alert(1)</script>'));
    }

    public function test_custom_default_returned_on_invalid(): void
    {
        $this->assertSame('#ff0000', $this->sanitize('bad-value', '#ff0000'));
    }

    public function test_seven_digit_hex_returns_default(): void
    {
        $this->assertSame('#000000', $this->sanitize('#1234567'));
    }

    public function test_two_digit_hex_returns_default(): void
    {
        $this->assertSame('#000000', $this->sanitize('#12'));
    }
}
