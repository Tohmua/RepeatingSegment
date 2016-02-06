<?php

namespace spec\Tohmua\RepeatingSegment\RegexGenerator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class RegexGeneratorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $prophet = new Prophet();
        $httpProphecy = $prophet->prophesize('Zend\Uri\Http');
        $httpProphecy->getPath()->willReturn('/b');

        $requestProphecy = $prophet->prophesize('Zend\Http\PhpEnvironment\Request');
        $requestProphecy->getUri()->willReturn($httpProphecy->reveal());

        $partProphecy = $prophet->prophesize('Tohmua\RepeatingSegment\Part\Part');

        $parts = [
            $partProphecy->reveal(),
            $partProphecy->reveal(),
            $partProphecy->reveal(),
        ];

        $this->beConstructedWith($requestProphecy, $parts);
        $this->shouldHaveType('Tohmua\RepeatingSegment\RegexGenerator\RegexGenerator');
    }
}
