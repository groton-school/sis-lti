<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Settings\SettingsInterface;
use App\Domain\Canvas\ApiProxy;
use App\Domain\Canvas\SecretsTokenRepository;
use GrotonSchool\Slim\LTI\Handlers\LaunchHandlerInterface;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;
use Packback\Lti1p3\LtiConstants;
use Packback\Lti1p3\LtiMessageLaunch;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;

class LaunchHandler implements LaunchHandlerInterface
{
    public function __construct(
        private SettingsInterface $settings,
        private PhpRenderer $views,
        private ContainerInterface $container
    ) {
    }

    public function handle(ResponseInterface $response, LtiMessageLaunch $launch): ResponseInterface
    {
        $sectionNames = json_decode($launch->getLaunchData()[LtiConstants::CUSTOM]['section_names']);
        $sectionIds = explode(',', $launch->getLaunchData()[LtiConstants::CUSTOM]['sis_section_ids']);
        if (count($sectionNames) != count($sectionIds)) {
            $sis_course_id = $launch->getLaunchData()[LtiConstants::LIS]['course_offering_sourcedid'];
            /** @var ApiProxy $canvas */
            $canvas = $this->container->get(ProviderInterface::class);
            /** @var SecretsTokenRepository $repository */
            $repository = $canvas->getAccessTokenRepository();
            $token = $repository->getToken();
            $request = $canvas->getAuthenticatedRequest('GET', $canvas->getInstanceUrl() . "/api/v1/courses/sis_course_id:$sis_course_id/sections", $token);
            $sections = json_decode((string) $canvas->getResponse($request)->getBody(), true);
            $sectionNames = array_map(fn ($section) => $section['name'], $sections);
            $sectionIds = array_map(fn ($section) => $section['sis_section_id'], $sections);
        }
        $sections = array_combine(
            $sectionNames,
            array_map(
                fn ($id) => 'https://' . $this->settings->getBlackbaudInstance() . ".myschoolapp.com/app/faculty?override=true#academicclass/{$id}/0/bulletinboard",
                str_replace('cls-542-', '', $sectionIds)
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
