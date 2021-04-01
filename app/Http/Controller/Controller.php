<?php
declare(strict_types = 1);

namespace App\Http\Controller;

use App\Http\Message\Request;
use App\Http\Message\Response;

interface Controller
{
    public function __invoke(Request $request, array $args = []): Response;
}