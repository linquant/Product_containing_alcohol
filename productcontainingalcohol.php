<?php
/**
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once dirname(__FILE__) . '/models/alcool.php';


class Productcontainingalcohol extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'productcontainingalcohol';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Linquant';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Product containing alcohol');
        $this->description = $this->l('informs customers of the presence of alcohol in products ');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('PRODUCTCONTAININGALCOHOL_LIVE_MODE', false);

        include(dirname(__FILE__) . '/sql/install.php');

        return parent::install() &&
              $this->registerHook('header') &&
        $this->registerHook('actionProductSave') &&
        $this->registerHook('displayProductAdditionalInfo') &&
        $this->registerHook('displayAdminProductsMainStepLeftColumnMiddle');
    }

    public function uninstall()
    {
        Configuration::deleteByName('PRODUCTCONTAININGALCOHOL_LIVE_MODE');

        include(dirname(__FILE__) . '/sql/uninstall.php');

        return parent::uninstall();
    }

   

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookdisplayAdminProductsMainStepLeftColumnMiddle($params)
    {
        $datas = array( 'etat' =>  Alcool::alcoolGet($params['id_product']));


        $this->context->smarty->assign($datas);

        return $this->display(__FILE__, 'views/templates/admin/alcohol.tpl');
    }

    public function hookactionProductSave($product)
    {

//  Récupération de l id produit
        $id_product = (int)$product['id_product'];

        //  Récupération de form alcool

        if (tools::getValue('alcool') && tools::getValue('alcool') == "on") {
            $alcool = true;
        } else {
            $alcool = false;
        }
        //  Sauvegarde des données en base

        Alcool::alcoolSave($id_product, $alcool);
    }

    public function hookdisplayProductAdditionalInfo()
    {
        $id_product = (int)Tools::getValue('id_product');


        $datas = array( 'etat' => Alcool::alcoolGet($id_product) );

        $this->context->smarty->assign($datas);

        return $this->display(__FILE__, 'views/templates/front/productalcohol.tpl');
    }
}
