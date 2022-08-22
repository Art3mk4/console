<?php

/** class importing */
use Art\Console\Input;
use Art\Console\Parameter;
use Art\Console\ParameterSet;
use Art\Console\Tools\CommandLineParser;
use Art\Console\Tools\Help;
use Art\Console\Tools\ResultNormalizer;

/** composer autoloader */
require_once "../vendor/autoload.php";

$normalizer = new ResultNormalizer();
$cmdParser = new CommandLineParser($normalizer);
$helpGenerator = new Help();
$parameterSet = new ParameterSet();
$consoleInput = new Input($parameterSet, $cmdParser, $helpGenerator);

/** Define parameters */
$commandsParameter = Parameter::create('commands');
$argumentsParameter = Parameter::create('arguments');
$logFileParameter = Parameter::create('log_file')
    ->setValueType(Parameter::VALUE_STRING);
$methodsParameter = Parameter::create('methods')
    ->setValueType(Parameter::VALUE_ARRAY);
$paginateParameter = Parameter::create('paginate')
    ->setValueType(Parameter::VALUE_STRING);

/** Inject parameters */
$consoleInput->addParameter($commandsParameter);
$consoleInput->addParameter($argumentsParameter);
$consoleInput->addParameter($logFileParameter);
$consoleInput->addParameter($methodsParameter);
$consoleInput->addParameter($paginateParameter);

/** Read parameters */
$log = $consoleInput->getValue('log_file');
$methods = $consoleInput->getValue('methods');
$paginate = $consoleInput->getValue('paginate');
$arguments = $consoleInput->getValue('arguments');
$commands = $consoleInput->getValue('commands');
echo $helpGenerator->render($parameterSet, $consoleInput);