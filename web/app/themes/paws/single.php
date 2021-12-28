
<?php get_header(); ?>

<?php the_post(); ?>
<?php 
	  $categories = get_the_terms( false, 'category' );
		$tags = get_the_terms( false, 'tags' );
?>
<div class="container">
	<img src="<?php echo the_post_thumbnail_url() ?>" class="blog-featured-image"/>
	<div class="blog-post-columns">
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
							$post_thumbnail = wp_get_attachment_url( get_post_thumbnail_id($related_post->ID), 'thumbnail' );
							$post_date = get_the_date(null, $related_post->ID);
							$permalink = get_permalink($related_post->ID);
						?>
						<a class="related-post" href="<?php echo $permalink ?>">
						<?php if($post_thumbnail) { ?>
							<img class="related-post__image" src="<?php echo $post_thumbnail ?>" />
						<?php } else { ?>
							<div class="related-post__image--none related-post__image">GR</div>
						<?php } ?>
							<div>
								<p class="subtitle"><?php echo $post_date ?></p>
								<h3><?php echo $related_post->post_title ?></h3>
								<p>As I walk around the paths through the healthland I take delight in the colours around me.</p>
							</div>
						</a>
						<?php
					}
				}
			?>
		</div>
		<div class="right-column">
		</div>
	</div>
</div>
<?php get_footer(); ?>