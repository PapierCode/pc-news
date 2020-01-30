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

/*===========================================================
=            Redirection vers la template single            =
===========================================================*/

add_filter( 'single_template', 'pc_news_add_to_page', 999, 1 );

function pc_news_add_to_page( $single_template ) {

    if ( is_singular( NEWS_POST_SLUG ) ) {
        $single_template = dirname( __FILE__ ).'\template-single.php';
    }

    return $single_template;

}


/*=====  FIN Redirection vers la template single  =====*/

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

/*=========================================================
=            Date et catégories dans le résumé            =
=========================================================*/

add_action( 'pc_display_post_resum_more', 'pc_news_add_to_post_resum', 10, 1 );

    function pc_news_add_to_post_resum( $post_id ) {

        /*----------  date  ----------*/

        echo '<time class="st-date" datetime="'.get_the_date('c',$post_id).'">Publié le '.get_the_date('',$post_id).'</time>';

        /*----------  Taxonomy  ----------*/
    
        if ( taxonomy_exists( NEWS_TAX_SLUG ) ) {
    
            // toutes les taxonomies 'newscategories' attachées au post (tableau d'objets)
            $terms = wp_get_post_terms( $post_id, NEWS_TAX_SLUG, array( "fields" => "all" ) );
    
            // si il y a au moins une tax
            if ( count( $terms ) > 0 ) {
    
                echo '<ul class="st-tax-list">';
                foreach ( $terms as $term_datas ) {
                    echo '<li class="reset-list st-tax-list-item"><a class="st-tax-list-link" href="'.pc_get_page_by_custom_content(NEWS_POST_SLUG).'?'.NEWS_TAX_QUERY_VAR.'='.$term_datas->slug.'" title="Tous les actualités publiées dans '.$term_datas->name.'" rel="nofollow">'.$term_datas->name.'</a></li>';
                }
                echo '</ul>';
    
            }
    
        }

    }


/*=====  FIN Date et catégories dans le résumé  =====*/

/*====================================================
=            Navigation précédent/suivant            =
====================================================*/

// template ajoutée par le plugin mais qui reprend la structure des pages du thème Préformaté

add_action( 'pc_content_footer', 'pc_display_main_footer_news_nav_links', 30, 1 );

function pc_display_main_footer_news_nav_links( $post ) {

    if ( $post->post_type == NEWS_POST_SLUG ) {

        $datas_nav = array(
            '<span>Article </span>Précédent',
            '<span>Article </span>Suivant',
            pc_get_page_by_custom_content(NEWS_POST_SLUG)
        );

        pc_post_navigation( $datas_nav['0'], $datas_nav['1'], $datas_nav['2'] );

    }

}


/*=====  FIN Navigation précédent/suivant  =====*/

/*===========================================================
=            Dernières actualités dans l'accueil            =
===========================================================*/

add_action( 'pc_home_content', 'pc_display_home_content_news', 20, 1 );

function pc_display_home_content_news( $settings_home ) {

    $home_news = get_posts(array(
        'post_type' => NEWS_POST_SLUG,
        'posts_per_page' => $settings_home['content-nbnews']

    ));

    if ( count($home_news) > 0 ) {
        echo '<aside class="">';
            echo '<h2 class="h1-like">'.$settings_home['content-newstitle'].'</h2>';
            echo '<div class="st-list" data-nb="'.$settings_home['content-nbnews'].'">';
                foreach ($home_news as $post) { pc_display_post_resum( $post->ID, '', 3, true ); }
            echo '</div>';
        echo '</aside>';
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