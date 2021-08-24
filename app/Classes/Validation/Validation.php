<?php


namespace App\Validation;


abstract class Validation
{
    private $rules;

    abstract public function validate(array $args);
}