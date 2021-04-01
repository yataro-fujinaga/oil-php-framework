<?php
declare(strict_types = 1);

namespace App\Http\Middleware;


use App\Http\Message\Request;

/**
 * Interface Middleware
 */
interface Middleware
{
    public function process(Request $request): void;
}
