<?php


namespace App\Validation;


abstract class Validation
{
    abstract public function validate(array $args): bool;
}