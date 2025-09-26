<?php

declare(strict_types=1);

use App\Application\Handlers\LaunchHandler;
use App\Application\Settings\SettingsInterface;
use App\Domain\Canvas\ApiProxy;
use App\Domain\Canvas\SecretsTokenRepository;
use Battis\LazySecrets\Cache;
use DI\ContainerBuilder;
use GrotonSchool\Slim\GAE;
use GrotonSchool\Slim\LTI;
use GrotonSchool\Slim\LTI\Actions\RegistrationConfigureActionInterface;
use GrotonSchool\Slim\LTI\Actions\RegistrationConfigurePassthruAction;
use GrotonSchool\Slim\LTI\Handlers\LaunchHandlerInterface;
use GrotonSchool\Slim\LTI\Infrastructure;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;
use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;
use Odan\Session\SessionManagerInterface;
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

        PhpRenderer::class => fn () => new PhpRenderer(__DIR__ . '/../views'),

        ProviderInterface::class => function () {
            $secrets = new Cache();
            $secretName = 'CANVAS_CREDENTIALS';
            $credentials = $secrets->get($secretName);
            $proxy = new ApiProxy($credentials['config']);
            $proxy->setAccessTokenRepostory(new SecretsTokenRepository($proxy, $secrets, $secretName));
            return $proxy;
        },

        SessionManagerInterface::class => DI\get(SessionInterface::class),
        SessionInterface::class => DI\autowire(PhpSession::class)
    ]);
};
