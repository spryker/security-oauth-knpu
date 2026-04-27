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
use Spryker\Zed\SecurityOauthUserExtension\Dependency\Plugin\OauthUserPostResolvePluginInterface;

/**
 * @method \Spryker\Zed\SecurityOauthKnpu\Business\SecurityOauthKnpuBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 */
class KnpuOauthUserIdentityPersistencePlugin extends AbstractPlugin implements OauthUserPostResolvePluginInterface
{
    /**
     * {@inheritDoc}
     * - Creates or updates the OAuth identity record linking the back-office user to the Knpu provider.
     * - Uses provider and external ID from the resource owner transfer.
     * - Skips if provider or external ID is missing.
     *
     * @api
     */
    public function postResolve(UserTransfer $userTransfer, ResourceOwnerTransfer $resourceOwnerTransfer): void
    {
        $this->getBusinessFactory()->createUserIdentityWriter()->persistUserIdentityByResourceOwner($userTransfer, $resourceOwnerTransfer);
    }
}
