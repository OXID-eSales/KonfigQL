<?php

declare(strict_types=1);

namespace APICodingDays\KonfigQL\Setting\Infrastructure;

use APICodingDays\KonfigQL\Setting\DataType\Setting;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopProfessional\Core\DatabaseProvider;

final class SettingRepository
{
    public function settings() : array
    {
        $configKey = Registry::getConfig()->getConfigParam('sConfigKey');
        $shopId = Registry::getConfig()->getShopId();
        $db = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
        $query = "SELECT OXID, OXVARNAME, decode(OXVARVALUE, ?) AS OXVARVALUE, OXVARTYPE
                    FROM oxconfig
                    WHERE OXSHOPID = ?";
        $settings = $db->getAll($query, [$configKey, $shopId]);

        return array_map(function ($setting) {
            return new Setting($setting);
        }, $settings);
    }

    public function getSingleSetting($settingName)
    {
        $configKey = Registry::getConfig()->getConfigParam('sConfigKey');
        $shopId = Registry::getConfig()->getShopId();
        $db = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
        $query = "SELECT OXID, decode(OXVARVALUE, ?) AS OXVARVALUE, OXVARTYPE
                    FROM oxconfig
                    WHERE OXSHOPID = ?
                    AND OXVARNAME = ?";

        $setting = $db->getCol($query, [$configKey, $shopId, $settingName]);

        return new Setting($setting);
    }
}
