<?php

namespace Tohmua\RepeatingSegment;

use Tohmua\RepeatingSegment\PartGenerator\PartGenerator;
use Tohmua\RepeatingSegment\Part\PartFactory;
use Tohmua\RepeatingSegment\RegexGenerator\RegexGenerator;

use Traversable;
use Zend\Mvc\Router\Exception;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\Mvc\Router\Http\Segment;
use Zend\Mvc\Router\RouteInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\RequestInterface as Request;

class RepeatingSegment extends Segment implements RouteInterface
{
    /**
     * Create a new regex route.
     *
     * @param  string $route
     * @param  array  $constraints
     * @param  array  $defaults
     */
    public function __construct($route, array $constraints = array(), array $defaults = array())
    {
        $this->defaults    = $defaults;
        $this->constraints = $constraints;
        $this->parts       = $this->parseRouteDefinition($route);
        $this->regex       = $this->regexMatch();
    }

    protected function parseRouteDefinition($route)
    {
        $parts = [];

        foreach ((new PartGenerator($this->constraints))->generatePartsFromRoute($route) as $part) {
            $parts[] = $part;
        }

        return $parts;
    }

    protected function regexMatch()
    {
        $regex = '#^';

        foreach ($this->parts as $part) {
            $regex .= $part->regex();
        }

        return $regex .= '$#';
    }

    public function match(Request $request, $pathOffset = null, array $options = array())
    {
        if (!method_exists($request, 'getUri')) {
            return;
        }

        $uri  = $request->getUri();
        $path = $uri->getPath();

        if (!preg_match($this->regex, $path)) {
            return;
        }

        preg_match($this->matchingRegex($request), $path, $matches);
        $paramaters = $this->constraintPartsFromMatches($matches);

        return new RouteMatch(array_merge($this->defaults, $paramaters));
    }

    private function matchingRegex(Request $request)
    {
        $regex = '';
        foreach ((new RegexGenerator($request, $this->parts))->regexMatch() as $regexPart) {
            $regex .= $regexPart;
        }
        return $regex;
    }

    private function constraintPartsFromMatches(array $matches)
    {
        $paramaters = [];

        foreach (array_keys($this->constraints) as $constraint) {
            $paramaters[$constraint] = $this->constraintFromMatches($constraint, $matches);
        }

        return $paramaters;
    }

    private function constraintFromMatches($constraint, array $matches)
    {
        $values = [];

        foreach ($matches as $key => $value) {
            if (substr($key, 0, strlen($constraint)) == $constraint) {
                $values[] = $value;
            }
        }

        return $values;
    }
}