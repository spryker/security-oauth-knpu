<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Yves\SecurityOauthKnpu\Plugin\CustomerPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CustomerAuthenticationLinkPluginInterface;

/**
 * @method \Spryker\Yves\SecurityOauthKnpu\SecurityOauthKnpuFactory getFactory()
 */
class KnpuCustomerAuthenticationLinkPlugin extends AbstractPlugin implements CustomerAuthenticationLinkPluginInterface
{
    /**
     * Specification:
     * - Reads customer OAuth provider configs from `SecurityOauthKnpuConfig::getCustomerProviderConfigs()`.
     * - Returns one `OauthAuthenticationLinkTransfer` per provider pointing to the OAuth initiate route.
     * - OAuth state generation is deferred to the initiate route handler — no session writes occur here.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\OauthAuthenticationLinkTransfer>
     */
    public function getAuthenticationLinks(): array
    {
        return $this->getFactory()->createOauthCustomerAuthenticationLinkProvider()->getAuthenticationLinks();
    }
}
