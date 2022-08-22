<?php

namespace Art\Console;

use Art\Console\Tools\CommandLineParser;
use Art\Console\Tools\Help;

class Input
{
    /**
     * @var parameters
     */
    protected $parameters;

    /**
     * @var parser
     */
    protected $parser;

    /**
     * @var bool
     */
    protected $parsed = false;

    /**
     * @var parsedParameters
     */
    protected $parsedParameters = [];

    /**
     * @var help
     */
    protected $help;

    /**
     * Input constructor.
     * @param ParameterSet      $parameters
     * @param CommandLineParser $parser
     * @param Help              $help
     */
    public function __construct(ParameterSet $parameters, CommandLineParser $parser, Help $help)
    {
        $this->parameters = $parameters;
        $this->parser = $parser;
        $this->help = $help;

        $this->setDefaultParameters();
    }

    private function setDefaultParameters()
    {
        $helpParameter = new Parameter(Parameter::OPTION_HELP);
        $this->parameters->addParameter($helpParameter);
    }

    public function addParameter(Parameter $parameter)
    {
        $this->parsed = false;
        $this->parameters->addParameter($parameter);
    }

    public function getValue($parameterName)
    {
        if (!is_string($parameterName)) {
            throw new \Exception("Parameter name must be a string");
        }

        $this->parse();

        $parameter = $this->parameters->getByEitherName($parameterName);
        if (!isset($this->parsedParameters[$parameterName])) {
            return $parameter->getDefaultValue();
        }
        return $this->parsedParameters[$parameterName];
    }

    /**
     * @return bool
     */
    private function parse()
    {
        if ($this->parsed) {
            return true;
        }

        try {
            $this->parsedParameters = $this->parser->parse($this->parameters);
        } catch (\Exception $e) {
            $help = $this->help->render($this->parameters, $e->getMessage());
            die($help);
        }

        $this->parsed = true;
        return true;
    }
}