<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu;

use Generated\Shared\Transfer\OauthKnpuProviderConfigTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SecurityOauthKnpuConfig extends AbstractBundleConfig
{
    protected const string URL_MERCHANT_PORTAL_LOGIN = '/security-merchant-portal-gui/login';

    /**
     * @uses \Spryker\Zed\SecurityGui\SecurityGuiConfig::LOGIN_PATH
     */
    protected const string URL_ZED_LOGIN = '/security-gui/login';

    protected const string CONFIGURATION_KEY_MERCHANT_PORTAL_PROVIDERS = 'integrations:federated_authentication:merchant_portal:providers';

    protected const string CONFIGURATION_KEY_BACKOFFICE_PROVIDERS = 'integrations:federated_authentication:backoffice:providers';

    /**
     * Specification:
     * - Returns the URL of the Zed backoffice login page.
     * - Used for redirecting after OAuth initiation errors.
     *
     * @api
     */
    public function getUrlZedLogin(): string
    {
        return static::URL_ZED_LOGIN;
    }

    /**
     * Specification:
     * - Returns the URL of the Merchant Portal login page.
     * - Used for redirecting after OAuth initiation errors.
     *
     * @api
     */
    public function getUrlMerchantPortalLogin(): string
    {
        return static::URL_MERCHANT_PORTAL_LOGIN;
    }

    /**
     * Specification:
     * - Returns the list of OAuth provider configs for Merchant Portal users.
     * - Reads provider entries from the Back Office configuration.
     * - Returns an empty array when no providers are configured.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\OauthKnpuProviderConfigTransfer>
     */
    public function getMerchantUserProviderConfigs(): array
    {
        $providersJson = $this->getModuleConfig(static::CONFIGURATION_KEY_MERCHANT_PORTAL_PROVIDERS, '[]');
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

    /**
     * Specification:
     * - Returns the OAuth provider config for Zed backoffice users.
     * - Reads provider entries from the Back Office configuration.
     * - Returns an empty array when no provider is configured.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\OauthKnpuProviderConfigTransfer>
     */
    public function getZedUserProviderConfigs(): array
    {
        $providersJson = $this->getModuleConfig(static::CONFIGURATION_KEY_BACKOFFICE_PROVIDERS, '[]');
        $providers = json_decode((string)$providersJson, true);

        if (!is_array($providers)) {
            return [];
        }

        $oauthKnpuProviderConfigTransfers = [];

        foreach ($providers as $provider) {
            if (!is_array($provider)) {
                continue;
            }

            $transfer = (new OauthKnpuProviderConfigTransfer())->fromArray($provider, true);

            if (!$transfer->getStatePrefix()) {
                $transfer->setStatePrefix($transfer->getClientNameOrFail());
            }

            $oauthKnpuProviderConfigTransfers[] = $transfer;
        }

        return $oauthKnpuProviderConfigTransfers;
    }
}
