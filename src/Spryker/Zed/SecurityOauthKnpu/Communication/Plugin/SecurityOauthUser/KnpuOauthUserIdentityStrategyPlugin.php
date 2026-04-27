<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\SecurityOauthUser;

use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserAuthenticationStrategyPluginInterface;

/**
 * @method \Spryker\Zed\SecurityOauthKnpu\Business\SecurityOauthKnpuBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 */
class KnpuOauthUserIdentityStrategyPlugin extends AbstractPlugin implements OauthUserAuthenticationStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if the resource owner has both a provider and an external ID set.
     *
     * @api
     */
    public function isApplicable(ResourceOwnerTransfer $resourceOwnerTransfer): bool
    {
        return $resourceOwnerTransfer->getId() !== null && $resourceOwnerTransfer->getProvider() !== null;
    }

    /**
     * {@inheritDoc}
     * - Finds the back-office user by the OAuth identity record matching provider and external ID.
     * - Returns null if no matching identity record exists.
     *
     * @api
     */
    public function resolveOauthUser(ResourceOwnerTransfer $resourceOwnerTransfer): ?UserTransfer
    {
        return $this->getBusinessFactory()->createUserIdentityReader()->findUserByResourceOwner($resourceOwnerTransfer);
    }
}
