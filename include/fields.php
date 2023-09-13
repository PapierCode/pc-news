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

/*----------  Associer aux pages  ----------*/

global $settings_pc;
if ( isset($settings_pc['news-to-pages']) ) {

	add_action( 'add_meta_boxes', 'pc_news_custom_metabox' );

		function pc_news_custom_metabox() {

			add_meta_box(
				'news-to-pages',
				'Associer aux pages',
				'pc_news_metabox_to_pages_content',
				NEWS_POST_SLUG,
				'normal',
				'high'
			);

		}

	function pc_news_metabox_to_pages_content( $post ) {

		wp_nonce_field( basename( __FILE__ ), 'none-news-to-pages-metaboxe' );

		echo '<table class="form-table"><tbody>';
			echo '<tr><th><label>Sélection</label></th><td>';

				$query_args = array(
					'post_type' => 'page',
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'orderby' => 'title',
					'order' => 'ASC',
					'post__not_in' => array( get_option( 'wp_page_for_privacy_policy' ) ) // page CGU 
				);

				// liste des articles
				$news_page = pc_get_page_by_custom_content( NEWS_POST_SLUG, 'object' );
				if ( is_object( $news_page ) ) { $query_args['post__not_in'][] = $news_page->ID; }

				if ( count( $query_args ) > 0 ) {

					$field_name = 'news_to_pages';
					$field_value = get_post_meta( $post->ID, $field_name, true );
					$repeater = new PC_Posts_Selector( $field_name, $field_value, $query_args );
					echo $repeater->display();

				} else { echo '<p>Aucune page disponible !</p>';	}

			echo '</td></tr>';
		echo '</tbody></table>';

	}

	add_action( 'save_post', 'pc_news_metabox_to_pages_save' );

	function pc_news_metabox_to_pages_save( $post_id ) {

		// check input hidden de vérification
		if ( isset($_POST['none-news-to-pages-metaboxe']) && wp_verify_nonce( $_POST['none-news-to-pages-metaboxe'], basename( __FILE__ ) ) ) {

			$temp = $_POST['news_to_pages'];
			$save = get_post_meta( $post_id, 'news_to_pages', true );
			
			// si une valeur arrive & si rien en bdd
			if ( $temp && '' == $save ) {
				add_post_meta( $post_id, 'news_to_pages', $temp, true );

			// si une valeur arrive & différente de la bdd
			} elseif ( $temp && $temp != $save ) {
				update_post_meta( $post_id, 'news_to_pages', $temp );

			// si rien n'arrive & si un truc en bdd
			} elseif ( '' == $temp && $save ) {
				delete_post_meta( $post_id, 'news_to_pages' );
			}

		}

	}

}