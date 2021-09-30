<?php

namespace Xanweb\C5\HtmlHelper\Form;

abstract class LinkType
{
    /**
     * No Link.
     */
    public const NO_LINK = 0;

    /**
     * Internal Link.
     */
    public const INTERNAL = 1;

    /**
     * External Link.
     */
    public const EXTERNAL = 2;

    public static function externalLinkFieldName(): string
    {
        return 'linkExtern';
    }

    public static function internalLinkFieldName(): string
    {
        return 'internalLinkCID';
    }

    public static function isExternal(int $linkType): bool
    {
        return $linkType === static::EXTERNAL;
    }

    public static function isInternal(int $linkType): bool
    {
        return $linkType === static::INTERNAL;
    }

    /**
     * Detect Link Type from item.
     *
     * @param array $item
     *
     * @return int
     */
    public static function getTypeForItem(array $item): int
    {
        $linkType = static::NO_LINK;
        if (!empty(trim($item[static::externalLinkFieldName()]))) {
            $linkType = static::EXTERNAL;
        } elseif ($item[static::internalLinkFieldName()] > 0) {
            $linkType = static::INTERNAL;
        }

        return $linkType;
    }

    /**
     * Get list of link types.
     *
     * @return array<int, string>
     */
    public static function getList(): array
    {
        return [
            static::NO_LINK => t('No link'),
            static::INTERNAL => t('Another Page'),
            static::EXTERNAL => t('External URL'),
        ];
    }

    /**
     * Sanitize data before block save.
     *
     * @param array $data
     */
    public static function sanitizeData(array &$data): void
    {
        if (!isset($data['sortOrder'])) {
            return;
        }

        $count = count($data['sortOrder']);
        $i = 0;

        while ($i < $count) {
            switch ((int) $data['linkType'][$i]) {
                case static::INTERNAL:
                    $data[static::externalLinkFieldName()][$i] = '';

                    break;
                case static::EXTERNAL:
                    $data[static::internalLinkFieldName()][$i] = 0;

                    break;
                case static::NO_LINK:
                default:
                    $data[static::externalLinkFieldName()][$i] = '';
                    $data[static::internalLinkFieldName()][$i] = 0;

                    break;
            }

            $i++;
        }
    }
}
