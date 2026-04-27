<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\SecurityOauthUser;

use Generated\Shared\Transfer\ResourceOwnerRequestTransfer;
use Generated\Shared\Transfer\ResourceOwnerResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserClientStrategyPluginInterface;

/**
 * @method \Spryker\Zed\SecurityOauthKnpu\Business\SecurityOauthKnpuBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 */
class KnpuOauthUserClientStrategyPlugin extends AbstractPlugin implements OauthUserClientStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if the request targets the Knpu OAuth provider for back-office users.
     *
     * @api
     */
    public function isApplicable(ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer): bool
    {
        return $this->getBusinessFactory()->createOauthUserResourceOwnerReader()->isApplicable($resourceOwnerRequestTransfer);
    }

    /**
     * {@inheritDoc}
     * - Exchanges the authorization code for a resource owner using the Knpu OAuth client.
     * - Returns a response with the resource owner on success, or isSuccessful=false on failure.
     *
     * @api
     */
    public function getResourceOwner(ResourceOwnerRequestTransfer $resourceOwnerRequestTransfer): ResourceOwnerResponseTransfer
    {
        return $this->getBusinessFactory()->createOauthUserResourceOwnerReader()->getResourceOwner($resourceOwnerRequestTransfer);
    }
}
