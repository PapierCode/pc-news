<?php

/**
 * 
 * Intégration à l'administration du thème Préformaté
 * 
 ** Actiosn groupées
 ** Colonnes de la liste des articles
 ** Ajout de l'option dans les pages
 ** Réutilisation des Métaboxes
 * 
 */


/*=======================================================
=            Paramètres de la page d'accueil            =
=======================================================*/

add_filter( 'pc_filter_settings_home_fields', 'pc_news_home_settings' );

	function pc_news_home_settings( $settings_home_fields ) {

		$settings_home_fields[0]['fields'][] = array(
			'type'      => 'text',
			'label_for' => 'news-title',
			'label'     => 'Titre des actualités',
			'css'       => 'width:100%'
		);

		return $settings_home_fields;

	}


/*=====  FIN Paramètres de la page d'accueil  =====*/

/*========================================
=            Actions groupées            =
========================================*/


add_filter( 'bulk_actions-edit-'.NEWS_POST_SLUG, 'pc_news_bluk_actions' );

	function pc_news_bluk_actions( $actions ) {

		unset($actions['edit']);
		return $actions;

	}


/*=====  FIN Actions groupées  =====*/

/*=========================================================
=            Colonnes de la liste des articles            =
=========================================================*/

// reprise de fonctions utilisées dans le thème pour afficher une vignette
add_action( 'manage_'.NEWS_POST_SLUG.'_posts_columns', 'pc_admin_list_column_img', 10, 2);
add_action( 'manage_'.NEWS_POST_SLUG.'_posts_custom_column', 'pc_admin_list_column_img_content', 10, 2);


/*=====  FIN Colonnes de la liste des articles  =====*/
 
/*========================================================
=            Ajout de l'option dans les pages            =
========================================================*/
    
add_filter( 'pc_filter_page_content_from', 'pc_add_news_to_page', 10, 1 );

    function pc_add_news_to_page( $page_content_from ) {

		$page_content_from['news'] = array(
			'Liste d\'actualités',
			dirname( __FILE__ ).'/template-list.php'
		);

        return $page_content_from;
        
	}
	
add_filter( 'pc_filter_metabox_select_content_from', 'pc_news_list_display_one_time', 10, 2 );

	function pc_news_list_display_one_time( $metabox_select_content_from, $post ) {

		$post_news_list = pc_get_page_by_custom_content( NEWS_POST_SLUG, 'object' );

		if( is_object( $post_news_list ) && $post_news_list->ID != $post->ID ) {

			unset( $metabox_select_content_from[NEWS_POST_SLUG] );

		}

		return $metabox_select_content_from;

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