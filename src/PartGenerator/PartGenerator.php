<?php

namespace Tohmua\RepeatingSegment\PartGenerator;

use Tohmua\RepeatingSegment\Part\PartFactory;

class PartGenerator
{
    private $type        = 'Forum\Router\Part\LiteralPart';
    private $options     = ['section' => ''];
    private $deviders    = ['[', ']', '/'];
    private $constraints = [];

    /**
     * @param array $constraints
     */
    public function __construct(array $constraints)
    {
        $this->constraints = $constraints;
    }

    /**
     * For a given route split it up into its parts
     *
     * @param  string $route
     * @return Tohmua\RepeatingSegment\Part\Part
     */
    public function generatePartsFromRoute($route)
    {
        foreach (range(0, strlen($route)-1) as $position) {
            if (in_array($route[$position], $this->deviders)) {
                if (!empty($this->options['section'])) {
                    yield PartFactory::create($this->type, $this->options());
                }
                $this->updateParts($route[$position]);
            } else {
                $this->options['section'] .= $route[$position];
            }
        }

        yield PartFactory::create($this->type, $this->options, true);
    }

    /**
     * After returning a part, reset the information about a part to the next
     * section to be returned
     *
     * @param  string $currentCharictor
     */
    private function updateParts($currentCharictor)
    {
        $this->setType($currentCharictor);
        $this->resetSection();
    }

    /**
     * Set the type of the next part to be returned
     */
    private function setType($currentCharictor)
    {
        switch($currentCharictor) {
            case '[':
                $this->type = 'Tohmua\RepeatingSegment\Part\SegmentPart';
                break;
            case ']':
            case '/':
            default:
                $this->type = 'Tohmua\RepeatingSegment\Part\LiteralPart';
                break;
        }
    }

    /**
     * Get the options to be passed into the PartFactory
     *
     * @return array $options
     */
    private function options()
    {
        $constraints = [];

        if ($this->isSegment() && isset($this->constraints[$this->options['section']])) {
            $constraints = ['constraint' => $this->constraints[$this->options['section']]];
        }

        return array_merge($this->options, $constraints);
    }

    /**
     * Resets the contents of the 'section' to a blank string
     */
    private function resetSection()
    {
        $this->options['section'] = '';
    }

    /**
     * Is the current type a SegmentPart
     *
     * @return boolean
     */
    private function isSegment()
    {
        return $this->type == 'Tohmua\RepeatingSegment\Part\SegmentPart';
    }
}