<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SecurityOauthKnpu\Communication\Plugin\AclMerchantPortal;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Spryker\Zed\SecurityOauthKnpu\Communication\Plugin\AclMerchantPortal\SecurityOauthKnpuMerchantUserAclEntityRuleExpanderPlugin;
use SprykerTest\Zed\SecurityOauthKnpu\SecurityOauthKnpuCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SecurityOauthKnpu
 * @group Communication
 * @group Plugin
 * @group AclMerchantPortal
 * @group SecurityOauthKnpuMerchantUserAclEntityRuleExpanderPluginTest
 * Add your own group annotations below this line
 */
class SecurityOauthKnpuMerchantUserAclEntityRuleExpanderPluginTest extends Unit
{
    protected const string MERCHANT_USER_IDENTITY_ENTITY = 'Orm\Zed\SecurityOauthKnpu\Persistence\SpyOauthKnpuMerchantUserIdentity';

    protected const string SCOPE_GLOBAL = 'global';

    /**
     * READ | UPDATE | CREATE bitmask
     */
    protected const int EXPECTED_PERMISSION_MASK = 0b111;

    protected SecurityOauthKnpuCommunicationTester $tester;

    public function testExpandAppendsAclEntityRuleWithCorrectPermissions(): void
    {
        // Arrange
        $plugin = new SecurityOauthKnpuMerchantUserAclEntityRuleExpanderPlugin();

        // Act
        $expandedRules = $plugin->expand([]);

        // Assert
        $this->assertCount(1, $expandedRules, 'Expected exactly one ACL rule to be appended.');

        $rule = $expandedRules[0];
        $this->assertSame(static::MERCHANT_USER_IDENTITY_ENTITY, $rule->getEntity());
        $this->assertSame(static::SCOPE_GLOBAL, $rule->getScope());
        $this->assertSame(static::EXPECTED_PERMISSION_MASK, $rule->getPermissionMask());
    }

    public function testExpandPreservesExistingRules(): void
    {
        // Arrange
        $existingRule = (new AclEntityRuleTransfer())
            ->setEntity('Some\Existing\Entity')
            ->setScope(static::SCOPE_GLOBAL)
            ->setPermissionMask(0b1);

        $plugin = new SecurityOauthKnpuMerchantUserAclEntityRuleExpanderPlugin();

        // Act
        $expandedRules = $plugin->expand([$existingRule]);

        // Assert
        $this->assertCount(2, $expandedRules, 'Expected two rules: the existing one and the newly appended one.');
        $this->assertSame('Some\Existing\Entity', $expandedRules[0]->getEntity(), 'Expected existing rule to remain at index 0.');
        $this->assertSame(static::MERCHANT_USER_IDENTITY_ENTITY, $expandedRules[1]->getEntity(), 'Expected new rule to be appended at the end.');
    }
}
