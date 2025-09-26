<?php

namespace App\Domain\Canvas;

use GrotonSchool\OAuth2\Client\Provider\CanvasLMS;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\Defaults;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;

class ApiProxy extends CanvasLMS implements ProviderInterface
{
    use Defaults\AccessTokenRepository;
    use Defaults\Headers;

    public function getSlug(): string
    {
        return 'canvas';
    }

    public function getAuthorizedRedirect(): string
    {
        return '/canvas/authorized';
    }

    public function getBaseApiUrl(): string
    {
        return $this->canvasInstanceUrl;
    }

    public function getInstanceUrl()
    {
        return $this->canvasInstanceUrl;
    }
}
