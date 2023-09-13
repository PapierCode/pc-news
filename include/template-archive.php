<?php
/**
 * 
 * [PC] News template : template archive
 * 
 */  

 
global $settings_project, $news_query, $news_page_number;

// page en cours (pager)
$news_page_number = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

/*=============================
=            Query            =
=============================*/

$news_query_args = array(
    'post_type' => NEWS_POST_SLUG,
    'posts_per_page' => get_option( 'posts_per_page' ),
    'paged' => $news_page_number
);

$news_query = new WP_Query( $news_query_args );


/*=====  FIN Query  =====*/

/*=================================
=            Affichage            =
=================================*/

if ( $news_query->have_posts() ) {

	global $pc_post;

	// données structurées
	$news_schema = array(
		'@context' => 'http://schema.org/',
		'@type'=> 'CollectionPage',
		'name' => $pc_post->get_seo_meta_title(),
		'headline' => $pc_post->get_seo_meta_title(),
		'description' => $pc_post->get_seo_meta_description(),
		'mainEntity' => array(
			'@type' => 'ItemList',
			'itemListElement' => array()
		),
		'isPartOf' => pc_get_schema_website()
	);
	// compteur position itemListElement
	$news_list_item_key = 1;

	echo '<ul class="st-list st-list--news reset-list">';

	// affichage des articles
    while ( $news_query->have_posts() ) { $news_query->the_post();
		
		// début d'élément
		echo '<li class="st st--news">';

			$news_post = new PC_Post( $news_query->post );

			// affichage résumé
			$news_post->display_card();
			// données structurées
			$news_schema['mainEntity']['itemListElement'][] = $news_post->get_schema_list_item( $news_list_item_key );
			$news_list_item_key++;
		
		// fin d'élément
		echo '</li>';

	}
	
	echo '</ul>';

	// affichage données structurées
	echo '<script type="application/ld+json">'.json_encode($news_schema,JSON_UNESCAPED_SLASHES).'</script>';
	
	// pagination
	add_action( 'pc_action_page_main_footer', 'pc_news_display_archive_pager', 65 );

		function pc_news_display_archive_pager() {
			
			global $news_query, $news_page_number;

			if ( $news_query->found_posts > get_option( 'posts_per_page' ) ) {
				pc_get_pager( $news_query, $news_page_number );
			}
			
		}
    
}
 
 
 /*=====  FIN Affichage  =====*/
 
 // reset query
 wp_reset_postdata();