<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Business\Provider;

use Generated\Shared\Transfer\OauthAuthenticationLinkTransfer;
use Generated\Shared\Transfer\OauthKnpuProviderConfigTransfer;
use Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig;

class MerchantUserAuthenticationLinkProvider implements MerchantUserAuthenticationLinkProviderInterface
{
    protected const string LINK_TARGET = '_self';

    protected const string REQUEST_PARAM_CLIENT = 'client';

    /**
     * @uses \Spryker\Zed\SecurityOauthKnpu\Communication\Controller\OauthMerchantUserInitiateController::indexAction()
     */
    protected const string ROUTE_PATH_OAUTH_MERCHANT_PORTAL_INITIATE = '/security-oauth-knpu/oauth-merchant-user-initiate';

    public function __construct(protected SecurityOauthKnpuConfig $securityOauthKnpuConfig)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthenticationLinks(): array
    {
        $links = [];

        foreach ($this->securityOauthKnpuConfig->getMerchantUserProviderConfigs() as $oauthKnpuProviderConfigTransfer) {
            $links[] = $this->buildAuthenticationLink($oauthKnpuProviderConfigTransfer);
        }

        return $links;
    }

    protected function buildAuthenticationLink(OauthKnpuProviderConfigTransfer $oauthKnpuProviderConfigTransfer): OauthAuthenticationLinkTransfer
    {
        $href = sprintf(
            '%s?%s=%s',
            static::ROUTE_PATH_OAUTH_MERCHANT_PORTAL_INITIATE,
            static::REQUEST_PARAM_CLIENT,
            $oauthKnpuProviderConfigTransfer->getClientNameOrFail(),
        );

        return (new OauthAuthenticationLinkTransfer())
            ->setHref($href)
            ->setText($oauthKnpuProviderConfigTransfer->getLinkTextOrFail())
            ->setTarget(static::LINK_TARGET);
    }
}
