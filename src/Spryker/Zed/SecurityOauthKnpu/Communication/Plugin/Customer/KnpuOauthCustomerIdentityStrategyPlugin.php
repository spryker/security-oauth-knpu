<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Spryker\Zed\CustomerExtension\Dependency\Plugin\OauthCustomerAuthenticationStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SecurityOauthKnpu\Business\SecurityOauthKnpuBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 */
class KnpuOauthCustomerIdentityStrategyPlugin extends AbstractPlugin implements OauthCustomerAuthenticationStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true when both provider and external ID are present on the resource owner.
     *
     * @api
     */
    public function isApplicable(ResourceOwnerTransfer $resourceOwnerTransfer): bool
    {
        return $resourceOwnerTransfer->getProvider() !== null
            && $resourceOwnerTransfer->getId() !== null;
    }

    /**
     * {@inheritDoc}
     * - Looks up the customer via the persisted OAuth identity record.
     * - Returns null if no identity record exists for the provider and external ID.
     *
     * @api
     */
    public function resolveOauthCustomer(ResourceOwnerTransfer $resourceOwnerTransfer): ?CustomerTransfer
    {
        return $this->getBusinessFactory()->createCustomerIdentityReader()->findCustomerByResourceOwner($resourceOwnerTransfer);
    }
}
