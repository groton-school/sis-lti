<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Settings\SettingsInterface;
use GrotonSchool\Slim\LTI\Handlers\LaunchHandlerInterface;
use Packback\Lti1p3\LtiConstants;
use Packback\Lti1p3\LtiMessageLaunch;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;

class LaunchHandler implements LaunchHandlerInterface
{
    public function __construct(
        private SettingsInterface $settings,
        private PhpRenderer $views
    ) {
    }

    public function handle(ResponseInterface $response, LtiMessageLaunch $launch): ResponseInterface
    {
        $sections = array_combine(
            json_decode($launch->getLaunchData()[LtiConstants::CUSTOM]['section_names']),
            array_map(
                fn ($id) => 'https://' . $this->settings->getBlackbaudInstance() . ".myschoolapp.com/app/faculty?override=true#academicclass/{$id}/0/bulletinboard",
                str_replace('cls-542-', '', explode(',', $launch->getLaunchData()[LtiConstants::CUSTOM]['sis_section_ids']))
            )
        );
        if (count($sections) === 1) {
            return $this->views->render($response, 'redirect.php', [
                'redirect' => array_pop($sections)
            ]);
        } else {
            return $this->views->render($response, 'picker.php', [
                'sections' => $sections
            ]);
        }
    }
}
