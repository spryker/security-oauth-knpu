<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\SecurityOauthMerchantPortal;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityMerchantPortalGuiExtension\Dependency\Plugin\OauthMerchantUserAuthenticationStrategyPluginInterface;

/**
 * @method \Spryker\Zed\SecurityOauthKnpu\Business\SecurityOauthKnpuBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 */
class KnpuOauthMerchantUserIdentityStrategyPlugin extends AbstractPlugin implements OauthMerchantUserAuthenticationStrategyPluginInterface
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
     * - Finds the merchant user by the OAuth identity record matching provider and external ID.
     * - Returns null if no matching identity record exists.
     *
     * @api
     */
    public function resolveOauthMerchantUser(ResourceOwnerTransfer $resourceOwnerTransfer): ?MerchantUserTransfer
    {
        return $this->getBusinessFactory()->createMerchantUserIdentityReader()->findMerchantUserByResourceOwner($resourceOwnerTransfer);
    }
}
