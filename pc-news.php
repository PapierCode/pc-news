<?php

/*
Plugin Name: [PC] News
Plugin URI: www.papier-code.fr
Description: Actualités
Version: 1.0.2
Author: Papier Codé
*/


add_action( 'setup_theme', 'pc_plugin_news_init' );

	function pc_plugin_news_init() {

		/*----------  Constantes  ----------*/    

		define( 'NEWS_POST_SLUG', 'news' );
		define( 'NEWS_TAX_SLUG', 'newstax' );
		define( 'NEWS_TAX_QUERY_VAR', 'actucat' );


		/*----------  Include  ----------*/

		// création du post
		include 'include/post.php';
		// intégration à l'administration du thème préformaté
		include 'include/preform-admin.php';
		// intégration aux templates du thème préformaté
		include 'include/preform-templates.php';

	}