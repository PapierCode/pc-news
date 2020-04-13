<?php
/**
 * 
 * Intégration aux templates du thème Préformaté
 * 
 ** Redirection vers la template page
 ** Afficher le filtre en cours
 ** Date dans le résumé
 ** Single
 ** Dernières actualités dans l'accueil
 ** Item menu actif
 * 
 */

 
/*=========================================================
=            Redirection vers la template page            =
=========================================================*/

add_filter( 'single_template', 'pc_news_add_to_page', 999, 1 );

function pc_news_add_to_page( $single_template ) {

    if ( is_singular( NEWS_POST_SLUG ) ) {
        //$single_template = dirname( __FILE__ ).'\template-single.php';
        $single_template = get_template_directory().'/page.php';
    }

    return $single_template;

}


/*=====  FIN Redirection vers la template page  =====*/

/*======================================
=            Date le résumé            =
======================================*/

add_action( 'pc_action_post_resum_after_start', 'pc_news_add_to_post_resum', 10, 1 );

    function pc_news_add_to_post_resum( $post_id ) {

		if ( get_post_type( $post_id ) == 'news' ) {

			echo '<time class="st-date" datetime="'.get_the_date('c',$post_id).'">Actualité du <span>'.get_the_date('',$post_id).'</span></time>';

		}

	}
	

/*=====  FIN Date le résumé  =====*/

/*==============================
=            Single            =
==============================*/

/*----------  Date  ----------*/

add_action( 'pc_page_content_before', 'pc_display_news_main_date', 35, 1 );

	function pc_display_news_main_date( $post ) {

		if ( $post->post_type == NEWS_POST_SLUG ) {

			echo '<time class="news-date" datetime="'.get_the_date('c',$post->ID).'">Actualité du '.get_the_date('',$post->ID).'</time>';

		}

	}


/*----------  Page précédente / retour liste  ----------*/

add_action( 'pc_page_content_footer', 'pc_display_news_main_footer_nav_links', 30, 1 );

	function pc_display_news_main_footer_nav_links( $post ) {

		if ( $post->post_type == NEWS_POST_SLUG ) {

			$wp_referer = wp_get_referer();
			
			if ( $wp_referer ) {
				$back_link = $wp_referer;
			} else {
				$back_link = pc_get_page_by_custom_content(NEWS_POST_SLUG);
			}

			echo '<nav class="main-footer-nav"><a href="'.$back_link.'" class="btn" title="Page précédente">'.pc_svg('arrow',null,'svg_block').'<span>Retour</span></a></nav>';

		}

	}


/*----------  Données structurées  ----------*/
	
add_filter( 'pc_filter_schema_post', 'pc_news_schema', 10, 3 );

	function pc_news_schema( $schema, $post, $post_metas ) {

		if ( $post->post_type == NEWS_POST_SLUG ) {
			$schema['@type'] = 'NewsArticle';
		}
		if ( isset($post_metas['content-from']) && $post_metas['content-from'][0] == 'news' ) {
			// suppression schema article dans la liste d'actualités
			$schema = array();
		}

		return $schema;

	}


/*----------  Métas titre & description, image de partage, custom CSS  ----------*/

add_filter( 'pc_filter_meta_title', 'pc_news_meta_title', 1 );

	function pc_news_meta_title( $meta_title ) {

		$post_id = get_the_id();
		if ( get_post_type( $post_id ) == NEWS_POST_SLUG ) {
			$post_metas = get_post_meta( $post_id );
			$meta_title = ( isset( $post_metas['seo-title'] ) ) ? $post_metas['seo-title'][0] : get_the_title($post_id);
		}

		return $meta_title;

	}

add_filter( 'pc_filter_meta_description', 'pc_news_meta_description', 1 );

	function pc_news_meta_description( $meta_description ) {

		$post_id = get_the_id();
		if ( get_post_type( $post_id ) == NEWS_POST_SLUG ) {
			$post_metas = get_post_meta( $post_id );
			$meta_description = pc_get_page_excerpt( $post_id, $post_metas );
		}

		return $meta_description;

	}

add_filter( 'pc_filter_img_to_share', 'pc_news_img_to_share', 1 );

	function pc_news_img_to_share( $img_to_share ) {

		$post_id = get_the_id();
		if ( get_post_type( $post_id ) == NEWS_POST_SLUG ) {
			$post_metas = get_post_meta( $post_id );
			if ( isset( $post_metas['thumbnail-img'] ) ) {
				$img_to_share = wp_get_attachment_image_src($post_metas['thumbnail-img'][0],'share')[0];
			}
		}

		return $img_to_share;

	}

add_filter( 'pc_filter_css_custom', 'pc_news_css_custom', 1 );

	function pc_news_css_custom( $css_custom ) {

		$post_id = get_the_id();
		if ( get_post_type( $post_id ) == NEWS_POST_SLUG ) {
			global $settings_project;
			$post_metas = get_post_meta( $post_id );
			if ( isset( $post_metas['thumbnail-img'] ) ) {
				if ( $settings_project['theme'] == 'fullscreen' ) { $css_custom .= pc_fs_main_header_css_bg($post_metas['thumbnail-img'][0]); }
			}
		}

		return $css_custom;

	}
	

/*=====  FIN Single  =====*/

/*===========================================================
=            Dernières actualités dans l'accueil            =
===========================================================*/

/*----------  Données structurées  ----------*/

add_filter( 'pc_filter_schema_collection_page_home', 'pc_home_news_schema' );

	function pc_home_news_schema( $collection_page ) {

		// liste
		$home_news = get_posts(array(
			'post_type' => NEWS_POST_SLUG,
			'posts_per_page' => 4
		));

		if ( count($home_news) > 0 ) {
			foreach ($home_news as $key => $post) {
				
				$post_id = $post->ID;
				$post_metas = get_post_meta($post_id);

				if ( isset( $post_metas['thumbnail-img'] ) ) {
					$img = pc_get_img( $post_metas['thumbnail-img'][0], 'share', 'datas' );
				} else {
					$img = pc_get_img_default_to_share();
				}

				// ajout données structurées
				// cf. fn-template_home.php
				$collection_page['mainEntity']['itemListElement'][] = array(
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

		return $collection_page;

	}


/*----------  Affichage  ----------*/

add_action( 'pc_home_content', 'pc_display_home_content_news', 20, 1 );

	function pc_display_home_content_news( $settings_home ) {

		// liste
		$home_news = get_posts(array(
			'post_type' => NEWS_POST_SLUG,
			'posts_per_page' => 4
		));
		// titre de la section
		$title = ( isset($settings_home['content-news-title']) && $settings_home['content-news-title'] != '' ) ? $settings_home['content-news-title'] : 'Actualités';
		// affichage des résumés de pages
		if ( count($home_news) > 0 ) {
			echo '<h2 class="home-news-title fs-bloc">'.$title.'</h2>';
			foreach ($home_news as $key => $post) {
				pc_display_post_resum( $post->ID, 'st--news', 3, true );
			}
		}
		pc_add_fake_st( count($home_news) );

	}


/*=====  FIN Dernières actualités dans l'accueil  =====*/

/*=======================================
=            Item menu actif            =
=======================================*/

add_filter( 'wp_nav_menu_objects', 'pc_news_nav_page_parent_active', NULL, 2 );

	function pc_news_nav_page_parent_active( $menu_items, $args ) {

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