<?php
/**
 * 
 * Intégration aux templates du thème Préformaté
 * 
 ** Redirection vers la template page
 ** Résumé (st)
 ** Single
 ** SEO
 ** Dernières actualités dans l'accueil
 ** Item menu actif
 * 
 */

 
/*=========================================================
=            Redirection vers la template page            =
=========================================================*/

add_filter( 'single_template', 'pc_news_single_template', 999, 1 );

	function pc_news_single_template( $single_template ) {

		if ( is_singular( NEWS_POST_SLUG ) ) {
			$single_template = get_template_directory().'/page.php';
		}

		return $single_template;

	}


/*=====  FIN Redirection vers la template page  =====*/

/*==============================
=            Résumé            =
==============================*/

/*----------  Ajout de la date  ----------*/

add_action( 'pc_action_post_resum_after_start', 'pc_news_resum_add_date', 10, 1 );

    function pc_news_resum_add_date( $post_id ) {

		if ( get_post_type( $post_id ) == NEWS_POST_SLUG ) {

			echo '<time class="st-date" datetime="'.get_the_date('c',$post_id).'">Actualité du <span>'.get_the_date('',$post_id).'</span></time>';

		}

	}
	

/*=====  FIN Résumé  =====*/

/*==============================
=            Single            =
==============================*/

/*----------  Date  ----------*/

add_filter( 'pc_the_content_before', 'pc_news_main_add_date_fullscreen' );

	function pc_news_main_add_date_fullscreen( $before ) {

		if ( is_singular( NEWS_POST_SLUG ) ) {

			$post_id = get_the_id();

			$before .= '<p><time class="news-date" datetime="'.get_the_date('c',$post_id).'">Actualité du '.get_the_date('',$post_id).'</time></p>';

		}

		return $before;

	}


/*----------  Page précédente / retour liste  ----------*/

add_action( 'pc_page_content_footer', 'pc_news_main_footer_add_back_link', 30, 1 );

	function pc_news_main_footer_add_back_link( $post ) {

		if ( $post->post_type == NEWS_POST_SLUG ) {

			$wp_referer = wp_get_referer();
			
			if ( $wp_referer ) {
				$back_link = $wp_referer;
			} else {
				$back_link = pc_get_page_by_custom_content( NEWS_POST_SLUG );
			}

			echo '<nav class="main-footer-nav"><a href="'.$back_link.'" class="button" title="Page précédente">'.pc_svg('arrow',null,'svg_block').'<span>Retour</span></a></nav>';

		}

	}


/*=====  FIN Single  =====*/

/*===========================
=            SEO            =
===========================*/

/*----------  Données structurées  ----------*/
	
add_filter( 'pc_filter_schema_post', 'pc_news_edit_schema_type', 10, 3 );

	function pc_news_edit_schema_type( $schema, $post, $post_metas ) {

		if ( $post->post_type == NEWS_POST_SLUG ) {
			$schema['@type'] = 'NewsArticle';
		}
		if ( isset($post_metas['content-from']) && $post_metas['content-from'][0] == NEWS_POST_SLUG ) {
			// suppression schema article dans la liste d'actualités
			$schema = array();
		}

		return $schema;

	}


/*----------  Métas titre & description, image de partage  ----------*/

add_filter( 'pc_filter_seo_metas', 'pc_news_edit_seo_metas' );

	function pc_news_edit_seo_metas( $seo_metas ) {

		$post_id = get_the_id();
		if ( get_post_type( $post_id ) == NEWS_POST_SLUG ) {
			$post_metas = get_post_meta( $post_id );
			$seo_metas = pc_get_post_seo_metas( $seo_metas, $post_id, $post_metas );
		}
		return $seo_metas;

	}


/*=====  FIN SEO  =====*/

/*===========================================================
=            Dernières actualités dans l'accueil            =
===========================================================*/

/*----------  Données structurées  ----------*/

add_filter( 'pc_filter_home_schema_collection_page', 'pc_news_edit_schema_home' );

	function pc_news_edit_schema_home( $schema_collection_page ) {

		// liste
		$news_home_posts = get_posts(array(
			'post_type' => NEWS_POST_SLUG,
			'posts_per_page' => 4
		));

		if ( count($news_home_posts) > 0 ) {
			foreach ($news_home_posts as $key => $post) {
				
				$post_id = $post->ID;
				$post_metas = get_post_meta($post_id);

				if ( isset( $post_metas['visual-id'] ) ) {
					$img = pc_get_img( $post_metas['visual-id'][0], 'share', 'datas' );
				} else {
					$img = pc_get_img_default_to_share();
				}

				// ajout données structurées
				// cf. fn-template_home.php
				$schema_collection_page['mainEntity']['itemListElement'][] = array(
					'@type' => 'ListItem',
					'name' => $post->post_title,
					'description' => pc_get_page_excerpt( $post_id, $post_metas ),
					'url' => get_the_permalink($post_id),
					'image' => array(
						'@type'		=>'ImageObject',
						'url' 		=> $img[0],
						'width' 	=> $img[1],
						'height' 	=> $img[2]
					)
				);

			}
		}

		return $schema_collection_page;

	}


/*----------  Affichage  ----------*/

add_action( 'pc_home_content', 'pc_news_display_inside_home', 80, 1 );

	function pc_news_display_inside_home( $settings_home ) {

		// liste
		$home_news = get_posts(array(
			'post_type' => NEWS_POST_SLUG,
			'posts_per_page' => 4
		));
		// titre de la section
		$title = ( isset($settings_home['content-news-title']) && $settings_home['content-news-title'] != '' ) ? $settings_home['content-news-title'] : 'Actualités';

		// affichage des résumés de pages
		if ( count($home_news) > 0 ) {

			echo '<div class="home-news">';
			echo '<h2 class="home-title-sub">'.$title.'</h2>';
			echo '<ul class="st-list st-list--news reset-list">';

			foreach ($home_news as $key => $post) {
				pc_display_post_resum( $post->ID, 'st--news', 3, true );
			}

			echo '</ul>';
			echo '</div>';
		}

	}


/*=====  FIN Dernières actualités dans l'accueil  =====*/

/*=======================================
=            Item menu actif            =
=======================================*/

add_filter( 'wp_nav_menu_objects', 'pc_news_edit_nav_parent_active', NULL, 2 );

	function pc_news_edit_nav_parent_active( $menu_items, $args ) {

		// si menu d'entête
		if ( $args->theme_location == 'nav-header' ) {

			// si c'est une actualité d'afficher
			if ( is_singular( NEWS_POST_SLUG ) ) {

				// page qui publie les actus
				$post = pc_get_page_by_custom_content( NEWS_POST_SLUG, 'object' );
				if ( $post ) {
					// si la page qui publie les actus a un parent ou pas
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


/*=====  FIN Item menu actif  =====*/