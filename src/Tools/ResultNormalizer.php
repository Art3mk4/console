<?php

namespace Art\Console\Tools;

use Art\Console\Parameter;
use Art\Console\ParameterSet;

class ResultNormalizer
{
    /**
     * $parameters
     * $rawValues
     */
    public function normalize(ParameterSet $parameters, $rawValues)
    {
        $results = [];

        foreach ($parameters as $parameter) {
            $results[$parameter->getName()] = $this->normalizeParameter($parameter, $rawValues);
        }

        return $results;
    }

    /**
     * @param $rawValues
     * @param $parameter
     * @return array
     */
    private function getValuesForName(Parameter $parameter, $rawValues)
    {
        $name = $parameter->getName();
        $value = $this->findKey($name, $rawValues); 
        if (!$value) {
            return [];
        }

        return $value;
    }

    /**
     * @param $key
     * @param $array
     */
    private function findKey($searchKey, $array)
    {
        foreach($array as $key => $value) {
            if ($key == $searchKey) return $value;
            if (is_array($value)) {
                if (($result = $this->findKey($searchKey, $value)) !== false)
                   return $result;
            }
        }

        return false;
    }

    /**
     * @param $parameter
     * @param $rawValues
     */
    private function normalizeParameter(Parameter $parameter, $rawValues)
    {
        $values = $this->getValuesForName($parameter, $rawValues);
        $numberOfValues = count($values);

        if (!$numberOfValues) {
            return $parameter->getDefaultValue();
        }

        $valueType = $parameter->getValueType();
        if ($valueType == Parameter::VALUE_STRING && $numberOfValues != 1) {
            throw new \Exception("The \"--" . $parameter->getName() . "\" parameter can only be used once since the expected value must be a string.");
        }

        if ($numberOfValues == 1 && $valueType !== Parameter::VALUE_ARRAY) {
            return $values[0];
        }

        return $values;
    }
}