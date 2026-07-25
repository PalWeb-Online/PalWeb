<?php

namespace App\Enums;

enum MasteryLevel: int
{
    case NEW = 0;
    case LOWEST = 1;
    case LOW = 2;
    case MEDIUM = 3;
    case HIGH = 4;
    case HIGHEST = 5;

    public function label(): string
    {
        return strtolower($this->name);
    }

    public function threshold(): float
    {
        return match ($this) {
            self::NEW, self::LOWEST => 0.0,
            self::LOW => 0.25,
            self::MEDIUM => 0.50,
            self::HIGH => 0.70,
            self::HIGHEST => 0.85,
        };
    }

    public static function fromScore(float $score, bool $isNew): self
    {
        if ($isNew) return self::NEW;

        return match (true) {
            $score >= self::HIGHEST->threshold() => self::HIGHEST,
            $score >= self::HIGH->threshold() => self::HIGH,
            $score >= self::MEDIUM->threshold() => self::MEDIUM,
            $score >= self::LOW->threshold() => self::LOW,
            default => self::LOWEST,
        };
    }
}
