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

 
/*=======================================================
=            Options du plugin (PC Réglages)            =
=======================================================*/

add_filter( 'pc_filter_settings_pc_fields', 'pc_news_edit_settings_pc_fields' );

	function pc_news_edit_settings_pc_fields( $settings_pc_fields ) {

		$settings_pc_fields[] = array(
			'title'     => 'Actualités/Blog',
			'id'        => 'news',
			'prefix'    => 'news',
			'fields'    => array(
				array(
					'type'      => 'radio',
					'label_for' => 'type',
					'label'     => 'Type',
					'default'	=> 'news',
					'options'	=> array(
						'Actualités' => 'news',
						'Blog' => 'blog'
					)
				),
				array(
					'type'      => 'checkbox',
					'label_for' => 'to-pages',
					'label'     => 'Associer aux pages'
				)
			)
		);

		return $settings_pc_fields;

	}


/*=====  FIN Options du plugin (PC Réglages)  =====*/

/*===============================
=            Accueil            =
===============================*/

add_filter( 'pc_filter_settings_home_fields', 'pc_news_edit_settings_home_fields' );

	function pc_news_edit_settings_home_fields( $fields ) {

		global $settings_pc;

		$news_title = array(
			'type'      => 'text',
			'label_for' => 'news-title',
			'label'     => $settings_pc['news-type'] == 'news' ? 'Titre de la section actualités' : 'Titre de la section blog',
			'css'       => 'width:100%'
		);

		$fields[0]['fields'][] = $news_title;

		return $fields;

	}


/*=====  FIN Accueil  =====*/
 
/*======================================
=            Option de page            =
======================================*/
    
add_filter( 'pc_filter_settings_project', 'pc_news_edit_settings_project', 10 );

    function pc_news_edit_settings_project( $settings ) {

		global $settings_pc;

		$settings['page-content-from'][NEWS_POST_SLUG] = array(
			$settings_pc['news-type'] == 'news' ? 'Actualités' : 'Blog',
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
add_action( 'manage_'.NEWS_POST_SLUG.'_posts_columns', 'pc_page_edit_manage_posts_columns', 10, 2);
add_action( 'manage_'.NEWS_POST_SLUG.'_posts_custom_column', 'pc_page_manage_posts_custom_column', 10, 2);


/*=====  FIN Liste d'article  =====*/

/*==========================
=                        =
==========================*/




/*=====  FIN   =====*/