<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\SecurityMerchantPortalGui;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityMerchantPortalGuiExtension\Dependency\Plugin\MerchantUserAuthenticationLinkPluginInterface;

/**
 * @method \Spryker\Zed\SecurityOauthKnpu\Business\SecurityOauthKnpuBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 */
class KnpuOauthMerchantUserAuthenticationLinkPlugin extends AbstractPlugin implements MerchantUserAuthenticationLinkPluginInterface
{
    /**
     * {@inheritDoc}
     * - Reads Merchant Portal OAuth provider configs from `SecurityOauthKnpuConfig::getMerchantUserProviderConfigs()`.
     * - Returns one `OauthAuthenticationLinkTransfer` per provider pointing to the OAuth initiate route.
     * - OAuth state generation is deferred to the initiate route handler — no session writes occur here.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\OauthAuthenticationLinkTransfer>
     */
    public function getAuthenticationLinks(): array
    {
        return $this->getBusinessFactory()->createMerchantUserAuthenticationLinkProvider()->getAuthenticationLinks();
    }
}
