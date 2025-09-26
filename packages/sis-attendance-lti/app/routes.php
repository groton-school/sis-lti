<?php

declare(strict_types=1);

use GrotonSchool\Slim\GAE;
use GrotonSchool\Slim\LTI;
use Slim\App;

return function (App $app) {
    (new GAE\RouteBuilder())->define($app);
    (new LTI\RouteBuilder())->define($app);
};
