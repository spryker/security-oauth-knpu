<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\SecurityGui;

use Generated\Shared\Transfer\OauthAuthenticationLinkTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SecurityGuiExtension\Dependency\Plugin\AuthenticationLinkPluginInterface;

/**
 * @method \Spryker\Zed\SecurityOauthKnpu\Business\SecurityOauthKnpuBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SecurityOauthKnpu\SecurityOauthKnpuConfig getConfig()
 */
class KnpuOauthAuthenticationLinkPlugin extends AbstractPlugin implements AuthenticationLinkPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns an OAuth authentication link for the Zed backoffice login page.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\OauthAuthenticationLinkTransfer
     */
    public function getAuthenticationLink(): OauthAuthenticationLinkTransfer
    {
        return $this->getBusinessFactory()->createOauthAuthenticationLinkProvider()->getAuthenticationLink();
    }
}
