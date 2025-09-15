<?php

declare(strict_types=1);

use App\Application\Handlers\LaunchHandler;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use GrotonSchool\Slim\GAE;
use GrotonSchool\Slim\LTI;
use GrotonSchool\Slim\LTI\Actions\RegistrationConfigureActionInterface;
use GrotonSchool\Slim\LTI\Actions\RegistrationConfigurePassthruAction;
use GrotonSchool\Slim\LTI\Handlers\LaunchHandlerInterface;
use GrotonSchool\Slim\LTI\Infrastructure;
use Slim\Views\PhpRenderer;

return function (ContainerBuilder $containerBuilder) {
    (new GAE\Dependencies())->inject($containerBuilder);
    (new LTI\Dependencies())->inject($containerBuilder);
    (new Infrastructure\GAE\Dependencies())->inject($containerBuilder);

    $containerBuilder->addDefinitions([
        // all settings interfaces map to the App Settings
        GAE\SettingsInterface::class => DI\get(SettingsInterface::class),
        LTI\SettingsInterface::class => DI\get(SettingsInterface::class),
        Infrastructure\GAE\SettingsInterface::class => DI\get(SettingsInterface::class),

        LaunchHandlerInterface::class => DI\autowire(LaunchHandler::class),

        RegistrationConfigureActionInterface::class => DI\autowire(RegistrationConfigurePassthruAction::class),

        PhpRenderer::class => fn () => new PhpRenderer(__DIR__ . '/../views')
    ]);
};
