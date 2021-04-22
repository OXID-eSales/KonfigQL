<?php

/**
 * Copyright © __Vender__. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace APICodingDays\KonfigQL\Setting\Exception;

use OxidEsales\GraphQL\Base\Exception\NotFound;

use function sprintf;

final class UpdateSettingFailed extends NotFound
{
    public static function byId(string $id): self
    {
        return new self(sprintf('Category was not found by id: %s', $id));
        return new self(sprintf('Updating setting %s failed with value ', $id));
    }
}
