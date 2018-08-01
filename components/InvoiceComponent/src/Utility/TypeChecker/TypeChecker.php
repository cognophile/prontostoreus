<?php

namespace InvoiceComponent\Utility\TypeChecker;

abstract class TypeChecker
{
    public static function isNumeric($value): bool
    {
        return (ctype_digit($value));
    }
}