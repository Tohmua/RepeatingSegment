<?php

namespace Tohmua\RepeatingSegment\RegexGenerator;

use Tohmua\RepeatingSegment\Part\Part;
use Zend\Stdlib\RequestInterface as Request;

class RegexGenerator
{
    private $path     = '';
    private $parts    = [];
    private $regex    = '#^';
    private $deviders = ['\[', '\]', '/'];

    /**
     * @param Request $request
     * @param array   $parts   contains Tohmua\RepeatingSegment\Part\Part
     */
    public function __construct(Request $request, array $parts)
    {
        $this->path = $request->getUri()->getPath();
        array_map(array($this, 'addPart'), $parts);
    }

    /**
     * Add parts to the parts array
     *
     * @param Part $part
     */
    private function addPart(Part $part)
    {
        $this->parts[] = $part;
    }

    /**
     * Returns parts of the regex the matches the uri from the Request object
     *
     * @return string
     */
    public function regexMatch()
    {
        yield '#^';
        for ($partId = 0; $partId < count($this->parts); $partId++) {
            while (preg_match($this->currentRegex($partId), $this->path) == false) {
                $previousPart = $partId-1;
                if (!$this->isSegment($previousPart)) {
                    break;
                }
                $this->updateRegex($previousPart);
                yield $this->regex($previousPart);
            }
            $this->updateRegex($partId);
            yield $this->regex($partId);
        }
        yield '$#';
    }

    /**
     * Builds the regex to match the current part in the array
     *
     * @param  int    $partId
     * @return string
     */
    private function currentRegex($partId)
    {
        $regex = $this->regex . $this->regex($partId);

        $regex .= $this->lastPart($partId) ? '$' : '[' . implode($this->deviders) . ']';

        return  $regex .= '#';
    }

    /**
     * Is the current part the last part
     *
     * @param  int     $partId
     * @return boolean
     */
    private function lastPart($partId)
    {
        return count($this->parts)-1 == $partId;
    }

    /**
     * Is the part of the Id given a Segment Part
     *
     * @param  int     $partId
     * @return boolean
     */
    private function isSegment($partId)
    {
        return is_a($this->parts[$partId], 'Tohmua\RepeatingSegment\Part\SegmentPart');
    }

    /**
     * Adds to the main regex the current part
     *
     * @param  int    $partId
     */
    private function updateRegex($partId)
    {
        $this->regex .= $this->regex($partId);
    }

    /**
     * Gets the non-repeating regex for a given part id
     *
     * @param  int    $partId
     * @return string
     */
    private function regex($partId)
    {
        return $this->parts[$partId]->nonRepeatingRegex();
    }
}