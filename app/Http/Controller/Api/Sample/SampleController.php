<?php
declare(strict_types = 1);

namespace App\Http\Controller\Api\Sample;

use App\Http\Controller\Controller;
use App\Http\Message\Request;
use App\Http\Message\Response;
use Packages\Application\User\Create\SampleUseCaseInterface;

class SampleController implements Controller
{
    public function __construct(private SampleUseCaseInterface $useCase)
    {
    }

    public function __invoke(Request $request, array $args = []): Response
    {
        $content    = ['message' => sprintf('test_id is %s.', $args['test_id'])];
        $statusCode = '200';
        $statusText = 'OK';
        $headers    = [];

        return new Response($content, $statusCode, $statusText, $headers);
    }
}
