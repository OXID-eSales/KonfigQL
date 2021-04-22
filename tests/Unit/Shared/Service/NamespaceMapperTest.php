<?php

/**
 * Copyright © __Vender__. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace APICodingDays\KonfigQL\Tests\Unit\Shared\Service;

use APICodingDays\KonfigQL\Shared\Service\NamespaceMapper;
use PHPUnit\Framework\TestCase;

/**
 * @covers APICodingDays\KonfigQL\Shared\Service\NamespaceMapper
 */
final class NamespaceMapperTest extends TestCase
{
    public function testFooBar(): void
    {
        $namespaceMapper = new NamespaceMapper();
        $this->assertCount(
            1,
            $namespaceMapper->getControllerNamespaceMapping()
        );
        $this->assertCount(
            1,
            $namespaceMapper->getTypeNamespaceMapping()
        );
    }
}
