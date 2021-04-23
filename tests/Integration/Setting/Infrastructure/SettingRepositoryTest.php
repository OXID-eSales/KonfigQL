<?php

declare(strict_types=1);

namespace APICodingDays\KonfigQL\Tests\Integration\Setting\Infrastructure;

use APICodingDays\KonfigQL\Setting\DataType\Setting;
use APICodingDays\KonfigQL\Setting\Infrastructure\SettingRepository;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;

use PHPUnit\Framework\TestCase;

final class SettingRepositoryTest extends TestCase
{
    public function testSettings(): void
    {
        /** @var SettingRepository $repository */
        $repository = $this->getShopContainer()->get(SettingRepository::class);
        $settings = $repository->settings();

        $this->assertInstanceOf(Setting::class, $settings[0]);
    }

    public function testSingleSetting(): void
    {
        /** @var SettingRepository $repository */
        $repository = $this->getShopContainer()->get(SettingRepository::class);
        $setting = $repository->getSingleSetting('8563fba1965a219c9.51133344');

        $this->assertInstanceOf(Setting::class, $setting);
        $this->assertSame("Lagerverwaltung aktiv", $setting->displayName());
        $this->assertSame("1", $setting->value());
    }

    public function testUpdateSetting(): void
    {
        /** @var SettingRepository $repository */
        $repository = $this->getShopContainer()->get(SettingRepository::class);
        $repository->updateSingleSetting('8563fba1965a25500.87856483', "2");
        $setting = $repository->getSingleSetting('8563fba1965a25500.87856483');

        $this->assertSame("Standard-MwSt.-Satz für alle Artikel", $setting->displayName());
        $this->assertSame("2", $setting->value());
    }

    private function getShopContainer()
    {
        $factory = ContainerFactory::getInstance();

        return $factory->getContainer();
    }
}
