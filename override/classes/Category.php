<?php

/** 
 * extends core Category object with custom properties
 * TO DO: don't use override and create separate Db Table for SEO?
 */

class Category extends CategoryCore {
    
    public $extra_desc;

    public function __construct($idCategory = null, $idLang = null, $idShop = null) {

        self::$definition['fields']['extra_desc'] = array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml');      

        parent::__construct($idCategory, $idLang, $idShop);

    }
}