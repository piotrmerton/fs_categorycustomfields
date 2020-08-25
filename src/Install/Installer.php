<?php

/**
 *  module's Installer class file
 *  based on tutorial https://devdocs.prestashop.com/1.7/modules/sample-modules/extending-sf-form-with-upload-image-field/#main-module-class
 *  file source: https://github.com/PrestaShop/example-modules/blob/master/demoextendsymfonyform2/src/Install/Installer.php
 */

declare(strict_types=1);

namespace PrestaShop\Module\Fs_CategoryCustomFields\Install;

use Db;
use Module;
use PrestaShop\Module\Fs_CategoryCustomFields\Sql\SqlQueries;

/**
 * Class Installer
 * @package PrestaShop\Module\DemoExtendSymfonyForm\Install
 */
class Installer
{
    /**
     * Module's installation entry point.
     *
     * @param Module $module
     *
     * @return bool
     */
    public function install(Module $module): bool
    {
        if (!$this->registerHooks($module)) {
            return false;
        }

        if (!$this->installDatabase()) {
            return false;
        }

        return true;
    }

    /**
     * Module's uninstallation entry point.
     *
     * @return bool
     */
    public function uninstall(): bool
    {
        return $this->uninstallDatabase();
    }

    /**
     * Install the database modifications required for this module.
     *
     * @return bool
     */
    private function installDatabase(): bool
    {

        // $queries = [

        //     'ALTER TABLE `'._DB_PREFIX_.'category_lang`
        //         ADD `extra_desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `meta_description`;',
                
        // ];

        return $this->executeQueries(SqlQueries::installQueries());

    }

    /**
     * Uninstall database modifications.
     *
     * @return bool
     */
    private function uninstallDatabase(): bool
    {

        // $queries = [
        //     'ALTER TABLE `'._DB_PREFIX_.'category_lang` DROP `extra_desc`;',
        // ];

        return $this->executeQueries(SqlQueries::uninstallQueries());
    }

    /**
     * Register hooks for the module.
     * list of available hoooks: https://devdocs.prestashop.com/1.7/modules/concepts/hooks/list-of-hooks/
     *
     * @param Module $module
     *
     * @return bool
     */
    private function registerHooks(Module $module): bool
    {
        
        $hooks = [
            'actionAdminCategoriesFormModifier', //this hook is absent on above hooks list, but it is equivalent of actionSupplierFormBuilderModifier that is used in sample code; also it is used in this french source: https://www.h-hennes.fr/blog/2017/06/21/prestashop-ajouter-des-champs-dans-un-formulaire-dadministration/ 
            'actionAdminCategoriesControllerSaveAfter', //absent as well?
        ];

        return (bool) $module->registerHook($hooks);
    }

    /**
     * A helper that executes multiple database queries.
     *
     * @param array $queries
     *
     * @return bool
     */
    private function executeQueries(array $queries): bool
    {
        foreach ($queries as $query) {
            if (!Db::getInstance()->execute($query)) {
                return false;
            }
        }

        return true;
    }
}