<?php

namespace spec\Tohmua\RepeatingSegment\PartGenerator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PartGeneratorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->beConstructedWith([]);
        $this->shouldHaveType('Tohmua\RepeatingSegment\PartGenerator\PartGenerator');
    }
}
