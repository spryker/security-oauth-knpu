<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Yves\SecurityOauthKnpu\Plugin\CustomerPage;

use Generated\Shared\Transfer\ResourceOwnerRequestTransfer;
use Generated\Shared\Transfer\ResourceOwnerResponseTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\OauthCustomerClientStrategyPluginInterface;

/**
 * @method \Spryker\Yves\SecurityOauthKnpu\SecurityOauthKnpuFactory getFactory()
 */
class KnpuOauthCustomerClientStrategyPlugin extends AbstractPlugin implements OauthCustomerClientStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function isApplicable(ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer): bool
    {
        return $this->getFactory()->createOauthCustomerResourceOwnerReader()->isApplicable($resourceOwnerRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function getResourceOwner(ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer): ResourceOwnerResponseTransfer
    {
        return $this->getFactory()->createOauthCustomerResourceOwnerReader()->getResourceOwner($resourceOwnerRequestTransfer);
    }
}
