<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Business\Reader;

use Generated\Shared\Transfer\OauthKnpuProviderConfigTransfer;
use Generated\Shared\Transfer\ResourceOwnerRequestTransfer;
use Generated\Shared\Transfer\ResourceOwnerResponseTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig;

class OauthMerchantPortalResourceOwnerReader implements OauthMerchantPortalResourceOwnerReaderInterface
{
    use LoggerTrait;

    protected const string CLAIM_EMAIL = 'email';

    protected const string CLAIM_MAIL = 'mail';

    protected const string CLAIM_PREFERRED_USERNAME = 'preferred_username';

    protected const string OPTION_CODE = 'code';

    public function __construct(
        protected ClientRegistry $clientRegistry,
        protected SecurityOauthKnpuConfig $securityOauthKnpuConfig,
    ) {
    }

    public function isApplicable(ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer): bool
    {
        return $this->findProviderConfigByState($resourceOwnerRequestTransfer->getState()) !== null;
    }

    public function getResourceOwner(ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer): ResourceOwnerResponseTransfer
    {
        $resourceOwnerResponseTransfer = new ResourceOwnerResponseTransfer();

        if ($resourceOwnerRequestTransfer->getCode() === null) {
            $this->getLogger()->warning(
                'OAuth callback received without authorization code.',
                ['state' => $resourceOwnerRequestTransfer->getState()],
            );

            return $resourceOwnerResponseTransfer->setIsSuccessful(false);
        }

        $oauthKnpuProviderConfigTransfer = $this->findProviderConfigByState($resourceOwnerRequestTransfer->getState());

        if ($oauthKnpuProviderConfigTransfer === null) {
            $this->getLogger()->warning(
                'OAuth callback state does not match any configured provider.',
                ['state' => $resourceOwnerRequestTransfer->getState()],
            );

            return $resourceOwnerResponseTransfer->setIsSuccessful(false);
        }

        $oauthClient = $this->getOauthClientByConfig($oauthKnpuProviderConfigTransfer);
        $options = [static::OPTION_CODE => $resourceOwnerRequestTransfer->getCode()];

        try {
            $accessToken = $oauthClient->getAccessToken($options);
            $resourceOwner = $oauthClient->fetchUserFromToken($accessToken);
        } catch (IdentityProviderException $exception) {
            $this->getLogger()->warning(
                sprintf('OAuth token exchange failed for client "%s".', $oauthKnpuProviderConfigTransfer->getClientNameOrFail()),
                ['exception' => $exception->getMessage()],
            );

            return $resourceOwnerResponseTransfer->setIsSuccessful(false);
        }

        return $resourceOwnerResponseTransfer
            ->setResourceOwner($this->mapResourceOwnerToResourceOwnerTransfer($resourceOwner, $oauthKnpuProviderConfigTransfer))
            ->setIsSuccessful(true);
    }

    protected function findProviderConfigByState(?string $state): ?OauthKnpuProviderConfigTransfer
    {
        if ($state === null) {
            return null;
        }

        foreach ($this->securityOauthKnpuConfig->getMerchantUserProviderConfigs() as $oauthKnpuProviderConfigTransfer) {
            if (str_starts_with($state, $oauthKnpuProviderConfigTransfer->getStatePrefixOrFail() . '_')) {
                return $oauthKnpuProviderConfigTransfer;
            }
        }

        return null;
    }

    protected function getOauthClientByConfig(OauthKnpuProviderConfigTransfer $providerConfig): OAuth2ClientInterface
    {
        return $this->clientRegistry->getClient($providerConfig->getClientNameOrFail());
    }

    protected function mapResourceOwnerToResourceOwnerTransfer(
        ResourceOwnerInterface $resourceOwner,
        OauthKnpuProviderConfigTransfer $providerConfig,
    ): ResourceOwnerTransfer {
        return (new ResourceOwnerTransfer())
            ->setId((string)$resourceOwner->getId())
            ->setProvider($providerConfig->getClientNameOrFail())
            ->setEmail($this->extractEmail($resourceOwner, $providerConfig));
    }

    protected function extractEmail(ResourceOwnerInterface $resourceOwner, OauthKnpuProviderConfigTransfer $providerConfig): ?string
    {
        if (method_exists($resourceOwner, 'getEmail')) {
            return $resourceOwner->getEmail();
        }

        $data = $resourceOwner->toArray();
        $email = $data[static::CLAIM_EMAIL] ?? $data[static::CLAIM_MAIL] ?? $data[static::CLAIM_PREFERRED_USERNAME] ?? null;

        if ($email === null) {
            $this->getLogger()->error(
                sprintf('OAuth provider returned no recognisable email claim for client "%s". Available claim keys: [%s]', $providerConfig->getClientNameOrFail(), implode(', ', array_keys($data))),
            );
        }

        return $email;
    }
}
