<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Settings\SettingsInterface;
use GrotonSchool\Slim\LTI\Handlers\LaunchHandlerInterface;
use Packback\Lti1p3\LtiMessageLaunch;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;

class LaunchHandler implements LaunchHandlerInterface
{
    private SettingsInterface $settings;

    public function __construct(SettingsInterface $settings)
    {
        $this->settings = $settings;
    }

    public function handle(ResponseInterface $response, LtiMessageLaunch $launch): ResponseInterface
    {
        return $response->withAddedHeader(
            'Location',
            'https://' . $this->settings->getBlackbaudInstance() . '.myschoolapp.com/app/faculty#myday/schedule-performance'
        );
    }
}
