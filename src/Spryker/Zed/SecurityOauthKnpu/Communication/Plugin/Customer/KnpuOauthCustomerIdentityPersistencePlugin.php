<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;
use Spryker\Zed\CustomerExtension\Dependency\Plugin\OauthCustomerPostResolvePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SecurityOauthKnpu\Business\SecurityOauthKnpuBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 */
class KnpuOauthCustomerIdentityPersistencePlugin extends AbstractPlugin implements OauthCustomerPostResolvePluginInterface
{
    /**
     * {@inheritDoc}
     * - Creates or updates the OAuth identity record linking the customer to the Knpu provider.
     * - Uses provider and external ID from the resource owner transfer.
     * - Skips if provider or external ID is missing.
     *
     * @api
     */
    public function postResolve(CustomerTransfer $customerTransfer, ResourceOwnerTransfer $resourceOwnerTransfer): void
    {
        $this->getBusinessFactory()->createCustomerIdentityWriter()->persistCustomerIdentityByResourceOwner($customerTransfer, $resourceOwnerTransfer);
    }
}
