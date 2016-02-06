<?php

namespace Tohmua\RepeatingSegment\Part;

use Tohmua\RepeatingSegment\Part\Exception\PartException;

class PartFactory
{
    /**
     * Create a part
     *
     * @param  string $type    fully qualified namespace of the Part
     * @param  array  $options options to give the part constructor
     * @return Tohmua\RepeatingSegment\Part\Part
     */
    public static function create($type, array $options)
    {
        try {
            self::validateType($type);
            self::validateOptions($options);

            return new $type($options);
        } catch (PartException $e) {
            throw $e;
        }
    }

    /**
     * Validate the type
     *
     * @param  string $type fully qualified name space of the part
     * @throws PartException
     */
    private static function validateType($type)
    {
        if (!class_exists($type)) {
            throw new PartException(sprintf('Part %s does not exist', $type));
        }

        if (!in_array('Tohmua\RepeatingSegment\Part\Part', class_implements($type))) {
            throw new PartException(sprintf('Part %s does not implement Forum\Router\Part\Part interface', $type));
        }
    }

    /**
     * Validate the minimum requirement for the options
     *
     * @param  array $options
     * @throws PartException
     */
    private static function validateOptions(array $options)
    {
        if (!isset($options['section'])) {
            throw new PartException('No section supplied in the options array');
        }
    }
}