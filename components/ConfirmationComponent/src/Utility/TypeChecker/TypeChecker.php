<?php

namespace ConfirmationComponent\Utility\TypeChecker;

abstract class TypeChecker
{
    public static function isNumeric($value)
    {
        return (ctype_digit($value));
    }
}