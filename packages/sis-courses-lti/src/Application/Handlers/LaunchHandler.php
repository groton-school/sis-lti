<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Settings\SettingsInterface;
use GrotonSchool\Slim\LTI\Handlers\LaunchHandlerInterface;
use Packback\Lti1p3\LtiConstants;
use Packback\Lti1p3\LtiMessageLaunch;
use Psr\Http\Message\ResponseInterface;

class LaunchHandler implements LaunchHandlerInterface
{
    private SettingsInterface $settings;

    public function __construct(SettingsInterface $settings)
    {
        $this->settings = $settings;
    }

    public function handle(ResponseInterface $response, LtiMessageLaunch $launch): ResponseInterface
    {
        $courseId = preg_replace(
            '/cls-\d+-(\d+)/',
            '$1',
            $launch->getLaunchData()[LtiConstants::LIS]['course_offering_sourcedid']
        );
        return $response->withAddedHeader(
            'Location',
            'https://' . $this->settings->getBlackbaudInstance() . ".myschoolapp.com/app/faculty?override=true#academicclass/{$courseId}/0/bulletinboard"
        );
    }
}
