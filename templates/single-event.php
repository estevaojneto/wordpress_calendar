<?php get_header(); ?>

	<div id="content" class="site-content">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
		<h2><?php the_title(); ?></h2>
			
			<div class="entry">
				<?php if (has_post_thumbnail( $post->ID ) ): ?>
  <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
  <div id="custom-bg" style="background-image: url('<?php echo $image[0]; ?>')">

  </div>
<?php endif; ?>
				<?php the_content(); 
				echo get_post_meta( get_the_ID(), 'start_date', true );
				echo get_post_meta( get_the_ID(), 'end_date', true );
				?>
				
			</div>
		</div>
		<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	</div>

<?php get_footer(); ?>