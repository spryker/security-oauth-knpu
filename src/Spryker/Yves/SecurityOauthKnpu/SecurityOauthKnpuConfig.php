<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Yves\SecurityOauthKnpu;

use Generated\Shared\Transfer\OauthKnpuProviderConfigTransfer;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class SecurityOauthKnpuConfig extends AbstractBundleConfig
{
    protected const string CONFIGURATION_KEY_STOREFRONT_PROVIDERS = 'integrations:federated_authentication:storefront:providers';

    /**
     * Specification:
     * - Returns the list of OAuth provider configs for the Storefront login page.
     * - Reads provider entries from the Back Office configuration.
     * - Returns an empty array when no providers are configured.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\OauthKnpuProviderConfigTransfer>
     */
    public function getCustomerProviderConfigs(): array
    {
        $providersJson = $this->getModuleConfig(static::CONFIGURATION_KEY_STOREFRONT_PROVIDERS, '[]');
        $providers = json_decode((string)$providersJson, true);

        if (!is_array($providers)) {
            return [];
        }

        $oauthKnpuProviderConfigTransfers = [];

        foreach ($providers as $provider) {
            if (!is_array($provider)) {
                continue;
            }

            $oauthKnpuProviderConfigTransfer = (new OauthKnpuProviderConfigTransfer())->fromArray($provider, true);

            if (!$oauthKnpuProviderConfigTransfer->getStatePrefix()) {
                $oauthKnpuProviderConfigTransfer->setStatePrefix($oauthKnpuProviderConfigTransfer->getClientNameOrFail());
            }

            $oauthKnpuProviderConfigTransfers[] = $oauthKnpuProviderConfigTransfer;
        }

        return $oauthKnpuProviderConfigTransfers;
    }
}
