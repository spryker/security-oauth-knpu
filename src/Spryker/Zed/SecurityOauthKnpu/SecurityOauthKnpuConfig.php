<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SecurityOauthKnpuConfig extends AbstractBundleConfig
{
    protected const string URL_MERCHANT_PORTAL_LOGIN = '/security-merchant-portal-gui/login';

    /**
     * @uses \Spryker\Zed\SecurityGui\SecurityGuiConfig::LOGIN_PATH
     */
    protected const string URL_ZED_LOGIN = '/security-gui/login';

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
     * - Returns the list of configured OAuth provider configs for Merchant Portal users.
     * - Override at project level to define providers.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\OauthKnpuProviderConfigTransfer>
     */
    public function getMerchantUserProviderConfigs(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Returns the list of configured OAuth provider configs for Zed backoffice users.
     * - Override at project level to define providers.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\OauthKnpuProviderConfigTransfer>
     */
    public function getZedUserProviderConfigs(): array
    {
        return [];
    }
}
