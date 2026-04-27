<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Yves\SecurityOauthKnpu\Reader;

use Generated\Shared\Transfer\OauthKnpuProviderConfigTransfer;
use Generated\Shared\Transfer\ResourceOwnerRequestTransfer;
use Generated\Shared\Transfer\ResourceOwnerResponseTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\SecurityOauthKnpu\SecurityOauthKnpuConfig;

class OauthCustomerResourceOwnerReader implements OauthCustomerResourceOwnerReaderInterface
{
    use LoggerTrait;

    protected const string CLAIM_EMAIL = 'email';

    protected const string CLAIM_MAIL = 'mail';

    protected const string CLAIM_PREFERRED_USERNAME = 'preferred_username';

    protected const string CLAIM_GIVEN_NAME = 'given_name';

    protected const string CLAIM_FAMILY_NAME = 'family_name';

    protected const string CLAIM_NAME = 'name';

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
        $code = $resourceOwnerRequestTransfer->getCode();

        if ($code === null) {
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
        $options = [static::OPTION_CODE => $code];

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
            ->setResourceOwner($this->mapResourceOwnerToTransfer($resourceOwner, $oauthKnpuProviderConfigTransfer))
            ->setIsSuccessful(true);
    }

    protected function findProviderConfigByState(?string $state): ?OauthKnpuProviderConfigTransfer
    {
        if ($state === null) {
            return null;
        }

        foreach ($this->securityOauthKnpuConfig->getCustomerProviderConfigs() as $oauthKnpuProviderConfigTransfer) {
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

    protected function mapResourceOwnerToTransfer(
        ResourceOwnerInterface $resourceOwner,
        OauthKnpuProviderConfigTransfer $providerConfig,
    ): ResourceOwnerTransfer {
        $resourceOwnerTransfer = (new ResourceOwnerTransfer())
            ->setId((string)$resourceOwner->getId())
            ->setProvider($providerConfig->getClientNameOrFail());

        $email = $this->extractEmail($resourceOwner, $providerConfig);

        if ($email !== null) {
            $resourceOwnerTransfer->setEmail($email);
        }

        $this->extractNames($resourceOwner, $resourceOwnerTransfer);

        return $resourceOwnerTransfer;
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

    protected function extractNames(ResourceOwnerInterface $resourceOwner, ResourceOwnerTransfer $resourceOwnerTransfer): void
    {
        $data = $resourceOwner->toArray();

        if (isset($data[static::CLAIM_GIVEN_NAME])) {
            $resourceOwnerTransfer->setFirstName($data[static::CLAIM_GIVEN_NAME]);
        }

        if (isset($data[static::CLAIM_FAMILY_NAME])) {
            $resourceOwnerTransfer->setLastName($data[static::CLAIM_FAMILY_NAME]);
        }

        if (isset($data[static::CLAIM_NAME]) && !$resourceOwnerTransfer->getFirstName()) {
            $nameParts = explode(' ', $data[static::CLAIM_NAME], 2);
            $resourceOwnerTransfer->setFirstName($nameParts[0]);
            $resourceOwnerTransfer->setLastName($nameParts[1] ?? null);
        }
    }
}
