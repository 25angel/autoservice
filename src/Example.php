<?php

namespace App;

/**
 * Пример класса для демонстрации структуры проекта
 */
class Example
{
    /**
     * Приветствие
     *
     * @param string $name Имя
     * @return string
     */
    public function greet(string $name = 'Мир'): string
    {
        return "Привет, {$name}!";
    }
}

