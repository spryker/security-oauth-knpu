<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Yves\SecurityOauthKnpu;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class SecurityOauthKnpuConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns the list of configured OAuth provider configs.
     * - Override at project level to define providers.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\OauthKnpuProviderConfigTransfer>
     */
    public function getCustomerProviderConfigs(): array
    {
        return [];
    }
}
