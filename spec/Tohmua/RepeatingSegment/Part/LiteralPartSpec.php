<?php

namespace spec\Tohmua\RepeatingSegment\Part;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LiteralPartSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->beConstructedWith(['section' => 'foo']);
        $this->shouldHaveType('Tohmua\RepeatingSegment\Part\LiteralPart');
    }

    public function it_should_throw_exception_if_missing_config()
    {
        $this->shouldThrow('Tohmua\RepeatingSegment\Part\Exception\PartException')->during('__construct', [[]]);
    }

    public function it_should_throw_exception_if_invalid_config()
    {
        $this->shouldThrow('Tohmua\RepeatingSegment\Part\Exception\PartException')->during('__construct', [['section' => []]]);
    }

    public function it_should_return_section()
    {
        $this->beConstructedWith(['section' => 'foo']);
        $this->section()->shouldReturn('foo');
    }

    public function it_should_return_regex_without_slash_for_empty_section()
    {
        $this->beConstructedWith(['section' => '']);
        $this->regex()->shouldReturn('');
    }

    public function it_should_return_regex_with_slash_for_section()
    {
        $this->beConstructedWith(['section' => 'foo']);
        $this->regex()->shouldReturn('/foo');
    }

    public function it_should_return_non_repeating_regex_without_slash_for_empty_section()
    {
        $this->beConstructedWith(['section' => '']);
        $this->nonRepeatingRegex()->shouldReturn('');
    }

    public function it_should_return_non_repeating_regex_with_slash_for_section()
    {
        $this->beConstructedWith(['section' => 'foo']);
        $this->nonRepeatingRegex()->shouldReturn('/foo');
    }
}
