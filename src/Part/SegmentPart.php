<?php

namespace Tohmua\RepeatingSegment\Part;

use Tohmua\RepeatingSegment\Part\Part;
use Tohmua\RepeatingSegment\Part\Exception\PartException;

class SegmentPart implements Part
{
    private $section    = '';
    private $constraint = '';

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (!isset($options['section']) || !is_string($options['section'])) {
            throw new PartException('Missing "section" from the options supplied to SegmentPart');
        }

        if (!isset($options['constraint']) || !is_string($options['constraint'])) {
            throw new PartException('SegmentPart needs a constraint');
        }

        $this->section    = $options['section'];
        $this->constraint = $options['constraint'];
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
     * Get the constraints for the variable segment as a regex
     *
     * @return string
     */
    public function constraint()
    {
        return $this->constraint;
    }

    /**
     * The regex that matches the literal part
     *
     * @return string
     */
    public function regex()
    {
        if (empty($this->section())) {
            return '';
        }

        return '(' . $this->constraint() . ')+';
    }

    /**
     * Get the regex add a variable name so this can be pulled out latter. Part of the name is
     * a randomly generated string so if there is more than one segment in a path the names wont
     * conflict when parsing the regex
     *
     * @return string
     */
    public function nonRepeatingRegex()
    {
        if (empty($this->section())) {
            return '';
        }

        return '(?P<' . $this->section() . '_' . uniqid() . '>' . $this->constraint() . ')';
    }
}