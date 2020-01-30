<?php

/*
Plugin Name: [PC] News
Plugin URI: www.papier-code.fr
Description: Actualités
Version: 1.0.0
Author: Papier Codé
*/


add_action('plugins_loaded', function() { // en attente du plugin [PC] Tools
    
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

    
});