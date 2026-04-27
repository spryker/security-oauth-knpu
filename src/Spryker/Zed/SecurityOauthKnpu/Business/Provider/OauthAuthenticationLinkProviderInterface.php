<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Business\Provider;

use Generated\Shared\Transfer\OauthAuthenticationLinkTransfer;

interface OauthAuthenticationLinkProviderInterface
{
    /**
     * Provides data necessary to render the OAuth login button on the Zed backoffice login page.
     */
    public function getAuthenticationLink(): OauthAuthenticationLinkTransfer;
}
