<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since Twenty Nineteen 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( ! twentynineteen_can_show_post_thumbnail() ) : ?>
	<header class="entry-header">
		<?php get_template_part( 'template-parts/header/entry', 'header' ); ?>
	</header>
	<?php endif; ?>
	
	<nav class="pagination-single section-inner load-podcast" aria-label="Post" role="navigation">
		<div class="entry-content">
			<hr class="styled-separator is-style-wide" aria-hidden="true">

			<div class="pagination-single-inner" id="recommended-listening">
				
				
				<span class="nav-subtitle">
				<?php echo twentynineteen_get_icon_svg( 'audio', 64 ); ?>
				<h3 class="recommended-title">Recommended Listening:</h3>
				</span>
				<div class="nav-links">
					<div class="nav-podcast">

						<?php 
							$ids = get_field('podcast', false, false);

							$query = new WP_Query(array(
								'post_type'      	=> 'podcast',
								'posts_per_page'	=> 1,
								'post__in'			=> $ids,
								'post_status'		=> 'any',
								'orderby'        	=> 'post__in',
							));

							$podcast = $query->post->ID;
						?>

						<?= get_the_post_thumbnail($podcast, 'thumbnail', array( 'class' => 'podcast-image' )); ?>
						
						<div class="podcast-info">

							<h2 class="podcast-title"><?php echo $query->post->post_title; ?></h2>

							<p class="podcast-excerpt"><?= get_the_excerpt($podcast); ?></p>

						</div>
						
					</div>
				</div>
				
			</div><!-- .pagination-single-inner -->

			<a class="podcast-load-button" href="<?php echo get_permalink($podcast); ?>" data-id="<?php echo $podcast; ?>">
								Load: "<?php echo $query->post->post_title; ?>"
			</a>

			<?php wp_reset_postdata(); ?>

			<div class="js-loader">
					<img src="<?php echo get_theme_file_uri('js/spinner.svg'); ?>" width="32" height="32" />
				</div>
			<hr class="styled-separator is-style-wide" aria-hidden="true">
		</div> <!-- .entry-content -->
	</nav>
	

	<div class="entry-content">
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Post title. Only visible to screen readers. */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentynineteen' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'twentynineteen' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php twentynineteen_entry_footer(); ?>
	</footer><!-- .entry-footer -->

	<?php if ( ! is_singular( 'attachment' ) ) : ?>
		<?php get_template_part( 'template-parts/post/author', 'bio' ); ?>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
