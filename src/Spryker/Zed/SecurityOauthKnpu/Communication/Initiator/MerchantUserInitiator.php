<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Communication\Initiator;

use Generated\Shared\Transfer\OauthKnpuProviderConfigTransfer;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\DependencyInjection\InvalidOAuth2ClientException;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MerchantUserInitiator implements MerchantUserInitiatorInterface
{
    use LoggerTrait;

    protected const string OPTION_STATE = 'state';

    public function __construct(
        protected ClientRegistry $clientRegistry,
        protected SecurityOauthKnpuConfig $securityOauthKnpuConfig,
    ) {
    }

    public function getOauthRedirectResponse(string $clientName): ?RedirectResponse
    {
        $providerConfig = $this->findProviderConfigByClientName($clientName);

        if ($providerConfig === null) {
            return null;
        }

        try {
            return $this->buildOauthRedirectResponse($providerConfig);
        } catch (InvalidOAuth2ClientException $exception) {
            $this->getLogger()->warning(
                sprintf('KnpU OAuth2 client "%s" is not registered in the KnpU bundle configuration.', $clientName),
                ['exception' => $exception->getMessage()],
            );

            return null;
        }
    }

    protected function findProviderConfigByClientName(string $clientName): ?OauthKnpuProviderConfigTransfer
    {
        foreach ($this->securityOauthKnpuConfig->getMerchantUserProviderConfigs() as $oauthKnpuProviderConfigTransfer) {
            if ($oauthKnpuProviderConfigTransfer->getClientName() === $clientName) {
                return $oauthKnpuProviderConfigTransfer;
            }
        }

        return null;
    }

    protected function buildOauthRedirectResponse(OauthKnpuProviderConfigTransfer $providerConfig): RedirectResponse
    {
        $state = sprintf('%s_%s', $providerConfig->getStatePrefixOrFail(), bin2hex(random_bytes(16)));
        $oauthClient = $this->clientRegistry->getClient($providerConfig->getClientNameOrFail());

        $options = [static::OPTION_STATE => $state];

        return $oauthClient->redirect($providerConfig->getScopes(), $options);
    }
}
