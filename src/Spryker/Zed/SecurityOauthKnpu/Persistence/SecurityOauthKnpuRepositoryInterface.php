<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Persistence;

use Generated\Shared\Transfer\OauthKnpuCustomerIdentityTransfer;
use Generated\Shared\Transfer\OauthKnpuIdentityCriteriaTransfer;
use Generated\Shared\Transfer\OauthKnpuMerchantUserIdentityTransfer;
use Generated\Shared\Transfer\OauthKnpuUserIdentityTransfer;

interface SecurityOauthKnpuRepositoryInterface
{
    public function findCustomerIdentity(OauthKnpuIdentityCriteriaTransfer $oauthKnpuIdentityCriteriaTransfer): ?OauthKnpuCustomerIdentityTransfer;

    public function findUserIdentity(OauthKnpuIdentityCriteriaTransfer $oauthKnpuIdentityCriteriaTransfer): ?OauthKnpuUserIdentityTransfer;

    public function findMerchantUserIdentity(OauthKnpuIdentityCriteriaTransfer $oauthKnpuIdentityCriteriaTransfer): ?OauthKnpuMerchantUserIdentityTransfer;

    /**
     * @param array<int> $userIds
     *
     * @return array<int>
     */
    public function getMerchantUserIdentityIdsByUserIds(array $userIds): array;
}
