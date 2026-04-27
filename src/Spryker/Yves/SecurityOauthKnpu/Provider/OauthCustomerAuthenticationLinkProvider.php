<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Yves\SecurityOauthKnpu\Provider;

use Generated\Shared\Transfer\OauthAuthenticationLinkTransfer;
use Generated\Shared\Transfer\OauthKnpuProviderConfigTransfer;
use Spryker\Yves\SecurityOauthKnpu\SecurityOauthKnpuConfig;

class OauthCustomerAuthenticationLinkProvider implements OauthCustomerAuthenticationLinkProviderInterface
{
    protected const string LINK_TARGET = '_self';

    protected const string REQUEST_PARAM_CLIENT = 'client';

    /**
     * @see \Spryker\Yves\SecurityOauthKnpu\Plugin\Router\SecurityOauthKnpuRouteProviderPlugin::ROUTE_PATH_OAUTH_INITIATE
     */
    protected const string ROUTE_PATH_OAUTH_INITIATE = '/login/oauth-start';

    public function __construct(protected SecurityOauthKnpuConfig $securityOauthKnpuConfig)
    {
    }

    /**
     * {@inheritDoc}
     *
     * @return array<\Generated\Shared\Transfer\OauthAuthenticationLinkTransfer>
     */
    public function getAuthenticationLinks(): array
    {
        $links = [];

        foreach ($this->securityOauthKnpuConfig->getCustomerProviderConfigs() as $oauthKnpuProviderConfigTransfer) {
            $links[] = $this->buildAuthenticationLink($oauthKnpuProviderConfigTransfer);
        }

        return $links;
    }

    protected function buildAuthenticationLink(OauthKnpuProviderConfigTransfer $oauthKnpuProviderConfigTransfer): OauthAuthenticationLinkTransfer
    {
        $href = sprintf(
            '%s?%s=%s',
            static::ROUTE_PATH_OAUTH_INITIATE,
            static::REQUEST_PARAM_CLIENT,
            $oauthKnpuProviderConfigTransfer->getClientNameOrFail(),
        );

        return (new OauthAuthenticationLinkTransfer())
            ->setHref($href)
            ->setText($oauthKnpuProviderConfigTransfer->getLinkTextOrFail())
            ->setTarget(static::LINK_TARGET);
    }
}
