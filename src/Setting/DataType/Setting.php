<?php

declare(strict_types=1);

namespace APICodingDays\KonfigQL\Setting\DataType;

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
        return (string) $this->setting['OXVARNAME'];
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
}
