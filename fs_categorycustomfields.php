<?php

/**
 *  module's main class file
 *  based on tutorial https://devdocs.prestashop.com/1.7/modules/sample-modules/extending-sf-form-with-upload-image-field/#main-module-class
 *  file source: https://github.com/PrestaShop/example-modules/blob/master/demoextendsymfonyform2/demoextendsymfonyform2.php
 */


// since this module is compatible with PS 1.7.7 and later, we
// can use PHP7 strict types because PHP5 support has been dropped for PS 1.7.7
declare(strict_types=1);

// use statements
use PrestaShop\Module\Fs_CategoryCustomFields\Install\Installer;

if (!defined('_PS_VERSION_')) {
    exit;
}
// needed as use Composer to autoload this module
require_once __DIR__.'/vendor/autoload.php';

/**
 * Class demoextendsymfonyform
 */
class Fs_CategoryCustomFields extends Module
{

    public function __construct()
    {
        $this->name = 'fs_categorycustomfields';
        $this->tab = 'front_office_features';
        $this->author = 'Piotr Merton';
        $this->need_instance = 0;
        $this->version = '0.0.1';
        $this->ps_versions_compliancy = ['min' => '1.7.0.0', 'max' => _PS_VERSION_];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Category custom fields');
        $this->description = $this->l(
            'Demonstration of how to add an image upload field inside the Symfony form'
        );

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

    }

    /**
     * @return bool
     */
    public function install()
    {

        if (!parent::install()) {
            return false;
        }
                
        $installer = new Installer();
        return $installer->install($this);
    }

    /**
     * @return bool
     */
    public function uninstall()
    {


        $installer = new Installer();
        return $installer->uninstall() && parent::uninstall();

    }


}