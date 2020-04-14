<?php
/**
 * 
 * Liste des actualités
 * 
 */  

 
global $news_query, $news_page_number;

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

if ( get_query_var( NEWS_TAX_QUERY_VAR ) ) {
    $news_query_args['tax_query'] = array(
        array (
            'taxonomy' => NEWS_TAX_SLUG,
            'field' => 'slug',
            'terms' => get_query_var( NEWS_TAX_QUERY_VAR )
        )
    );
}

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
		'description' => pc_get_page_excerpt( $post->ID, $post_metas ),
		'mainEntity' => array(
			'@type' => 'ItemList',
			'itemListElement' => array()
		),
		'isPartOf' => pc_get_schema_website()
	);
	global $st_schema;

	// affichage des actus
    while ( $news_query->have_posts() ) { $news_query->the_post();

		pc_display_post_resum( $news_query->post->ID, 'st--news', 2 );
		// données structurées
		$news_schema['mainEntity']['itemListElement'][] = $st_schema;

	}
	pc_add_fake_st( count($news_query->posts), 'st--news' );

	// affichage données structurées
	echo '<script type="application/ld+json">'.json_encode($news_schema,JSON_UNESCAPED_SLASHES).'</script>';
	
	// pagination
	add_action( 'pc_page_content_footer', 'pc_display_main_footer_news_pager', 25 );

		function pc_display_main_footer_news_pager() {
			
			global $news_query, $news_page_number;
			echo '<nav class="main-footer-nav">';
			pc_get_pager( $news_query, $news_page_number );
			echo '</nav>';
			
		}
    
}
 
 
 /*=====  FIN Affichage  =====*/
 
 // reset query
 wp_reset_postdata();