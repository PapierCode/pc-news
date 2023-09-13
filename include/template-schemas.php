<?php
/**
 * 
 * [PC] News : données structurées
 * 
 ** Archive
 ** Single
 * 
 */


/*===============================
=            Archive            =
===============================*/

/*----------  Données structurées  ----------*/

add_filter ( 'pc_filter_page_schema_article_display', 'pc_news_edit_archive_schema', 10, 2 ); 

	function pc_news_edit_archive_schema( $display, $pc_post ) {

		$metas = $pc_post->metas;

		if ( isset( $metas['content-from'] ) && $metas['content-from'] == NEWS_POST_SLUG ) {
			$display = false;
		} 
		
		return $display;

	}


/*=====  FIN Archive  =====*/

/*==============================
=            Single            =
==============================*/

add_filter( 'pc_filter_post_schema_article', 'pc_news_edit_single_schema', 10, 2 );

	function pc_news_edit_single_schema( $schema, $pc_post ) {

		if ( NEWS_POST_SLUG == $pc_post->type ) {
			global $settings_pc;
			$schema['@type'] = $settings_pc['news-type'] == 'news' ? 'NewsArticle' : 'BlogPosting';
		}
		if ( isset($post_metas['content-from']) && $post_metas['content-from'][0] == NEWS_POST_SLUG ) {
			// suppression schema article dans la liste des articles
			$schema = array();
		}

		return $schema;

	}

	
/*=====  FIN Single  =====*/
