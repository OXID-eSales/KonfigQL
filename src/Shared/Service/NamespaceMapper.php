<?php

/**
 * Copyright © __Vender__. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace APICodingDays\KonfigQL\Shared\Service;

use OxidEsales\GraphQL\Base\Framework\NamespaceMapperInterface;

final class NamespaceMapper implements NamespaceMapperInterface
{
    public function getControllerNamespaceMapping(): array
    {
        return [
            '\\APICodingDays\KonfigQL\\Setting\\Controller' => __DIR__ . '/../../Setting/Controller/',
        ];
    }

    public function getTypeNamespaceMapping(): array
    {
        return [
            '\\APICodingDays\KonfigQL\\Setting\\DataType' => __DIR__ . '/../../Setting/DataType/',
        ];
    }
}
