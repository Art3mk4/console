<?php

namespace Art\Console\Tools;
use Art\Console\Parameter;
use Art\Console\ParameterSet;

class CommandLineParser
{

    protected $params;

    public function __construct($commandParams)
    {
        $this->params = $commandParams;
    }

    /**
     * @param ParameterSet $parameters
     * @return array
     */
    public function parse($parameters)
    {
        $longOptions = [];
        $shortOptions = "";

        foreach ($parameters as $parameter) {
            $longOptions[] = $this->getOption($parameter);
        }

        $rawValues = $this->getOptions();
        if ($rawValues === false) {
            throw new \Exception("You have managed to jam the console component. What input did you use?");
        }

        if (isset($rawValues[Parameter::OPTION_HELP])) {
            throw new \Exception("Asked for help page...");
        }

        $values = $this->params->normalize($parameters, $rawValues);
        return $values;
    }


    protected function getOption(Parameter $parameter)
    {
        $parameterName = $parameter->getName();
        return $parameterName;
    }

    private function getOptions() {
        $rawValues = array(
            'commands'  => array(),
            'arguments' => array(),
            'options'   => array(),
            'filled'    => false
        );
        $argv = $_SERVER['argv'];
        array_shift($argv);
        $rawValues['commands'] = array(array_shift($argv));
        foreach ($argv as $arg) {
            if (strpos($arg, ']')) {
                $value = $this->parseValues(trim($arg, '[]'));
                if (is_array($value)) {
                  $rawValues['options'][$value['key']][] = $value['name'];
                } else {
                    $rawValues['options'][] = $value;
                }
            } else {
               $rawValues['arguments'][] = trim($arg, '{}');
               $rawValues['filled'] = true;
            }
        }

        if ($rawValues['filled'] === false) {
            throw new \Exception("You have managed to jam the console component. What input did you use?");
        }

        if (isset($rawValues[Parameter::OPTION_HELP])) {
            throw new \Exception("Asked for help page...");
        }

        return $rawValues;
    }

    private function parseValues($value) {
        if (strpos($value, '=') !== 0) {
            $temp = explode('=', $value);
            if (count($temp) > 1) {
                $this->rawValues['filled'] = true;
                return array(
                    'key'  => $temp[0],
                    'name' => $temp[1]
                );
            }
        }

        return $value;
    }
}