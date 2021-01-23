<?php

/**
 * 
 * Intégration à l'administration du thème Préformaté
 * 
 ** Paramètres de la page d'accueil
 ** Actions groupées
 ** Colonnes de la liste des articles
 ** Ajout de l'option dans les pages
 ** Réutilisation des Métaboxes
 * 
 */


/*=======================================================
=            Paramètres de la page d'accueil            =
=======================================================*/

add_filter( 'pc_filter_settings_home_fields', 'pc_news_admin_edit_settings_home' );

	function pc_news_admin_edit_settings_home( $fields ) {

		$news_title_home_field = array(
			'type'      => 'text',
			'label_for' => 'news-title',
			'label'     => 'Titre des actualités',
			'css'       => 'width:100%'
		);

		$fields[0]['fields'][] = $news_title_home_field;

		return $fields;

	}


/*=====  FIN Paramètres de la page d'accueil  =====*/

/*========================================
=            Actions groupées            =
========================================*/


add_filter( 'bulk_actions-edit-'.NEWS_POST_SLUG, 'pc_news_admin_edit_bluk_actions' );

	function pc_news_admin_edit_bluk_actions( $actions ) {

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
    
add_filter( 'pc_filter_settings_project', 'pc_news_admin_edit_page_content_from' );

    function pc_news_admin_edit_page_content_from( $settings_project ) {

		$settings_project['page-content-from'][NEWS_POST_SLUG] = array(
			'Liste d\'actualités',
			dirname( __FILE__ ).'/news-template-list.php'
		);

        return $settings_project;
        
	}
	
// sauf si déjà publié
add_filter( 'pc_filter_metabox_select_content_from', 'pc_news_admin_edit_page_content_from_one_time', 10, 2 );

	function pc_news_admin_edit_page_content_from_one_time( $metabox_select_content_from, $post ) {

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
add_filter( 'pc_filter_metabox_img_for', 'pc_news_admin_edit_metabox_for', 10, 1 );
add_filter( 'pc_filter_metabox_resum_for', 'pc_news_admin_edit_metabox_for', 10, 1 );
add_filter( 'pc_filter_metabox_seo_for', 'pc_news_admin_edit_metabox_for', 10, 1 );

    function pc_news_admin_edit_metabox_for( $for ) {

        $for[] = NEWS_POST_SLUG;
        return $for;
        
    }


/*=====  FIN Réutilisation des Métaboxes  =====*/