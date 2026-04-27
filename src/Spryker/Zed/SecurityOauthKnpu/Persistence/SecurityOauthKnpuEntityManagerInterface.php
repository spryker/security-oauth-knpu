<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Persistence;

use Generated\Shared\Transfer\OauthKnpuCustomerIdentityTransfer;
use Generated\Shared\Transfer\OauthKnpuMerchantUserIdentityTransfer;
use Generated\Shared\Transfer\OauthKnpuUserIdentityTransfer;

interface SecurityOauthKnpuEntityManagerInterface
{
    public function createCustomerIdentity(OauthKnpuCustomerIdentityTransfer $oauthKnpuCustomerIdentityTransfer): OauthKnpuCustomerIdentityTransfer;

    public function updateCustomerIdentity(OauthKnpuCustomerIdentityTransfer $oauthKnpuCustomerIdentityTransfer): OauthKnpuCustomerIdentityTransfer;

    public function createUserIdentity(OauthKnpuUserIdentityTransfer $oauthKnpuUserIdentityTransfer): OauthKnpuUserIdentityTransfer;

    public function updateUserIdentity(OauthKnpuUserIdentityTransfer $oauthKnpuUserIdentityTransfer): OauthKnpuUserIdentityTransfer;

    public function createMerchantUserIdentity(
        OauthKnpuMerchantUserIdentityTransfer $oauthKnpuMerchantUserIdentityTransfer
    ): OauthKnpuMerchantUserIdentityTransfer;

    public function updateMerchantUserIdentity(
        OauthKnpuMerchantUserIdentityTransfer $oauthKnpuMerchantUserIdentityTransfer
    ): OauthKnpuMerchantUserIdentityTransfer;
}
