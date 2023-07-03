<?php
namespace App\Service\courbeNiveau;

class CourbeXpImpl
{
    public static function getXpMax(string $letter, int $level): float
    {
        switch ($letter) {
            case 'R':
                return self::courbeR($level);
            case 'M':
                return self::courbeM($level);
            case 'P':
                return self::courbeP($level);
            case 'L':
                return self::courbeL($level);
        }
        return 0.0;
    }

    private static function courbeR(int $level): float
    {
        return 0.8 * pow($level, 3);
    }

    private static function courbeM(int $level): float
    {
        return pow($level, 3);
    }

    private static function courbeP(int $level): float
    {
        return 1.2 * pow($level, 3) - 15 * pow($level, 2) + 100 * $level - 140;
    }

    private static function courbeL(int $level): float
    {
        return 1.25 * pow($level, 3);
    }
}
