<?php
/**
*
* Création du post Actualité
*
**/


if ( class_exists( 'PC_Add_Custom_Post' ) && class_exists( 'PC_Add_Admin_Page' ) ) {


	/*----------  Labels  ----------*/

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


	/*----------  Configuration  ----------*/

	$news_post_args = array(
		'menu_position'     => 26,
		'menu_icon'         => 'dashicons-megaphone',
		'show_in_nav_menus' => false,
		'supports'          => array( 'title', 'editor' ),
		'rewrite'			=> array( 'slug' => 'news-actualites'),
		'has_archive'		=> false
	);


	/*----------  Déclaration  ----------*/

	$news_post_declaration = new PC_Add_Custom_Post( NEWS_POST_SLUG, $news_post_labels, $news_post_args );


} // FIN if class_exists(PC_Add_Custom_Post)
