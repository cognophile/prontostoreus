<?php

namespace Prontostoreus\Api\Utility;

abstract class TypeChecker
{
    public static function isNumeric($value)
    {
        return (ctype_digit($value));
    }
}