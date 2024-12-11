<?php

namespace App\Enums;

enum Timing: string
{
    case Morning = 'Morning';

    case Afternoon = 'Afternoon';

    case Evening = 'Evening';

    public static function getValues() : array
    {
        return array_column(Timing::cases(), 'value');
    }

    public static function getKeyValue() : array
    {
        return array_column(Timing::cases(), 'value','key');
    }
}