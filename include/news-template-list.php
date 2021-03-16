<?php
/**
 * 
 * Liste des actualités
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

	$post_title = get_the_title( $post->ID );

	// données structurées
	$news_schema = array(
		'@context' => 'http://schema.org/',
		'@type'=> 'CollectionPage',
		'name' => $post_title,
		'headline' => $post_title,
		'description' => pc_get_post_seo_description( $post->ID, $post_metas ),
		'mainEntity' => array(
			'@type' => 'ItemList',
			'itemListElement' => array()
		),
		'isPartOf' => pc_get_schema_website()
	);

	echo '<ul class="st-list st-list--news reset-list">';

	// affichage des actus
    while ( $news_query->have_posts() ) { $news_query->the_post();

		pc_display_post_resum( $news_query->post->ID, 'st--news', 2 );
		// données structurées
		global $post_resum_schema;
		$news_schema['mainEntity']['itemListElement'][] = $post_resum_schema;

	}
	
	echo '</ul>';

	// affichage données structurées
	echo '<script type="application/ld+json">'.json_encode($news_schema,JSON_UNESCAPED_SLASHES).'</script>';
	
	// pagination
	add_action( 'pc_action_page_main_footer', 'pc_news_list_add_pager', 65 );

		function pc_news_list_add_pager() {
			
			global $news_query, $news_page_number;

			if ( $news_query->found_posts > get_option( 'posts_per_page' ) ) {
				pc_get_pager( $news_query, $news_page_number );
			}
			
		}
    
}
 
 
 /*=====  FIN Affichage  =====*/
 
 // reset query
 wp_reset_postdata();