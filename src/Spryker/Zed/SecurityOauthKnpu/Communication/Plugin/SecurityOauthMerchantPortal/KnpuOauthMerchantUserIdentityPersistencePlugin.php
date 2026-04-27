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
use Spryker\Zed\SecurityMerchantPortalGuiExtension\Dependency\Plugin\OauthMerchantUserPostResolvePluginInterface;

/**
 * @method \Spryker\Zed\SecurityOauthKnpu\Business\SecurityOauthKnpuBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 */
class KnpuOauthMerchantUserIdentityPersistencePlugin extends AbstractPlugin implements OauthMerchantUserPostResolvePluginInterface
{
    /**
     * {@inheritDoc}
     * - Creates or updates the OAuth identity record linking the merchant user to the Knpu provider.
     * - Uses provider and external ID from the resource owner transfer.
     * - Skips if provider or external ID is missing.
     *
     * @api
     */
    public function postResolve(MerchantUserTransfer $merchantUserTransfer, ResourceOwnerTransfer $resourceOwnerTransfer): void
    {
        $this->getBusinessFactory()
            ->createMerchantUserIdentityWriter()
            ->persistMerchantUserIdentityByResourceOwner($merchantUserTransfer, $resourceOwnerTransfer);
    }
}
