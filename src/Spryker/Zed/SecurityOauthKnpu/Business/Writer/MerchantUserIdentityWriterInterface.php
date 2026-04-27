<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Business\Writer;

use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;

interface MerchantUserIdentityWriterInterface
{
    public function persistMerchantUserIdentityByResourceOwner(MerchantUserTransfer $merchantUserTransfer, ResourceOwnerTransfer $resourceOwnerTransfer): void;
}
