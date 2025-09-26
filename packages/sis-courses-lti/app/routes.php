<?php

declare(strict_types=1);

use App\Application\Actions\Authorized;
use GrotonSchool\Slim\GAE;
use GrotonSchool\Slim\LTI;
use GrotonSchool\Slim\OAuth2\APIProxy\Actions\AuthorizeAction;
use GrotonSchool\Slim\OAuth2\APIProxy\Actions\RedirectAction;
use Odan\Session\Middleware\SessionStartMiddleware;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;

return function (App $app) {
    (new GAE\RouteBuilder())->define($app);
    (new LTI\RouteBuilder())->define($app);

    $app->group('/canvas', function (RouteCollectorProxyInterface $canvas) {
        $canvas->group('/login', function (RouteCollectorProxyInterface $login) {
            $login->get('/oauth2', AuthorizeAction::class);
            $login->get('/redirect', RedirectAction::class);
        });
        $canvas->get('/authorized', Authorized::class);
    })->add(SessionStartMiddleware::class);
};
