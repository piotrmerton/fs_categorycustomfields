<?php

/**
 *  Modules's main Class file
 *  based on tutorial https://devdocs.prestashop.com/1.7/modules/sample-modules/extending-sf-form-with-upload-image-field/#main-module-class
 *  file source: https://github.com/PrestaShop/example-modules/blob/master/demoextendsymfonyform2/demoextendsymfonyform2.php
 */


// since this module is compatible with PS 1.7.7 and later, we
// can use PHP7 strict types because PHP5 support has been dropped for PS 1.7.7
declare(strict_types=1);

// use statements
use PrestaShop\Module\Fs_CategoryCustomFields\Install\Installer;
use Symfony\Component\Form\FormBuilderInterface;
use PrestaShopBundle\Form\Admin\Type\FormattedTextareaType;

if (!defined('_PS_VERSION_')) {
    exit;
}
// needed as use Composer to autoload this module
require_once __DIR__.'/vendor/autoload.php';

/**
 * Class Fs_CategoryCustomFields
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
        $this->ps_versions_compliancy = ['min' => '1.7.7.6', 'max' => _PS_VERSION_];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->getTranslator()->trans('Category custom fields', array(), 'Modules.CategoryCustomFields.Admin');
        $this->description = $this->getTranslator()->trans('Adds extra field to Category for SEO purposes.', array(), 'Modules.CategoryCustomFields.Admin');
        $this->confirmUninstall = $this->getTranslator()->trans('Are you sure you want to uninstall? Uninstalling will also remove data from Database.', array(), 'Modules.CategoryCustomFields.Admin');

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

    /**
     * Adding new field to Category form based on devdoc tutorial
     * source: https://devdocs.prestashop.com/1.7/modules/sample-modules/grid-and-identifiable-object-form-hooks-usage/#adding-new-form-field-to-customer-form
     * 
     * @param array $params
     */
    public function hookActionCategoryFormBuilderModifier(array $params) {

        $id_category = $params['id'];
        $translator = $this->getTranslator();

        /** 
         * formBuilder Symfony object
         * https://devdocs.prestashop.com/1.7/development/components/form/types-reference/
         * https://devdocs.prestashop.com/1.7/development/components/form/types-reference/translatable/
         * https://devdocs.prestashop.com/1.7/development/components/form/types-reference/formatted-textarea/
         * https://symfony.com/doc/current/forms.html
         * 
         * @var FormBuilderInterface $formBuilder 
         * 
         * */
        $formBuilder = $params['form_builder'];
        $formBuilder
            ->add('extra_desc', FormattedTextareaType::class, [
                'label' => $translator->trans('Extra description for SEO', [], 'Modules.Fs_CategoryCustomFields'),
                'required' => false,
            ]);
        /**
         * TO DO: make texarea translatable - TranslateType is implied to use alongside FormattedTextareaType, but undocumented as of 25-08;
         * source: https://devdocs.prestashop.com/1.7/development/components/form/types-reference/translatable/
         */
            
        $params['data']['extra_desc'] = $this->getCategoryExtraData($id_category);

        $formBuilder->setData($params['data']);

    }


    /**
     * Retrieve current category data to populate form
     */
    private function getCategoryExtraData(int $id_category) {
        $category = new Category( $id_category, $this->context->language->id );
        return $category->extra_desc;
    }

    /**
     * Hook allows to modify Category form and add additional form fields as well as modify or add new data to the forms.
     * @param array $params
     */    
    public function hookActionAfterCreateCategoryFormHandler(array $params)
    {
        $this->updateCategoryData($params);
    }

    /**
     * Hook allows to modify Category form and add additional form fields as well as modify or add new data to the forms.
     * @param array $params
     */    
    public function hookActionAfterUpdateCategoryFormHandler(array $params)
    {
        $this->updateCategoryData($params);
    }

    /**
     * Update Database with form data
     */
    protected function updateCategoryData(array $params)
    {
        /** based on demo module and similar extension of Customer Object:
         * https://github.com/PrestaShop/example-modules/tree/master/demoextendsymfonyform1
         * https://github.com/wfpaisa/prestashop-custom-field/blob/master/ps_customercedula.php */
        
        $categoryFormData = $params['form_data'];

        try {

            $category = new Category((int)$params['id']);
            $category->extra_desc = $categoryFormData['extra_desc'];
            $category->update();

        } catch (Exception $exception) {
            throw new \PrestaShop\PrestaShop\Core\Module\Exception\ModuleErrorException($exception);
        }        

    }    


}