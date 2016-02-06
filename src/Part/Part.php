<?php

namespace Tohmua\RepeatingSegment\Part;

interface Part
{
    /**
     * @param array $options
     */
    public function __construct(array $options);

    /**
     * Name of the section of this part
     *
     * @return string
     */
    public function section();

    /**
     * Get the regex that matches this part. If the part is longer than a single string it
     * must match the whole string e.g.
     *
     * /foo         should be /[a-z]+
     * /foo/bar/bas should be (/[a-z]+)+
     *
     * @return string
     */
    public function regex();

    /**
     * Get the regex that matches this part. If the part is longer than a single string it
     * must only match a single element
     *
     * /foo         should be /[a-z]+
     * /foo/bar/bas should be (/[a-z]+)
     *
     * @return string
     */
    public function nonRepeatingRegex();
}