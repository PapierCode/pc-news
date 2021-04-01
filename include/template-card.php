<?php
/**
 * 
 * [PC] News : template résumé
 * 
 */


/*----------  Date  ----------*/

add_action( 'pc_post_card_after_start', 'pc_news_display_card_date', 10 );

    function pc_news_display_card_date( $pc_post ) {

		if ( NEWS_POST_SLUG == $pc_post->type ) {

			echo '<time class="st-date" datetime="'.$pc_post->get_date('c').'">Actualité du <span>'.$pc_post->get_date().'</span></time>';

		}

	}