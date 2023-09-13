<?php
/**
 * 
 * [PC] News : template single
 * 
 ** Redirection template
 ** Classes CSS
 ** Menu item actif
 ** Contenu
 ** Résultats de recherche
 * 
 */

 
/*=========================================================
=            Redirection vers la template page            =
=========================================================*/

add_filter( 'single_template', 'pc_news_edit_single_template', 999, 1 );

	function pc_news_edit_single_template( $single_template ) {

		if ( is_singular( NEWS_POST_SLUG ) ) {
			$single_template = get_template_directory().'/page.php';
		}

		return $single_template;

	}


/*=====  FIN Redirection vers la template page  =====*/

/*===================================
=            Classes CSS            =
===================================*/

add_filter( 'pc_filter_html_css_class', 'pc_news_edit_single_html_css_class' );

function pc_news_edit_single_html_css_class( $css_classes ) {
	
	if ( is_singular( NEWS_POST_SLUG ) ) {
		$css_classes[] = 'is-page';
		global $settings_pc;
		$css_classes[] = $settings_pc['news-type'] == 'news' ? 'is-news' : 'is-blog';
	}

	return $css_classes;

}


/*=====  FIN Classes CSS  =====*/

/*=======================================
=            Menu item actif            =
=======================================*/

add_filter( 'wp_nav_menu_objects', 'pc_news_edit_single_nav_item_active', NULL, 2 );

function pc_news_edit_single_nav_item_active( $menu_items, $args ) {

	// si menu d'entête
	if ( $args->theme_location == 'nav-header' ) {

		// si c'est un article d'afficher
		if ( is_singular( NEWS_POST_SLUG ) ) {

			// page qui publie les articles (archive)
			$post = pc_get_page_by_custom_content( NEWS_POST_SLUG, 'object' );
			if ( $post ) {
				// si la page qui publie les articles a un parent ou pas
				$id_to_search = ( $post->post_parent > 0 ) ? $post->post_parent : $post->ID;
			}

		}
		
		// recherche de l'item
		if ( isset($id_to_search) ) {

			foreach ( $menu_items as $object ) {
				if ( $object->object_id == $id_to_search ) {
					// ajout classe WP (remplacée dans le Walker du menu)
					$object->classes[] = 'current-menu-item';
				}
			}

		}

	}

	return $menu_items;

};


/*=====  FIN Menu item actif  =====*/

/*====================================
=            Fil d'ariane            =
====================================*/

add_filter( 'pc_filter_breadcrumb', 'pc_news_edit_breadcrumb' );

	function pc_news_edit_breadcrumb( $links ) {

		if ( is_singular( NEWS_POST_SLUG ) ) {

			$post = pc_get_page_by_custom_content( NEWS_POST_SLUG, 'object' );
			$pc_post = new PC_Post( $post );

			$links[] = array(
				'name' => $pc_post->get_card_title(),
				'permalink' => $pc_post->permalink
			);

		}

		return $links;

	}


/*=====  FIN Fil d'ariane  =====*/

/*===============================
=            Contenu            =
===============================*/
	
/*----------  Date  ----------*/

add_action( 'pc_action_page_main_header', 'pc_news_display_single_date', 35 );

	function pc_news_display_single_date( $pc_post ) {

		if ( NEWS_POST_SLUG == $pc_post->type ) {

			echo '<p class="single-date single-date--news">';
				echo '<span class="ico">'.pc_svg('calendar').'</span>';
				echo '<time class="txt news-date" datetime="'.$pc_post->get_date('c').'">Article publié le '.$pc_post->get_date().'</time>';
			echo '</p>';

		}

	}


/*----------  Page précédente / retour liste  ----------*/

add_action( 'pc_action_page_main_footer', 'pc_news_display_single_backlink', 20 );

	function pc_news_display_single_backlink( $pc_post ) {

		if ( $pc_post->type == NEWS_POST_SLUG ) {

			$wp_referer = wp_get_referer();
			
			if ( $wp_referer ) {
				$back_link = $wp_referer;
				$back_title = 'Page précédente';
				$back_txt = 'Retour';
				$back_ico = 'arrow';
			} else {
				$back_link = pc_get_page_by_custom_content( NEWS_POST_SLUG );
				$back_title = 'Tous les articles';
				$back_txt = 'd\'articles';
				$back_ico = 'more';
			}

			echo '<div class="main-footer-prev"><a href="'.$back_link.'" class="button" title="'.$back_title.'"><span class="ico">'.pc_svg($back_ico).'</span><span class="txt">'.$back_txt.'</span></a></div>';

		}

	}


/*=====  FIN Contenu  =====*/

/*==============================================
=            Résultats de recherche            =
==============================================*/

add_filter( 'pc_filter_search_results_type', 'pc_news_edit_search_results_type' );

	function pc_news_edit_search_results_type( $types ) {

		global $settings_pc;
		$types[NEWS_POST_SLUG] = $settings_pc['news-type'] == 'news' ? 'Actualité' : 'Blog';
		return $types;

	}


/*=====  FIN Résultats de recherche  =====*/