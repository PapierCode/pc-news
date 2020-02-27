<?php

/**
*
* Création du post Actualités
*
** Création du post
** Création de la taxonomie
** Métaboxes
*
**/


if ( class_exists( 'PC_Add_Custom_Post' ) && class_exists( 'PC_Add_Admin_Page' ) ) {

	/*========================================
	=            Création du post            =
	========================================*/

	/*----------  Labels  ----------*/

	$post_news_labels = array (
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

	$current_user_role = ( is_user_logged_in() ) ? wp_get_current_user()->roles[0] : '';

	$news_post_args = array(
	    'menu_position'     => 26,
		'menu_icon'         => 'dashicons-megaphone',
		'show_in_nav_menus' => ($current_user_role === 'administrator') ? true : false,
	    'supports'          => array( 'title', 'editor' ),
	    'rewrite'			=> array( 'slug' => 'news-actualites'),
		'taxonomies'		=> array( NEWS_TAX_SLUG ),
		'has_archive'		=> false
	);


	/*----------  Déclaration  ----------*/

	$news_post_declaration = new PC_Add_Custom_Post( NEWS_POST_SLUG, $post_news_labels, $news_post_args );


	/*=====  FIN Création du post  ======*/

	/*================================================
	=            Création de la taxonomie            =
	================================================*/

	/*----------  Labels  ----------*/

	// $news_tax_labels = array(
	// 	'name'                          => 'Catégories',
	// 	'singular_name'                 => 'Catégorie',
	// 	'menu_name'                     => 'Catégories',
	// 	'all_items'                     => 'Toutes les catégories',
	// 	'edit_item'                     => 'Modifier la catégorie',
	// 	'view_item'                     => 'Voir la catégorie',
	// 	'update_item'                   => 'Mettre à jour la catégorie',
	// 	'add_new_item'                  => 'Ajouter une catégorie',
	// 	'new_item_name'                 => 'Ajouter une catégorie',
	// 	'search_items'                  => 'Rechercher une catégorie',
	// 	'popular_items'                 => 'Catégories les plus utilisées',
	// 	'separate_items_with_commas'    => 'Séparer les catégories avec une virgule',
	// 	'add_or_remove_items'           => 'Ajout/supprimer une catégorie',
	// 	'choose_from_most_used'         => 'Choisir parmis les plus utilisées',
	// 	'not_found'                     => 'Aucune catégorie définie'
	// );


	/*----------  Paramètres  ----------*/

	// $news_tax_args = array(
	// 	'show_in_nav_menus' => ($current_user_role === 'administrator') ? true : false,
	// );


	/*----------  Déclaration  ----------*/

	// $news_post_declaration->add_custom_tax( NEWS_TAX_SLUG, $news_tax_labels, $news_tax_args );


	/*----------  Variable de requête  ----------*/

	// add_filter( 'query_vars', 'pc_news_query_vars' );

	// 	function pc_news_query_vars( $vars ){

	// 		$vars[] = NEWS_TAX_QUERY_VAR;
	// 		return $vars;

	// 	}


	/*=====  FIN Création de la taxonomie  ======*/

} // FIN if class_exists(PC_Add_Custom_Post)
