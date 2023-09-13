<?php
/**
*
* [PC] News : création du post
*
**/


if ( class_exists( 'PC_Add_Custom_Post' ) ) {


	/*----------  Labels  ----------*/

	global $settings_pc;

	if ( $settings_pc['news-type'] == 'news' ) {

		$news_post_labels = array (
			'name'                  => 'Actualités',
			'singular_name'         => 'Actualité',
			'menu_name'             => 'Actualités',
			'add_new'               => 'Ajouter une actualité',
			'add_new_item'          => 'Ajouter une actualité',
			'new_item'              => 'Ajouter une actualité',
			'edit_item'             => 'Modifier l\'actualité',
			'all_items'             => 'Toutes les actualités',
			'not_found'             => 'Aucune actualité'
		);

		$slug = 'news-actualites';

	} else {

		$news_post_labels = array (
			'name'                  => 'Blog',
			'singular_name'         => 'Article',
			'menu_name'             => 'Blog',
			'add_new'               => 'Ajouter un article',
			'add_new_item'          => 'Ajouter un article',
			'new_item'              => 'Ajouter un article',
			'edit_item'             => 'Modifier l\'article',
			'all_items'             => 'Tous les articles',
			'not_found'             => 'Aucun article'
		);

		$slug = 'cpt-blog';

	}


	/*----------  Configuration  ----------*/

	$news_post_args = array(
		'menu_position'     => 26,
		'menu_icon'         => 'dashicons-megaphone',
		'show_in_rest' 		=> true,
		'show_in_nav_menus' => false,
		'supports'          => array( 'title', 'editor' ),
		'rewrite'			=> array( 'slug' => $slug ),
		'has_archive'		=> false
	);

	if ( class_exists( 'Classic_Editor' ) ) { unset( $news_post_args['show_in_rest'] ); }


	/*----------  Déclaration  ----------*/

	$news_post_declaration = new PC_Add_Custom_Post( NEWS_POST_SLUG, $news_post_labels, $news_post_args );


} // FIN if class_exists(PC_Add_Custom_Post)
