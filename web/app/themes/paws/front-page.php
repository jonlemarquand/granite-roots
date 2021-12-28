<?php 
	$title = get_bloginfo();
	$subtitle = get_bloginfo('description');
	$posts = get_posts([
		'numberposts'      => 4,
		'orderby'          => 'date',
		'order'            => 'DESC',
		'post_type'        => 'post',
	]);
?>
<?php get_header(); ?>
<div class="container front-page">
	<h1>Granite<span>Roots</span></h1>
	<div class="subtitle"><?php echo $subtitle ?></div>
	<div class="front-page-main-post">
		<?php 
			$post_thumbnail = wp_get_attachment_url( get_post_thumbnail_id($posts[0]->ID), 'thumbnail' );
		?>
				<img class="front-page-main-post__image" src="<?php echo $post_thumbnail?>" />
				<h2><?php echo $posts[0]->post_title ?></h2>
	</div>
	<div class="post-grid-3">
	<?php for($i = 1; $i < count($posts); $i++)
	{ 
		$post_thumbnail = wp_get_attachment_url( get_post_thumbnail_id($posts[$i]->ID), 'thumbnail' );
		$post_date = get_the_date(null, $posts[$i]->ID);
		$permalink = get_permalink($posts[$i]->ID);
						?>
						<a class="frontpage-post" href="<?php echo $permalink ?>">
						<?php if($post_thumbnail) { ?>
							<img class="frontpage-post__image" src="<?php echo $post_thumbnail ?>" />
						<?php } else { ?>
							<div class="frontpage-post__image--none frontpage-post__image">GR</div>
						<?php } ?>
							<div>
								<p class="subtitle"><?php echo $post_date ?></p>
								<h3><?php echo $posts[$i]->post_title ?></h3>
								<p>As I walk around the paths through the healthland I take delight in the colours around me.</p>
							</div>
						</a>
						<?php
					}
			?>
	</div>
</div>
<?php get_footer(); ?>