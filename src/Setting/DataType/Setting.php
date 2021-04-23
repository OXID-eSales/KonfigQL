<?php

declare(strict_types=1);

namespace APICodingDays\KonfigQL\Setting\DataType;

use APICodingDays\KonfigQL\Setting\Infrastructure\SettingTitleMap;
use OxidEsales\Eshop\Core\Registry;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;

/**
 * @Type()
 */
final class Setting
{
    private $setting;

    public function __construct(array $setting)
    {
        $this->setting = $setting;
    }

    /**
     * @Field
     */
    public function id(): ID
    {
        return new ID(
            $this->setting['OXID']
        );
    }

    /**
     * @Field
     */
    public function displayName(): string
    {
        $name = $this->setting['OXVARNAME'];
        $translation = $this->findTranslation($name, 'translation');
        if ($translation) {
            return $translation;
        }

        return (string) $name;
    }

    /**
     * @Field
     */
    public function internalName(): string
    {
        return (string) $this->setting['OXVARNAME'];
    }

    /**
     * @Field
     */
    public function helpText(): string
    {
        $name = $this->setting['OXVARNAME'];
        $translation = $this->findTranslation($name, 'help');
        if ($translation) {
            return $translation;
        }

        return (string) '';
    }

    /**
     * @Field
     */
    public function value(): string
    {
        return (string) $this->setting['OXVARVALUE'];
    }

    /**
     * @Field
     */
    public function type(): string
    {
        $map = [
            'aarr'   => 'associative array',
            'arr'    => 'array',
            'bool'   => 'boolean',
            'num'    => 'number',
            'str'    => 'string',
            'select' => 'select',
        ];
        $internalType = $this->setting['OXVARTYPE'];

        $res = $map[$internalType] ?? $internalType;

        return (string) $res;
    }

    private function findTranslation($name, $type): string
    {
        $translationKey = SettingTitleMap::MAP[$name][$type] ?? null;
        if ($translationKey) {
            $translation = Registry::getLang()->translateString($translationKey, null, true);
            if ($translation && $translation !== $translationKey) {
                return (string) $translation;
            }
        }

        foreach (['SHOP_THEME_', 'SHOP_MODULE_'] as $item) {
            $translationKey = ($type === 'help' ? 'HELP_' : '') . $item . $name;
            $translation = Registry::getLang()->translateString($translationKey, null, true);
            if ($translation && $translation !== $translationKey) {
                return (string) $translation;
            }
        }

        return '';
    }
}
