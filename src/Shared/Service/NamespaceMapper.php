<?php

/**
 * Copyright © __Vender__. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace MyVendor\GraphQL\MyPackage\Shared\Service;

use OxidEsales\GraphQL\Base\Framework\NamespaceMapperInterface;

final class NamespaceMapper implements NamespaceMapperInterface
{
    public function getControllerNamespaceMapping(): array
    {
        return [
            '\\MyVendor\\GraphQL\\MyPackage\\Category\\Controller' => __DIR__ . '/../../Category/Controller/',
        ];
    }

    public function getTypeNamespaceMapping(): array
    {
        return [
            '\\MyVendor\\GraphQL\\MyPackage\\Category\\DataType' => __DIR__ . '/../../Category/DataType/',
        ];
    }
}
