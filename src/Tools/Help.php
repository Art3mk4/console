<?php

namespace Art\Console\Tools;

use Art\Console\Input;
use Art\Console\ParameterSet;

/**
 * This class generates the help message of the application
 *
 * @package Art\Console
 */
class Help
{
    public function render(ParameterSet $parameters, Input $consoleInput, $errorMessage = "")
    {
        $return = "";
        $return .= $errorMessage . PHP_EOL;
        $return .= "Called command: " . $consoleInput->getValue('commands') . PHP_EOL;
        $return .= "" . PHP_EOL;


        if (count($consoleInput->getValue('arguments')) > 0) {
            $return .= "Arguments: " . PHP_EOL;
        }

        foreach ($consoleInput->getValue('arguments') as $argInput) {
            $text = (string)$argInput;
            if ($text) {
                $text = " - " . $text . "";
            }
            $return .= sprintf("% -s ", $text) . PHP_EOL;
        }

        $methods = $consoleInput->getValue('methods');
        if (!is_array($methods)) {
            return false;
        }

        if (count($consoleInput->getValue('methods')) > 0) {
            $return .= "Options: " . PHP_EOL;
        }
        $return .= " - log_file" . PHP_EOL;
        $return .= "    - " . (string)$consoleInput->getValue('log_file') . PHP_EOL;

        $return .= " - methods" . PHP_EOL;
        foreach ($consoleInput->getValue('methods') as $methodInput) {
            $text = (string)$methodInput;
            if ($text) {
                $text = "    - " . $text;
            }
            $return .= sprintf("% -s ", $text) . PHP_EOL;
        }

        $return .= " - paginate" . PHP_EOL;
        $return .= "    - " . (string)$consoleInput->getValue('paginate') . PHP_EOL;

        return $return;
    }
}