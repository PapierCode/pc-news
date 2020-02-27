<?php

/**
 * 
 * Intégration aux templates du thème Préformaté
 * 
 ** Redirection vers la template single
 ** Afficher le filtre en cours
 ** Date et catégories dans le résumé
 ** Navigation précédent/suivant
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
        $single_template = get_template_directory().'\page.php';
    }

    return $single_template;

}


/*=====  FIN Redirection vers la template page  =====*/

/*===================================================
=            Afficher le filtre en cours            =
===================================================*/

add_action( 'pc_content_before', 'pc_news_display_current_filter', 30 );

    function pc_news_display_current_filter() {

        if ( get_query_var( NEWS_TAX_QUERY_VAR ) ) {
            $current_news_cat = get_term_by( 'slug', get_query_var( NEWS_TAX_QUERY_VAR ), NEWS_TAX_SLUG );
            echo '<p>Pour la catégorie <em>'.$current_news_cat->name.'</em></p>';
        }

    }


/*=====  FIN Afficher le filtre en cours  =====*/

/*======================================
=            Date le résumé            =
======================================*/

add_action( 'pc_action_post_resum_after_start', 'pc_news_add_to_post_resum', 10, 1 );

    function pc_news_add_to_post_resum( $post_id ) {

		if ( get_post_type( $post_id ) == 'news' ) {

			echo '<div class="st-details"><time class="st-date" datetime="'.get_the_date('c',$post_id).'">Actualité du '.get_the_date('',$post_id).'</time></div>';

		}

	}
	

/*=====  FIN Date le résumé  =====*/

/*==============================
=            Single            =
==============================*/

/*----------  Date  ----------*/

add_action( 'pc_content_before', 'pc_display_news_main_date', 35, 1 );

	function pc_display_news_main_date( $post ) {

		if ( $post->post_type == NEWS_POST_SLUG ) {

			echo '<time class="news-date" datetime="'.get_the_date('c',$post->ID).'">Actualité du '.get_the_date('',$post->ID).'</time>';

		}

	}


/*----------  Page précédente / retour liste  ----------*/

add_action( 'pc_content_footer', 'pc_display_news_main_footer_nav_links', 30, 1 );

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
	
add_action( 'pc_content_after', 'pc_display_news_structured_datas', 20, 2 );

	function pc_display_news_structured_datas( $post, $metas ) {

		if ( $post->post_type == NEWS_POST_SLUG ) {
		
			global $settings_project, $images_project_sizes;
			$post_id = $post->ID;
			$post_url = get_the_permalink($post_id);
			$theme_dir = get_bloginfo('template_directory');

			if ( isset( $metas['thumbnail-img'] ) ) {
				$post_img = pc_get_img( $metas['thumbnail-img'][0], 'share', 'datas' );
			} else {
				$post_img = array( $theme_dir.'/images/logo.jpg', 300, 300); 
			}
			
			?> <script type="application/ld+json">
				{
					"@context": "http://schema.org",
					"@type": "NewsArticle",
					"url": "<?= $post_url; ?>",
					"author": {
						"@type": "Organization",
						"name": "<?= $settings_project['coord-name']; ?>",
						"logo": {
							"@type":"ImageObject",
							"url" : "<?= $theme_dir; ?>/images/logo.jpg",
							"width" : "<?= $images_project_sizes['share']['width']; ?>",
							"height" : "<?= $images_project_sizes['share']['height']; ?>"
						}
					},
					"publisher": {
						"@type": "Organization",
						"name": "<?= $settings_project['coord-name']; ?>",
						"logo": {
							"@type":"ImageObject",
							"url" : "<?= $theme_dir; ?>/images/logo.jpg",
							"width" : "<?= $images_project_sizes['share']['width']; ?>",
							"height" : "<?= $images_project_sizes['share']['height']; ?>"
						}
					},
					"headline": "<?= get_the_title($post_id); ?>",
					"image": {
						"@type":"ImageObject",
						"url" : "<?= $post_img[0]; ?>",
						"width" : "<?= $post_img[1]; ?>",
						"height" : "<?= $post_img[2]; ?>"
					},
					"datePublished": "<?= get_the_date('c', $post_id); ?>",
					"dateModified": "<?php the_modified_date('c', $post_id); ?>",
					"description": "<?= (isset($metas['resum-desc'])) ? $metas['resum-desc'][0] : get_the_excerpt($post_id); ?>",
					"mainEntityOfPage": "<?= $post_url; ?>"
				}
			</script>
	
		<?php }

	}


/*=====  FIN Single  =====*/

/*===========================================================
=            Dernières actualités dans l'accueil            =
===========================================================*/

add_action( 'pc_home_content', 'pc_display_home_content_news', 20, 1 );

function pc_display_home_content_news( $settings_home ) {

    $home_news = get_posts(array(
        'post_type' => NEWS_POST_SLUG,
        'posts_per_page' => 4

    ));

    if ( count($home_news) > 0 ) {
        foreach ($home_news as $post) { pc_display_post_resum( $post->ID, 'st--news', 3, true ); }
    }

}


/*=====  FIN Dernières actualités dans l'accueil  =====*/

/*=======================================
=            Item menu actif            =
=======================================*/

add_filter( 'wp_nav_menu_objects', 'pc_news_nav_page_parent_active', NULL, 2 );

	function pc_news_nav_page_parent_active( $menu_items, $args ) {

		// si menu d'entête
		if ( $args->theme_location == 'nav-header' ) {

			// si single-news.php
			if ( is_singular( NEWS_POST_SLUG ) ) {

				// page qui publie les actus
				$news_page = pc_get_page_by_custom_content( NEWS_POST_SLUG, 'object' );
				// si la page qui publie les actus a un parent ou pas
				$id_to_search = ( $news_page->post_parent > 0 ) ? $news_page->post_parent : $news_page->ID;

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