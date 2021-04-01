<?php
/**
 * 
 * [PC] News :  champs du post
 * 
 */


/*----------  Reprise WPréform  ----------*/

add_filter( 'pc_filter_metabox_image_for', 'pc_news_edit_metabox_for', 10, 1 );
add_filter( 'pc_filter_metabox_card_for', 'pc_news_edit_metabox_for', 10, 1 );
add_filter( 'pc_filter_metabox_seo_for', 'pc_news_edit_metabox_for', 10, 1 );

    function pc_news_edit_metabox_for( $for ) {

        $for[] = NEWS_POST_SLUG;
        return $for;
        
    }
