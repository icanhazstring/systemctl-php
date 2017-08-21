<?php

namespace SystemCtl\Template\Section;

use SystemCtl\Exception\PropertyNotSupportedException;

abstract class AbstractSection
{
    /** @var array */
    protected $properties = [];

    protected const PROPERTIES = [];

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @throws PropertyNotSupportedException
     * @return UnitSection|mixed
     */
    public function __call($name, $arguments)
    {
        preg_match('/(?<type>get|set)(?<property>.*)/', $name, $match);

        if (!in_array($match['property'], static::PROPERTIES)) {
            throw new PropertyNotSupportedException($match['property'], static::class);
        }

        if ($match['type'] === 'set') {
            $this->properties[$match['property']] = $arguments[0];

            return $this;
        }

        return $this->properties[$match['property']] ?? null;
    }
}
