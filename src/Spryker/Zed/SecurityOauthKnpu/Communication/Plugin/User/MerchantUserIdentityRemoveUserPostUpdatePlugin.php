<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\User;

use Generated\Shared\Transfer\UserCollectionResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\UserExtension\Dependency\Plugin\UserPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\SecurityOauthKnpu\Business\SecurityOauthKnpuBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 */
class MerchantUserIdentityRemoveUserPostUpdatePlugin extends AbstractPlugin implements UserPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Executes only for users with status `deleted` (i.e. anonymized users).
     * - Finds `spy_oauth_knpu_merchant_user_identity` records linked via `spy_merchant_user` to the given user IDs.
     * - Removes the found `spy_oauth_knpu_merchant_user_identity` records.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserCollectionResponseTransfer $userCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionResponseTransfer
     */
    public function postUpdate(UserCollectionResponseTransfer $userCollectionResponseTransfer): UserCollectionResponseTransfer
    {
        return $this->getBusinessFactory()->createMerchantUserIdentityWriter()->removeMerchantUserIdentities($userCollectionResponseTransfer);
    }
}
