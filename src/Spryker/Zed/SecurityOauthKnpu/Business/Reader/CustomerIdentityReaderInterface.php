<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Business\Reader;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ResourceOwnerTransfer;

interface CustomerIdentityReaderInterface
{
    public function findCustomerByResourceOwner(ResourceOwnerTransfer $resourceOwnerTransfer): ?CustomerTransfer;
}
