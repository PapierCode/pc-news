<?php

/*
Plugin Name: [PC] News
Plugin URI: www.papier-code.fr
Description: Actualités
Version: 1.1.0
Author: Papier Codé
*/


add_action( 'plugins_loaded', 'pc_plugin_news_init' );

	function pc_plugin_news_init() {

		/*----------  Constantes  ----------*/    

		define( 'NEWS_POST_SLUG', 'news' );
		

		/*----------  Include  ----------*/

		// création du post
		include 'include/news-post.php';
		// intégration à l'administration du thème préformaté
		include 'include/news-admin.php';
		// intégration aux templates du thème préformaté
		include 'include/news-templates.php';

	}