<?php
if (!defined('_PS_VERSION_')) {
    exit;
}



use PrestaShop\PrestaShop\Core\Module\WidgetInterface;


class productsScripts extends Module implements WidgetInterface
{

  public function __construct()
  {



    $this->name = 'productsscripts';

    $this->version = '1.0.0';
    $this->author = 'Jero Soler';
    $this->need_instance = 0;

    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = $this->l('Productos Scripts And CSS');
    $this->description = $this->l('Poner scripts or CSS to products');
    $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    $this->templateFile = 'module:productsscripts/productscriptproducto.tpl';



  }


  public function install()
  {
      if (!parent::install() OR
          !$this->alterTable('add') OR
          !$this->registerHook('actionAdminControllerSetMedia') OR
          !$this->registerHook('actionProductUpdate') OR
          !$this->registerHook('displayFooterProduct') OR
          !$this->registerHook('displayAdminProductsExtra')
          )

          return false;
      return true;
  }

  public function uninstall()
  {
      if (!parent::uninstall() OR !$this->alterTable('remove'))
          return false;
      return true;
  }


  public function alterTable($method)
  {
      switch ($method) {
          case 'add':
              $sql = 'ALTER TABLE  ' . _DB_PREFIX_ . 'product_lang ADD `scripts` TEXT NOT NULL, ADD csss TEXT NOT NULL';
              break;

          case 'remove':
              $sql = 'ALTER TABLE  ' . _DB_PREFIX_ . 'product_lang DROP COLUMN `scripts`, DROP COLUMN `csss` ';
              break;
      }

      if(!Db::getInstance()->Execute($sql))
          return false;
      return true;
  }



  public function renderWidget($hookName = 'displayFooterProduct', array $params)
  {


      if (!$this->isCached($this->templateFile, $this->getCacheId('productsscripts'))) {
          $this->smarty->assign($this->getWidgetVariables($hookName, $params));
      }

      return $this->fetch($this->templateFile, $this->getCacheId('productsscripts'));
  }

  public function getWidgetVariables($hookName = null,  array $params)
  {
    $id_product =  $params['product']['id_product'];

    $sql = 'SELECT scripts, csss FROM `'._DB_PREFIX_.'product_lang` WHERE id_product = '.$id_product.' AND `id_lang` = '.(int)$this->context->language->id.' AND  `id_shop` = '.(int)$this->context->shop->id;

    $texto = Db::getInstance()->getRow($sql);

    return array("searchscripts" => $texto);
  }

/*public function hookDisplayProductContent($params) {
  return $this->display(__FILE__, 'productscriptproducto.tpl');
}*/




  public function hookDisplayAdminProductsExtra($params) {


    $this->context->smarty->assign(
      array(
      "product" => $params['id_product'],
      "languages" => Language::getLanguages(true),
      "default_lang" => (int)Configuration::get('PS_LANG_DEFAULT'),
      "valores" => $this->getdatos($params['id_product']),
      "sum" => 0
      )
  );

  /*
  $this->context->controller->addCSS($this->_path.'editor/codemirror.css', 'all');
  $this->context->controller->addCSS($this->_path.'editor/icecoder.css', 'all');
  $this->context->controller->addJS($this->_path.'editor/codemirror.js', 'all');
  $this->context->controller->addJS($this->_path.'editor/autorefresh.js', 'all');
  $this->context->controller->addJS($this->_path.'editor/javascript.js', 'all');
  $this->context->controller->addJS($this->_path.'editor/css.js', 'all');
*/


return $this->display(__FILE__, 'productsscripts.tpl');

}




  public function getdatos($producto_buscado){

    $sql = 'SELECT scripts, csss FROM `'._DB_PREFIX_.'product_lang` WHERE id_product = '.$producto_buscado.' ORDER BY id_lang';
    $texto = Db::getInstance()->executeS($sql, $array = true);
    return $texto;
  }

  public function hookActionProductUpdate($params)
  {
      // get all languages
      // for each of them, store the new field

      $id_product = (int)Tools::getValue('id_product');

      $languages = Language::getLanguages(true);
      foreach ($languages as $lang) {
          if(!Db::getInstance()->update('product_lang', array('scripts'=> Db::getInstance()->escape(Tools::getValue('scripts_'.$lang['id_lang'].'')), 'csss'=> Db::getInstance()->escape(Tools::getValue('csss_'.$lang['id_lang'].''))) ,'id_lang = ' . $lang['id_lang'] .' AND id_product = ' .$id_product ))
              $this->context->controller->_errors[] = Tools::displayError('Error: ').mysql_error();
      }
      


  }


}
