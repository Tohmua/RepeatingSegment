<?php

namespace spec\Tohmua\RepeatingSegment;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class RepeatingSegmentSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->beConstructedWith(
            '/a[section]/b',
            ['section' => '/[a-zA-Z][a-zA-Z0-9_-]+'],
            ['controller' => 'Controller', 'action' => 'create']
        );
        $this->shouldHaveType('Tohmua\RepeatingSegment\RepeatingSegment');
    }

    public function it_cant_match_basic_regex()
    {
        $prophet = new Prophet();
        $httpProphecy = $prophet->prophesize('Zend\Uri\Http');
        $httpProphecy->getPath()->willReturn('/b');

        $requestProphecy = $prophet->prophesize('Zend\Http\PhpEnvironment\Request');
        $requestProphecy->getUri()->willReturn($httpProphecy->reveal());

        $this->beConstructedWith(
            '/a[section]/b',
            ['section' => '/[a-zA-Z][a-zA-Z0-9_-]+'],
            ['controller' => 'Controller', 'action' => 'create']
        );

        $this->match($requestProphecy->reveal())->shouldReturn(null);
    }

    public function it_can_match_single_segment()
    {
        $prophet = new Prophet();
        $httpProphecy = $prophet->prophesize('Zend\Uri\Http');
        $httpProphecy->getPath()->willReturn('/a/foo/b');

        $requestProphecy = $prophet->prophesize('Zend\Http\PhpEnvironment\Request');
        $requestProphecy->getUri()->willReturn($httpProphecy->reveal());

        $this->beConstructedWith(
            '/a[section]/b',
            ['section' => '/[a-zA-Z][a-zA-Z0-9_-]+'],
            ['controller' => 'Controller', 'action' => 'create']
        );

        $this->match($requestProphecy->reveal())->shouldReturnAnInstanceOf('Zend\Mvc\Router\Http\RouteMatch');
    }

    public function it_can_match_repeating_single_segments()
    {
        $prophet = new Prophet();
        $httpProphecy = $prophet->prophesize('Zend\Uri\Http');
        $httpProphecy->getPath()->willReturn('/a/foo/bar/bass/b');

        $requestProphecy = $prophet->prophesize('Zend\Http\PhpEnvironment\Request');
        $requestProphecy->getUri()->willReturn($httpProphecy->reveal());

        $this->beConstructedWith(
            '/a[section]/b',
            ['section' => '/[a-zA-Z][a-zA-Z0-9_-]+'],
            ['controller' => 'Controller', 'action' => 'create']
        );

        $this->match($requestProphecy->reveal())->shouldReturnAnInstanceOf('Zend\Mvc\Router\Http\RouteMatch');
    }

    public function it_can_match_multiple_segments()
    {
        $prophet = new Prophet();
        $httpProphecy = $prophet->prophesize('Zend\Uri\Http');
        $httpProphecy->getPath()->willReturn('/a/foo/b/c/bar');

        $requestProphecy = $prophet->prophesize('Zend\Http\PhpEnvironment\Request');
        $requestProphecy->getUri()->willReturn($httpProphecy->reveal());

        $this->beConstructedWith(
            '/a[section]/b/c[other_section]',
            ['section' => '/[a-zA-Z][a-zA-Z0-9_-]+', 'other_section' => '/[a-zA-Z][a-zA-Z0-9_-]+'],
            ['controller' => 'Controller', 'action' => 'create']
        );

        $this->match($requestProphecy->reveal())->shouldReturnAnInstanceOf('Zend\Mvc\Router\Http\RouteMatch');
    }

    public function it_can_match_repeating_multiple_segments()
    {
        $prophet = new Prophet();
        $httpProphecy = $prophet->prophesize('Zend\Uri\Http');
        $httpProphecy->getPath()->willReturn('/a/foo/bar/b/c/bar/bass/d');

        $requestProphecy = $prophet->prophesize('Zend\Http\PhpEnvironment\Request');
        $requestProphecy->getUri()->willReturn($httpProphecy->reveal());

        $this->beConstructedWith(
            '/a[section]/b/c[other_section]/d',
            ['section' => '/[a-zA-Z][a-zA-Z0-9_-]+', 'other_section' => '/[a-zA-Z][a-zA-Z0-9_-]+'],
            ['controller' => 'Controller', 'action' => 'create']
        );

        $this->match($requestProphecy->reveal())->shouldReturnAnInstanceOf('Zend\Mvc\Router\Http\RouteMatch');
    }
}
