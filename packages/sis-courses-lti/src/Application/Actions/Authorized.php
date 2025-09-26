<?php

namespace App\Application\Actions;

use GrotonSchool\Slim\Norms\AbstractAction;
use Slim\Http\ServerRequest;
use Slim\Http\Response;
use Psr\Http\Message\ResponseInterface;

class Authorized extends AbstractAction
{
    protected function invokeHook(
        ServerRequest $request,
        Response $response,
        array $args = []
    ): ResponseInterface {
        $response->getBody()->write("Authorized");
        return $response;
    }
}
