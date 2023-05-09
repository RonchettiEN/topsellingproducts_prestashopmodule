<?php
/**
* 2007-2023 PrestaShop
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
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class ProductsOnSale extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'productsonsale';
        $this->tab = 'content_management';
        $this->version = '1.0.0';
        $this->author = 'Ronchetti Ezequiel Nicolás';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Products on sale');
        $this->description = $this->l('Displays x number of products on sale on the home page');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        try {
            Configuration::updateValue('PRODUCTSONSALE_QUANTITY_OF_PRODUCTS_TO_DISPLAY', 10);
    
            include(dirname(__FILE__).'/sql/install.php');
    
            return parent::install() &&
                $this->registerHook('header') &&
                $this->registerHook('displayBackOfficeHeader') &&
                $this->registerHook('displayHome');
        } catch (\Throwable $error) {
            $this->saveError($error);
        }
    }

    public function uninstall()
    {
        try {
            Configuration::deleteByName('PRODUCTSONSALE_QUANTITY_OF_PRODUCTS_TO_DISPLAY');
    
            include(dirname(__FILE__).'/sql/uninstall.php');
    
            return parent::uninstall();
        } catch (\Throwable $error) {
            $this->saveError($error);
        }
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        try {
            /**
             * If values have been submitted in the form, process.
             */
            if (((bool)Tools::isSubmit('submitProductsOnSaleModule')) == true) {
                $this->postProcess();
            }
    
            $this->context->smarty->assign('module_dir', $this->_path);
    
            $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
    
            return $output.$this->renderForm();
        } catch (\Throwable $error) {
            $this->saveError($error);
        }
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        try {
            $helper = new HelperForm();
    
            $helper->show_toolbar = false;
            $helper->table = $this->table;
            $helper->module = $this;
            $helper->default_form_language = $this->context->language->id;
            $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
    
            $helper->identifier = $this->identifier;
            $helper->submit_action = 'submitProductsOnSaleModule';
            $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
                .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
            $helper->token = Tools::getAdminTokenLite('AdminModules');
    
            $helper->tpl_vars = array(
                'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
                'languages' => $this->context->controller->getLanguages(),
                'id_language' => $this->context->language->id,
            );
    
            return $helper->generateForm(array($this->getConfigForm()));
        } catch (\Throwable $error) {
            $this->saveError($error);
        }
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        try {
            return array(
                'form' => array(
                    'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                    ),
                    'input' => array(
                        array(
                            'col' => 3,
                            'type' => 'text',
                            'desc' => $this->l('Enter a number'),
                            'name' => 'PRODUCTSONSALE_QUANTITY_OF_PRODUCTS_TO_DISPLAY',
                            'label' => $this->l('Quantity of products to display'),
                        ),
                    ),
                    'submit' => array(
                        'title' => $this->l('Save'),
                    ),
                ),
            );
        } catch (\Throwable $error) {
            $this->saveError($error);
        }
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        try {
            return array(
                'PRODUCTSONSALE_QUANTITY_OF_PRODUCTS_TO_DISPLAY' => Configuration::get('PRODUCTSONSALE_QUANTITY_OF_PRODUCTS_TO_DISPLAY', 10),
            );
        } catch (\Throwable $error) {
            $this->saveError($error);
        }
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        try {
            $form_values = $this->getConfigFormValues();
    
            foreach (array_keys($form_values) as $key) {
                Configuration::updateValue($key, Tools::getValue($key));
            }
        } catch (\Throwable $error) {
            $this->saveError($error);
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookDisplayBackOfficeHeader()
    {
        try {
            if (Tools::getValue('configure') == $this->name) {
                $this->context->controller->addJS($this->_path.'views/js/back.js');
                $this->context->controller->addCSS($this->_path.'views/css/back.css');
            }
        } catch (\Throwable $error) {
            $this->saveError($error);
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        try {
            $this->context->controller->addJS($this->_path.'/views/js/front.js');
            $this->context->controller->addCSS($this->_path.'/views/css/front.css');
        } catch (\Throwable $error) {
            $this->saveError($error);
        }
    }

    public function hookDisplayHome()
    {
        try {
            $quantity = (int)Configuration::get('PRODUCTSONSALE_QUANTITY_OF_PRODUCTS_TO_DISPLAY');
            $sql = "
                SELECT p.id_product, p.price, p.wholesale_price, sp.reduction, sp.reduction_type, i.id_image
                FROM "._DB_PREFIX_."product p
                JOIN "._DB_PREFIX_."specific_price sp ON p.id_product = sp.id_product
                JOIN "._DB_PREFIX_."image i ON p.id_product = i.id_product AND i.cover = 1
                ORDER BY sp.`from` DESC
                LIMIT $quantity
            ";
            $products_db = Db::getInstance()->executeS($sql);
            $products = [];
            foreach ($products_db as $product_db) {
                $product = new Product($product_db['id_product']);
                $image_url = $this->context->link->getImageLink($product->link_rewrite, $product_db['id_image']);
                $product_url = $this->context->link->getProductLink($product_db['id_product']);
                $price = $product_db['price'] * 1.21;
                switch ($product_db['reduction_type']) {
                    case 'percentage':
                        $price_with_reduction = $price - ($price * (float)$product_db['reduction']);
                        break;
                    case 'amount':
                        $price_with_reduction = $price - (float)$product_db['reduction'];
                        break;
                }
                $products[] = array(
                    'id_product' => $product_db['id_product'],
                    'image_url' => $image_url,
                    'product_url' => $product_url,
                    'price' => str_replace(".",",", round($price,2)),
                    'price_with_reduction' => str_replace(".",",", round($price_with_reduction,2)),
                    'reduction' => (int)($product_db['reduction'] * 100)
                );
            }
            $this->context->smarty->assign(array(
                'products' => $products
            ));
            
            return $this->display(__FILE__, 'views/templates/hook/home.tpl');
        } catch (\Throwable $error) {
            $this->saveError($error);
        }
        
    }

    public function saveError($error)
    {
        $error_type = get_class($error);
        $error_message = str_replace("'","\"",$error->getMessage());
        $error_file = str_replace('\\',"\\\\",$error->getFile());
        $error_line = $error->getLine();

        $query = "INSERT INTO `"._DB_PREFIX_."productsonsale` (`error_type`, `error_message`, `error_in_file`, `error_in_line`) VALUES ('$error_type', '$error_message', '$error_file', '$error_line')";
        Db::getInstance()->execute($query);
    }
}
