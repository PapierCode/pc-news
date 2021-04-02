<?php

/**
 * 
 * [PC] News : administration
 * 
 ** Accueil
 ** Option de page
 ** Liste d'article
 * 
 */


/*===============================
=            Accueil            =
===============================*/

add_filter( 'pc_filter_settings_home_fields', 'pc_news_edit_settings_home_fields' );

	function pc_news_edit_settings_home_fields( $fields ) {

		$news_title = array(
			'type'      => 'text',
			'label_for' => 'news-title',
			'label'     => 'Titre des actualités',
			'css'       => 'width:100%'
		);

		$fields[0]['fields'][] = $news_title;

		return $fields;

	}


/*=====  FIN Accueil  =====*/
 
/*======================================
=            Option de page            =
======================================*/
    
add_filter( 'pc_filter_settings_project', 'pc_news_edit_settings_project' );

    function pc_news_edit_settings_project( $settings ) {

		$settings['page-content-from'][NEWS_POST_SLUG] = array(
			'Liste d\'actualités',
			dirname( __FILE__ ).'/template-archive.php'
		);

        return $settings;
        
	}
	
// sauf si déjà publié
add_filter( 'pc_filter_page_metabox_select_content_from', 'pc_news_edit_page_metabox_select_content_from', 10, 2 );

	function pc_news_edit_page_metabox_select_content_from( $select, $post ) {

		$news_archive = pc_get_page_by_custom_content( NEWS_POST_SLUG, 'object' );

		if( is_object( $news_archive ) && $news_archive->ID != $post->ID ) {

			unset( $select[NEWS_POST_SLUG] );

		}

		return $select;

	}


/*=====  FIN Archive (option de page)  =====*/

/*=======================================
=            Liste d'article            =
=======================================*/

/*----------  Actions groupées  ----------*/

add_filter( 'bulk_actions-edit-'.NEWS_POST_SLUG, 'pc_news_edit_bluk_actions' );

	function pc_news_edit_bluk_actions( $actions ) {

		unset($actions['edit']);
		return $actions;

	}

/*----------  Colonne visuel  ----------*/

// reprise WPréform
add_action( 'manage_'.NEWS_POST_SLUG.'_posts_columns', 'pc_admin_list_column_img', 10, 2);
add_action( 'manage_'.NEWS_POST_SLUG.'_posts_custom_column', 'pc_admin_list_column_img_content', 10, 2);


/*=====  FIN Liste d'article  =====*/