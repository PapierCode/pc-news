<?php

/**
 * 
 * Template single actualité (sur la base de page.php du thème Préformaté)
 * 
 */

get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post(); // Boucle WP (1/2)

$news_metas = get_post_meta( $post->ID );

do_action( 'pc_content_before', $post, $news_metas );

	/*===============================
	=            Contenu            =
	===============================*/
	
	/*----------  Visuel  ----------*/

	if ( isset( $news_metas['thumbnail-img'] ) ) {
		$news_img = pc_get_img( $news_metas['thumbnail-img'][0], 'st', 'datas' );
		echo '<figure class=""><img src="'.$news_img[0].'" alt="'.$news_img[1].'" width="'.$news_img[2].'" height="'.$news_img[3].'" /></figure>';
	} else {
		$news_img = pc_get_default_st( '', 'datas' ); 
	}


	/*----------  Dates  ----------*/ ?>

	<time class="" datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>


	<?php /*----------  Taxonomy  ----------*/ ?>

	<?php if ( taxonomy_exists (NEWS_TAX_SLUG ) ) {

		// toutes les termes attachés au post
		$news_tax = wp_get_post_terms( $post->ID, NEWS_TAX_SLUG, array("fields" => "all" ) );

		// si il y a au moins une tax
		if ( count($news_tax) > 0 ) {

			echo '<ul class="tax-list">';
			foreach ($news_tax as $news_tax_key => $news_tax_value) {
				echo '<li class="reset-list tax-list-item"><a class="tax-list-link" href="'.pc_get_page_by_custom_content(NEWS_POST_SLUG).'?'.NEWS_TAX_QUERY_VAR.'='.$news_tax_value->slug.'" title="Tous les actualités publiées dans '.$news_tax_value->name.'" rel="nofollow">'.$news_tax_value->name.'</a></li>';
			}
			echo '</ul>';
			
		}

	}


	/*----------  Contenu  ----------*/
	
	the_content();


	/*=====  FIN Contenu  =====*/
	
	/*==========================================
	=            Données structurée            =
	==========================================*/ ?>

	<script type="application/ld+json">
		{
			"@context": "http://schema.org",
			"@type": "NewsArticle",
			"url": "<?php the_permalink(); ?>",
			"author": {
				"@type": "Organization",
				"name": "<?= $settings_project['coord-name']; ?>",
				"logo": {
					"@type":"ImageObject",
					"url" : "<?php bloginfo('template_directory'); ?>/images/logo.jpg",
					"width" : "<?= $images_project_sizes['share']['width']; ?>",
					"height" : "<?= $images_project_sizes['share']['height']; ?>"
				}
			},
			"publisher": {
				"@type": "Organization",
				"name": "<?php bloginfo('name'); ?>",
				"logo": {
					"@type":"ImageObject",
					"url" : "<?php bloginfo('template_directory'); ?>/images/logo.jpg",
					"width" : "<?= $images_project_sizes['share']['width']; ?>",
					"height" : "<?= $images_project_sizes['share']['height']; ?>"
				}
			},
			"headline": "<?php the_title(); ?>",
			"image": {
				"@type":"ImageObject",
				"url" : "<?= $news_img[0]; ?>",
				"width" : "<?= $news_img[1]; ?>",
				"height" : "<?= $news_img[2]; ?>"
			},
			"datePublished": "<?= get_the_date('c'); ?>",
			"dateModified": "<?php the_modified_date('c'); ?>",
			"description": "<?= (isset($postResumMetas['resum-desc'])) ? $postResumMetas['resum-desc'][0] : get_the_excerpt(); ?>",
			"mainEntityOfPage": "<?php the_permalink(); ?>"
		}
	</script>

	<?php /*=====  FIN Données structurée  ======*/

do_action( 'pc_content_footer', $post, $news_metas );

do_action( 'pc_content_after', $post, $news_metas );

// Boucle WP (2/2)
endwhile; endif;
// Pied de page
get_footer();