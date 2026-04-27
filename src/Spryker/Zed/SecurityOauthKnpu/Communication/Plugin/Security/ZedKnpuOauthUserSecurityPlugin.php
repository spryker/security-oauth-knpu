<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\Security;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface;
use Spryker\Shared\SecurityExtension\Dependency\Plugin\SecurityPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * This plugin must be registered before {@link \Spryker\Zed\SecurityGui\Communication\Plugin\Security\ZedUserSecurityPlugin}
 * so that the PUBLIC_ACCESS rule for the OAuth initiate route is added before the catch-all `^/` → ROLE_BACK_OFFICE_USER rule.
 * Symfony's access control evaluates rules in registration order and stops at the first match.
 *
 * @method \Spryker\Zed\SecurityOauthKnpu\Communication\SecurityOauthKnpuCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 */
class ZedKnpuOauthUserSecurityPlugin extends AbstractPlugin implements SecurityPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds the OAuth user initiate route as PUBLIC_ACCESS so unauthenticated users can start the OAuth flow.
     *
     * @api
     *
     * @param \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\SecurityExtension\Configuration\SecurityBuilderInterface
     */
    public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface
    {
        return $this->getFactory()->createOauthUserSecurityBuilderExpander()->extend($securityBuilder, $container);
    }
}
