<?php

if (! function_exists('getColorTipo')) {
    function getColorTipo($codigo)
    {
        return match ($codigo) {
            'A' => '#4CAF50',
            'T' => '#FFC107',
            'F' => '#F44336',
            'J' => '#2196F3',
            default => '#9E9E9E',
        };
    }
}
