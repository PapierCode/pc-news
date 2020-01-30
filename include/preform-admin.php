<?php

/**
 * 
 * Intégration à l'administration du thème Préformaté
 * 
 ** Colonnes de la liste des articles
 ** Ajout de l'option dans les pages
 ** Réutilisation des Métaboxes
 ** Options pour la page d'accueil
 * 
 */

 
/*=========================================================
=            Colonnes de la liste des articles            =
=========================================================*/

// reprise de fonction utilisées dans le thème pour afficher une vignette
add_action( 'manage_'.NEWS_POST_SLUG.'_posts_columns', 'pc_admin_list_column_img', 10, 2);
add_action( 'manage_'.NEWS_POST_SLUG.'_posts_custom_column', 'pc_admin_list_column_img_content', 10, 2);

add_filter( 'manage_edit-'.NEWS_TAX_SLUG.'_columns', 'pc_news_tax_columns', 10, 1 );

    function pc_news_tax_columns( $columns ) {
        
        unset( $columns['description'] );
        return $columns;

    }


/*=====  FIN Colonnes de la liste des articles  =====*/
 
/*========================================================
=            Ajout de l'option dans les pages            =
========================================================*/
    
add_filter( 'pc_filter_page_content_from', 'pc_add_news_to_page', 10, 1 );

    function pc_add_news_to_page( $page_content_from ) {

        $page_content_from['news'] = array(
            'Liste des actualités',
            dirname( __FILE__ ).'\template-list.php'
        );

        return $page_content_from;
        
    }


/*=====  FIN Ajout de l'option dans les pages  =====*/

/*===================================================
=            Réutilisation des Métaboxes            =
===================================================*/

// reprise de métaboxes du thème
add_filter( 'pc_filter_metabox_thumbnail_for', 'pc_news_metabox_for', 10, 1 );
add_filter( 'pc_filter_metabox_resum_for', 'pc_news_metabox_for', 10, 1 );
add_filter( 'pc_filter_metabox_seo_for', 'pc_news_metabox_for', 10, 1 );

    function pc_news_metabox_for( $for ) {

        $for[] = NEWS_POST_SLUG;
        return $for;
        
    }


/*=====  FIN Réutilisation des Métaboxes  =====*/

/*======================================================
=            Options pour la page d'accueil            =
======================================================*/

add_filter( 'pc_filter_settings_home_fields', 'pc_news_add_to_settings_home',10 , 1 );

    function pc_news_add_to_settings_home( $fields ) {

        $fields[0]['fields'][] = array(
            'type'      => 'text',
            'label_for' => 'newstitle',
            'label'     => 'Titre des actualités',
            'css'       => 'width:100%',
            'required'  => true
        );
        $fields[0]['fields'][] = array(
            'type'      => 'select',
            'label_for' => 'nbnews',
            'label'     => 'Nombre d\'actualités',
            'required'  => true,
            'options'   => array(
                '2' => '2',
                '4' => '4'
            )
        );

        return $fields;

    }


/*=====  FIN Options pour la page d'accueil  =====*/