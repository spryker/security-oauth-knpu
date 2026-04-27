<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Business\Provider;

use Generated\Shared\Transfer\OauthAuthenticationLinkTransfer;
use Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig;

class OauthAuthenticationLinkProvider implements OauthAuthenticationLinkProviderInterface
{
    protected const string LINK_TARGET = '_self';

    protected const string REQUEST_PARAM_CLIENT = 'client';

    /**
     * @uses \Spryker\Zed\SecurityOauthKnpu\Communication\Controller\OauthUserInitiateController::indexAction()
     */
    protected const string ROUTE_PATH_OAUTH_USER_INITIATE = '/security-oauth-knpu/oauth-user-initiate';

    public function __construct(protected SecurityOauthKnpuConfig $securityOauthKnpuConfig)
    {
    }

    public function getAuthenticationLink(): OauthAuthenticationLinkTransfer
    {
        $oauthKnpuProviderConfigTransfer = $this->securityOauthKnpuConfig->getZedUserProviderConfigs()[0] ?? null;

        if ($oauthKnpuProviderConfigTransfer === null) {
            return new OauthAuthenticationLinkTransfer();
        }

        $href = sprintf(
            '%s?%s=%s',
            static::ROUTE_PATH_OAUTH_USER_INITIATE,
            static::REQUEST_PARAM_CLIENT,
            $oauthKnpuProviderConfigTransfer->getClientNameOrFail(),
        );

        return (new OauthAuthenticationLinkTransfer())
            ->setHref($href)
            ->setText($oauthKnpuProviderConfigTransfer->getLinkTextOrFail())
            ->setTarget(static::LINK_TARGET);
    }
}
