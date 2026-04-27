<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Communication\Expander;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;

class OauthUserSecurityBuilderExpander implements OauthUserSecurityBuilderExpanderInterface
{
    // The initiate route is outside the ^/security-gui ignorable pattern, so it gets blocked
    // by the User firewall's ROLE_BACK_OFFICE_USER access rule before the controller is reached.
    // We must add it as PUBLIC_ACCESS so unauthenticated users can start the OAuth flow.
    /**
     * @uses \Spryker\Zed\SecurityOauthKnpu\Communication\Controller\OauthUserInitiateController::indexAction()
     */
    protected const string ROUTE_PATTERN_OAUTH_USER_INITIATE = '^/security-oauth-knpu/oauth-user-initiate';

    protected const string ACCESS_MODE_PUBLIC = 'PUBLIC_ACCESS';

    public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
    {
        return $this->addAccessRules($securityBuilder);
    }

    protected function addAccessRules(SecurityBuilderInterface $securityBuilder): SecurityBuilderInterface
    {
        return $securityBuilder->addAccessRules([
            [
                static::ROUTE_PATTERN_OAUTH_USER_INITIATE,
                static::ACCESS_MODE_PUBLIC,
            ],
        ]);
    }
}
