<?php

declare(strict_types=1);

namespace Src\Interfaces;

interface IView
{
    public static function view(object $props): void;
    public static function api(object $props): void;
}