<?php

namespace Tohmua\RepeatingSegment;

use Traversable;
use Zend\Stdlib\ArrayUtils;
use Zend\Mvc\Router\Exception;
use Zend\Mvc\Router\Http\Segment;
use Zend\Mvc\Router\RouteInterface;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\Stdlib\RequestInterface as Request;
use Tohmua\RepeatingSegment\Part\PartFactory;
use Tohmua\RepeatingSegment\PartGenerator\PartGenerator;
use Tohmua\RepeatingSegment\RegexGenerator\RegexGenerator;

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

    /**
     * Parse the rout and build an array of route parts from it
     *
     * @param  string $route
     * @return array         Tohmua\RepeatingSegment\Part\Part
     */
    protected function parseRouteDefinition($route)
    {
        $parts = [];

        foreach ((new PartGenerator($this->constraints))->generatePartsFromRoute($route) as $part) {
            $parts[] = $part;
        }

        return $parts;
    }

    /**
     * Build a regex to match the URI
     *
     * @return string
     */
    protected function regexMatch()
    {
        $regex = '#^';

        foreach ($this->parts as $part) {
            $regex .= $part->regex();
        }

        return $regex .= '$#';
    }

    /**
     * Try to match a request object
     *
     * @param  Request         $request
     * @param  string|null     $pathOffset
     * @param  array           $options
     * @return RouteMatch|null
     */
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

    /**
     * Return the regex that will match all the individual matching parts of the URI
     * from the request object
     *
     * @param  Request $request
     * @return string
     */
    private function matchingRegex(Request $request)
    {
        $regex = '';
        foreach ((new RegexGenerator($request, $this->parts))->regexMatch() as $regexPart) {
            $regex .= $regexPart;
        }
        return $regex;
    }

    /**
     * Loop through the constrains and try and find all the matches for it
     *
     * @param  array  $matches
     * @return array
     */
    private function constraintPartsFromMatches(array $matches)
    {
        $paramaters = [];

        foreach (array_keys($this->constraints) as $constraint) {
            $paramaters[$constraint] = $this->constraintFromMatches($constraint, $matches);
        }

        return $paramaters;
    }

    /**
     * For a given constraint find its matches
     *
     * @param  string $constraint
     * @param  array  $matches
     * @return array
     */
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