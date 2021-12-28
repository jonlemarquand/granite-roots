<?php get_header(); ?>

<?php the_post(); ?>
<div class="container">
	<img src="<?php echo the_post_thumbnail_url() ?>" class="blog-featured-image"/>
	<div class="left-column">
		<p class="subtitle"><?php the_date(); ?></p>
		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>
		<?php 
			$related_content = get_field('related_posts');
			if($related_content) { 
		?>
				<h2>Related Posts</h2>
			<?php 
				foreach($related_content as $related_post) { ?>					
					<?php
						$post_thumbnail = wp_get_attachment_url( get_post_thumbnail_id($related_post->post_id), 'thumbnail' );
						$post_date = get_the_date(null, $related_post->post_id);
					?>
					<a class="related-post" href="<?php echo $related_post->guid ?>">
						<img class="related-post__image" src="<?php echo $post_thumbnail ?>" />
						<div>
							<p class="subtitle"><?php echo $post_date ?></p>
							<h3><?php echo $related_post->post_title ?></h3>
							<p><?php echo $related_post->post_excerpt ?></p>
						</div>
					</a>
					<?php
				 }
			}
		?>
</div>
		</div>
<?php get_footer(); ?>