<?php  
	/* 
	Name: Page Title Splitter - WooCommerce
	Description: Adjusts the single product page template to allow for html to function on it.
	Author: Chris Steman
	Version: 1.0.0
	*/

  class pt_splitter_woocommerce {
    
    public function __construct() {
    }

    public static function woocommerce_locate_template($template, $template_name, $template_path) {
      global $woocommerce;
      
      $path = str_replace('/class', '', untrailingslashit( plugin_dir_path( __FILE__ ) ) ) . '/includes/woocommerce/';
      $_template = $template;
      if(!$template_path) {
        $template_path = $woocommerce->template_url;
      }
      $template = locate_template( array($template_path.$template_name, $template_name) );

      if(!$template && file_exists($path.$template_name)) {
        $template = $path . $template_name;
      }
      
      if(!$template) {
        $template = $_template;
      }

      return $template;
    }

  }

  add_filter('woocommerce_locate_template', array('pt_splitter_woocommerce', 'woocommerce_locate_template'), 10, 3);
?>