<?php

namespace Art\Console;


class Parameter
{

    const VALUE_ARRAY = "array";
    const VALUE_STRING = "string";
    const VALUE_AUTO = "auto";
    const OPTION_HELP = "help";

    /**
     * @var string
     */
    protected $name;

    /**
     * The expected parameter value type:
     * @var string
     */
    protected $valueType = self::VALUE_AUTO;

    /**
     * @var mixed The default value when optional argument missing.
     */
    protected $defaultValue = false;

    /**
     * Static constructor useful for chaining
     *
     * @param $name
     * @return Parameter
     */
    static public function create($name)
    {
        return new self($name);
    }

    /**
     * Parameter constructor.
     * @param string $longName
     * @throws \Exception
     */
    public function __construct($name)
    {
        if (!is_string($name) || !preg_match('#^[a-z_0-9]{2,}$#i', $name) ) {
            throw new \Exception('The "name" parameter must be a non-empty string of at least 2 characters.');
        }

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the expected value type for the parameter
     *
     * @param string $valueType The expected value type:
     * self::RETURN_STRING - Returns a single value for the parameter. Throws Exception if parameter appears twice.
     * self::RETURN_ARRAY - Returns array of values even if parameter appears only once.
     * self::RETURN_AUTO - Returns a string if parameter appears once and an array if parameter appears multiple times.
     *
     * @return Parameter
     * @throws \Exception
     */
    public function setValueType($valueType)
    {
        if (!in_array($valueType, [self::VALUE_ARRAY, self::VALUE_STRING, self::VALUE_AUTO])) {
            throw new \Exception('The return type must be one of [Parameter::VALUE_ARRAY, Parameter::VALUE_STRING, Parameter::VALUE_AUTO]');
        }
        $this->valueType = $valueType;
        return $this;
    }

    /**
     * @return string
     */
    public function getValueType()
    {
        return $this->valueType;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     * @return Parameter
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }
}