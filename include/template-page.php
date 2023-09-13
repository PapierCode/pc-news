<?php

global $settings_pc;
if ( isset($settings_pc['news-to-pages']) ) {

add_action( 'pc_action_page_main_footer', 'pc_news_display_news_to_pages', 110 );

	function pc_news_display_news_to_pages( $pc_post ) {

		$news_to_pages = get_posts( array(
			'post_type' => NEWS_POST_SLUG,
			'posts_per_page' => 4,
			'meta_query' => array(
				array(
					'key'     => 'news_to_pages',
					'value'   => '(^|,)'.$pc_post->id.'(,|$)',
					'compare' => 'REGEXP',
				)
			)
		) );

		if ( count( $news_to_pages ) > 0 ) {
			
			echo '<aside class="page-aside page-aside--news">';

				$title = apply_filters( 'pc_filter_news_to_pages', 'Actualités', $pc_post );
				echo '<h2 class="page-aside-title page-aside-title--news">'.$title.'</h2>';

				echo '<ul class="st-list st-list--news reset-list">';
				foreach ($news_to_pages as $post) {
			
					echo '<li class="st st--news">';
						$pc_post = new PC_Post( $post );
						$pc_post->display_card( 3 );
					echo '</li>';

				}
				echo '</ul>';

			echo '</aside>';

		}

	}

}