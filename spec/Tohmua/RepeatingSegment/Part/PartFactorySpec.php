<?php

namespace spec\Tohmua\RepeatingSegment\Part;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PartFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Tohmua\RepeatingSegment\Part\PartFactory');
    }

    public function it_should_throw_exception_with_invalid_type()
    {
        $this->shouldThrow('Tohmua\RepeatingSegment\Part\Exception\PartException')->during('create', ['foo\bar\bass', []]);
    }

    public function it_should_throw_exception_with_invalid_options()
    {
        $this->shouldThrow('Tohmua\RepeatingSegment\Part\Exception\PartException')->during('create', ['Tohmua\RepeatingSegment\Part\LiteralPart', []]);
    }

    public function it_creates_literal_part()
    {
        $this::create('Tohmua\RepeatingSegment\Part\LiteralPart', ['section' => 'foo'])->shouldHaveType('Tohmua\RepeatingSegment\Part\Part');
    }

    public function it_creates_segment_part()
    {
        $this::create('Tohmua\RepeatingSegment\Part\SegmentPart', ['section' => 'foo', 'constraint' => 'bar'])->shouldHaveType('Tohmua\RepeatingSegment\Part\Part');
    }
}
