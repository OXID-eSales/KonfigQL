<?php

namespace APICodingDays\KonfigQL\Setting\Infrastructure;

use APICodingDays\KonfigQL\Setting\DataType\Setting;
use OxidEsales\EshopProfessional\Core\DatabaseProvider;

class SettingRepository
{
    public function settings() : array
    {
        $query = "SELECT OXID, OXSHOPID, OXVARNAME, decode( OXVARVALUE, 'fq45QS09_fqyx09239QQ' ) AS OXVARVALUE
                    FROM oxconfig
                    WHERE OXSHOPID = 1";

        $settings = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC)->getAll($query);

        return array_map(function ($setting) { return new Setting($setting); }, $settings);
    }
}
