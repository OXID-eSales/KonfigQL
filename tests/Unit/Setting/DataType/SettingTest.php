<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace APICodingDays\KonfigQL\Tests\Unit\DataType;

use APICodingDays\KonfigQL\Setting\DataType\Setting as SettingDataType;
use PHPUnit\Framework\TestCase;

final class SettingTest extends TestCase
{
    public function testDisplayNameWithTranslationNotFound(): void
    {
        $type = (new SettingDataType(['OXVARNAME' => 123]))->displayName();
        $this->assertSame('123', $type);
    }

    public function testHelpTextWithWithTranslationNotFound(): void
    {
        $type = (new SettingDataType(['OXVARNAME' => 123]))->helpText();
        $this->assertEmpty($type);
    }

    /** @dataProvider testTypeDataProvider */
    public function testType($key, $expectedTypeName): void
    {
        $typeName = (new SettingDataType(['OXVARTYPE' => $key]))->type();
        $this->assertSame($expectedTypeName, $typeName);
    }

    public function testTypeDataProvider(): array
    {
        return [
            [null, ''],
            ['aarr', 'associative array'],
            ['array', 'array', ],
            ['something', 'something'],
        ];
    }
}
