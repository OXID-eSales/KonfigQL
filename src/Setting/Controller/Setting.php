<?php

declare(strict_types=1);

namespace APICodingDays\KonfigQL\Setting\Controller;

use APICodingDays\KonfigQL\Setting\Infrastructure\SettingRepository;
use TheCodingMachine\GraphQLite\Annotations\Query;

final class Setting
{
    /** @var SettingRepository */
    private $settingsService;

    public function __construct(
        SettingRepository $settingsService
    ) {
        $this->settingsService = $settingsService;
    }

    /**
     * @Query()
     *
     * @return \APICodingDays\KonfigQL\Setting\DataType\Setting[]
     */
    public function settings(): array {
        return $this->settingsService->settings();
    }
}