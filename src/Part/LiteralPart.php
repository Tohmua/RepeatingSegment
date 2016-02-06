<?php

namespace Tohmua\RepeatingSegment\Part;

use Tohmua\RepeatingSegment\Part\Part;
use Tohmua\RepeatingSegment\Part\Exception\PartException;

class LiteralPart implements Part
{
    private $section = '';

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (!isset($options['section']) || !is_string($options['section'])) {
            throw new PartException('Missing "section" from the options supplied to LiteralPart');
        }

        $this->section = $options['section'];
    }

    /**
     * Name of the section of this part
     *
     * @return string
     */
    public function section()
    {
        return $this->section;
    }

    /**
     * The regex that matches the literal part
     *
     * @return string
     */
    public function regex()
    {
        if (!empty($this->section())) {
            return '/' . $this->section();
        }

        return $this->section();
    }

    /**
     * For the literal part this is the same as the regex() function
     *
     * @return string
     */
    public function nonRepeatingRegex()
    {
        return $this->regex();
    }
}