<?php

namespace App\Domain\Canvas;

use Battis\LazySecrets\Cache;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\AccessToken\AbstractAccessTokenRepository;
use GrotonSchool\Slim\OAuth2\APIProxy\Domain\Provider\ProviderInterface;
use Slim\Http\ServerRequest;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class SecretsTokenRepository extends AbstractAccessTokenRepository
{
    public function __construct(private ProviderInterface $provider, private Cache $secrets, private string $secretName)
    {
        parent::__construct($this->provider);
    }

    public function getToken(?ServerRequest $request = null): ?AccessToken
    {
        $data = $this->secrets->get($this->secretName)['token'];
        if ($data) {
            $token = new AccessToken($data);
            if ($token && $token->hasExpired()) {
                $token = $this->provider->getAccessToken('refresh_token', ['refresh_token' => $token->getRefreshToken()]);
            }
            return $token;
        }
        return null;
    }

    public function setToken(
        ?AccessToken $token,
        ?ServerRequest $request,
        ResponseInterface $response
    ): ResponseInterface {
        $data = $this->secrets->get($this->secretName);
        if (isset($data['token'])) {
            $data['token'] = $this->merge(new AccessToken($data['token']), $token);
        } else {
            $data['token'] = $token;
        }
        $this->secrets->set($this->secretName, $data);
        return $response;
    }

    public function deleteToken(
        ServerRequest $request,
        ResponseInterface $response
    ): ResponseInterface {
        $data = $this->secrets->get($this->secretName);
        unset($data['token']);
        $this->secrets->set($this->secretName, $data);
        return $response;
    }
}
