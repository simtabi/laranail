<?php

if (!function_exists('anchorLink')) {
    function anchorLink(?string $link, ?string $name, array $options = []): ?string
    {
        return Html::link($link, $name, $options);
    }
}

if (!function_exists('getCssActiveClass')) {
    function getCssActiveClass($route_name, $class = 'active'): ?string
    {
        $class = !empty($class) ? $class : ' active ';
        return Route::currentRouteName() === $route_name ? $class : '';
    }
}