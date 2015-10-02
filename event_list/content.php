
<?php
$event_date_str = date("d/m/Y G:i", get_post_custom( get_the_ID() )['event_date'][0]);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if( has_post_thumbnail() && siteorigin_setting('blog_featured_image_type') == 'icon' ): ?>
		<div class="entry-thumbnail">
			<a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'thumbnail' ) ?></a>
		</div>
	<?php endif; ?>

	<div class="entry-main">

		<header class="entry-header">
			<?php if( has_post_thumbnail() && siteorigin_setting('blog_featured_image_type') == 'large' ): ?>
				<div class="entry-thumbnail">
					<a href="<?php the_permalink() ?>"><?php the_post_thumbnail( 'thumbnail' ) ?></a>
				</div>
			<?php endif; ?>

			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?>
					<br />
					<span style="font-size: 10px;"><?php echo $event_date_str ?></span>
				</a>
		  </h1>
		</header><!-- .entry-header -->

			<div class="entry-content">
				<?php the_content( __( 'Continua a leggere <span class="meta-nav">&rarr;</span>' ) ); ?>
			</div><!-- .entry-content -->

	</div>

</article><!-- #post-<?php the_ID(); ?> -->
