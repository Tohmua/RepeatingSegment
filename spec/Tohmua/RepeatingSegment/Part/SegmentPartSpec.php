<?php

namespace spec\Tohmua\RepeatingSegment\Part;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SegmentPartSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(['section' => 'foo', 'constraint' => 'bar']);
        $this->shouldHaveType('Tohmua\RepeatingSegment\Part\SegmentPart');
    }

    public function it_should_throw_exception_if_missing_section()
    {
        $this->shouldThrow('Tohmua\RepeatingSegment\Part\Exception\PartException')->during('__construct', [['constraint' => 'foo']]);
    }

    public function it_should_throw_exception_if_missing_constraint()
    {
        $this->shouldThrow('Tohmua\RepeatingSegment\Part\Exception\PartException')->during('__construct', [['section' => []]]);
    }

    public function it_should_throw_exception_if_invalid_section()
    {
        $this->shouldThrow('Tohmua\RepeatingSegment\Part\Exception\PartException')->during('__construct', [['section' => [], 'constraint' => 'foo']]);
    }

    public function it_should_throw_exception_if_invalid_constraint()
    {
        $this->shouldThrow('Tohmua\RepeatingSegment\Part\Exception\PartException')->during('__construct', [['section' => 'foo', 'constraint' => []]]);
    }

    public function it_should_return_section()
    {
        $this->beConstructedWith(['section' => 'foo', 'constraint' => '[a-z]+']);
        $this->section()->shouldReturn('foo');
    }

    public function it_should_return_regex_without_slash_for_empty_section()
    {
        $this->beConstructedWith(['section' => '', 'constraint' => '[a-z]+']);
        $this->regex()->shouldReturn('');
    }

    public function it_should_return_regex_with_slash_for_section()
    {
        $this->beConstructedWith(['section' => 'foo', 'constraint' => '[a-z]+']);
        $this->regex()->shouldReturn('([a-z]+)+');
    }

    public function it_should_return_non_repeating_regex_without_slash_for_empty_section()
    {
        $this->beConstructedWith(['section' => '', 'constraint' => '[a-z]+']);
        $this->nonRepeatingRegex()->shouldReturn('');
    }

    public function it_should_return_non_repeating_regex_with_slash_for_section()
    {
        $this->beConstructedWith(['section' => 'foo', 'constraint' => '[a-z]+']);
        $this->nonRepeatingRegex()->shouldMatch('#\(\?P<foo_[a-z0-9]{13}>\[a\-z\]\+\)#');
    }
}
