<?php
/**
 * 
 * [PC] News : template accueil du site
 * 
 */


/*----------  Dernières actualités  ----------*/

add_action( 'pc_action_home_main_content', 'pc_news_display_home_last_news', 40 );

	function pc_news_display_home_last_news( $pc_home ) {

		$metas = $pc_home->metas;

		// liste
		$home_news = get_posts(array(
			'post_type' => NEWS_POST_SLUG,
			'posts_per_page' => 4
		));
		// titre de la section
		$title = ( isset($metas['content-news-title']) && $metas['content-news-title'] != '' ) ? $metas['content-news-title'] : 'Actualités';

		// affichage des résumés de pages
		if ( count($home_news) > 0 ) {

			echo '<div class="home-news">';
			echo '<h2 class="home-title-sub">'.$title.'</h2>';
			echo '<ul class="st-list st-list--news reset-list">';

			foreach ($home_news as $key => $post) {
		
				// début d'élément
				echo '<li class="st st--news">';

					$pc_post = new PC_Post( $post );

					// affichage résumé
					$pc_post->display_card( 3 );
					
					// données structurée de la liste
					add_filter( 'pc_filter_home_schema_collection_page', function( $schema_collection_page ) use( $pc_post ) {
						$key = count( $schema_collection_page['mainEntity']['itemListElement'] ) + 1;
						$schema_collection_page['mainEntity']['itemListElement'][] = $pc_post->get_schema_list_item( $key );
						return $schema_collection_page;
					} );
		
				// fin d'élément
				echo '</li>';

			}

			echo '</ul>';
			echo '</div>';
		}

	}