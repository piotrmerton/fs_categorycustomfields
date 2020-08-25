<?php

/**
 * SQL queries file
 */

declare(strict_types=1);

namespace PrestaShop\Module\Fs_CategoryCustomFields\Sql;

/**
 * Class SqlQueries
 * @package PrestaShop\Module\Fs_CategoryCustomFields\Sql
 */
class SqlQueries
{
    /**
     * Install database queries.
     *
     * @return array
     */
    public static function installQueries(): array
    {
        return [
            'ALTER TABLE `'._DB_PREFIX_.'category_lang`
                ADD `extra_desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `meta_description`;',
        ];
    }

    /**
     * Uninstall database queries.
     *
     * @return bool
     */
    public static function uninstallQueries(): array
    {
        return [
            'ALTER TABLE `'._DB_PREFIX_.'category_lang` DROP `extra_desc`;',
        ];
    }
}