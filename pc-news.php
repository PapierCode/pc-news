<?php

/*
Plugin Name: [PC] News
Plugin URI: www.papier-code.fr
Description: Actualités
Version: 2.3.3
Author: Papier Codé
GitHub Plugin URI: https://github.com/PapierCode/pc-news
*/
   

define( 'NEWS_POST_SLUG', 'news' );	

include 'include/admin.php';

add_action( 'plugins_loaded', 'pc_plugin_news_init' );

	function pc_plugin_news_init() {	

		include 'include/register.php';
		include 'include/fields.php';
		
		include 'include/template-home.php';
		include 'include/template-card.php';
		include 'include/template-single.php';
		include 'include/template-schemas.php';
		include 'include/template-page.php';

	}