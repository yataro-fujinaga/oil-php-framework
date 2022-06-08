<?php
declare(strict_types = 1);

namespace App\Http\Middleware;


use App\Http\Message\Request;

/**
 * Interface Middleware
 */
interface Middleware
{
    // Middlewareの実行処理
    /**
     * @param Request $request
     */
    public function process(Request $request): void;
}
