<?php

namespace App\Enums;

enum MasteryLevel: int
{
    case NEW = 0;
    case RUSTY = 1;
    case LEARNING = 2;
    case SOLID = 3;
    case CONFIDENT = 4;
    case MASTERED = 5;

    public function label(): string
    {
        return strtolower($this->name);
    }

    public function threshold(): float
    {
        return match ($this) {
            self::NEW, self::RUSTY => 0.0,
            self::LEARNING => 0.25,
            self::SOLID => 0.50,
            self::CONFIDENT => 0.70,
            self::MASTERED => 0.85,
        };
    }

    public static function fromScore(float $score, bool $isNew): self
    {
        if ($isNew) return self::NEW;

        return match (true) {
            $score >= self::MASTERED->threshold() => self::MASTERED,
            $score >= self::CONFIDENT->threshold() => self::CONFIDENT,
            $score >= self::SOLID->threshold() => self::SOLID,
            $score >= self::LEARNING->threshold() => self::LEARNING,
            default => self::RUSTY,
        };
    }

    public function message(): string
    {
        return match ($this) {
            self::NEW => "this Term is new to you",
            self::RUSTY => "your knowledge of this Term is spotty",
            self::LEARNING => "this Term is familiar to you",
            self::SOLID => "you have a grasp of this Term",
            self::CONFIDENT => "this Term is easy for you",
            self::MASTERED => "you've fully mastered this Term",
        };
    }
}
