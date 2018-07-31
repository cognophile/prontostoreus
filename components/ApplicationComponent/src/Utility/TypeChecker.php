<?php

namespace ApplicationComponent\Utility;

abstract class TypeChecker
{
    public static function isNumeric($value)
    {
        return (ctype_digit($value));
    }
}