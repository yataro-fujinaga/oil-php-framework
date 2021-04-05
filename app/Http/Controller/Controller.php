<?php
declare(strict_types = 1);

namespace App\Http\Controller;

use App\Http\Message\Request;
use App\Http\Message\Response;

/**
 * The implementation class must be a single controller.
 *
 * @package App\Http\Controller
 */
interface Controller
{
    /**
     * @param Request $request
     * @param array   $args
     *
     * @return Response
     */
    public function __invoke(Request $request, array $args = []): Response;
}
