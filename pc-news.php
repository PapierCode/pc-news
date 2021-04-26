<?php

/*
Plugin Name: [PC] News
Plugin URI: www.papier-code.fr
Description: Actualités
Version: 2.0.5
Author: Papier Codé
*/


add_action( 'plugins_loaded', 'pc_plugin_news_init' );

	function pc_plugin_news_init() {

		/*----------  Constantes  ----------*/    

		define( 'NEWS_POST_SLUG', 'news' );
		

		/*----------  Include  ----------*/

		include 'include/register.php';
		include 'include/fields.php';

		include 'include/admin.php';
		
		include 'include/template-home.php';
		include 'include/template-card.php';
		include 'include/template-single.php';
		include 'include/template-schemas.php';

	}