<?php
declare(strict_types = 1);

namespace App\Http\Controller\Api\Sample;

use App\Http\Controller\Controller;
use App\Http\Message\Request;
use App\Http\Message\Response;


class SampleController implements Controller
{
    public function __invoke(Request $request, array $args = []): Response
    {
        $content    = ['message' => 'I am sample response.'];
        $statusCode = '200';
        $statusText = 'OK';
        $headers    = [];

        return new Response($content, $statusCode, $statusText, $headers);
    }
}
