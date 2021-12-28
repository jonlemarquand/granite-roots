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
	<h1>Granite<span class="front-page__logo--span">Roots</span></h1>
	<div class="subtitle"><?php echo $subtitle ?></div>
	<section>
	<?php 
			$permalink = get_permalink($posts[0]->ID);
			$post_thumbnail = wp_get_attachment_url( get_post_thumbnail_id($posts[0]->ID), 'thumbnail' );
			$post_date = get_the_date(null, $posts[0]->ID);
	?>
	<a href="<?php echo $permalink ?>" class="front-page-main-post">
				<img class="front-page-main-post__image" src="<?php echo $post_thumbnail?>" />
				<p class="subtitle"><?php echo $post_date ?></p>
				<h2><?php echo $posts[0]->post_title ?></h2>
				<p>As I walk around the paths through the healthland I take delight in the colours around me.</p>
	</a>
	<div class="post-grid-3">
	<?php for($i = 1; $i < count($posts); $i++)
	{ 
		$post_thumbnail = wp_get_attachment_url( get_post_thumbnail_id($posts[$i]->ID), 'thumbnail' );
		$post_date = get_the_date(null, $posts[$i]->ID);
		$permalink = get_permalink($posts[$i]->ID);
						?>
						<a class="front-page-post" href="<?php echo $permalink ?>">
						<?php if($post_thumbnail) { ?>
							<img class="front-page-post__image" src="<?php echo $post_thumbnail ?>" />
						<?php } else { ?>
							<div class="front-page-post__image--none front-page-post__image">GR</div>
						<?php } ?>
							<p class="subtitle"><?php echo $post_date ?></p>
							<h3><?php echo $posts[$i]->post_title ?></h3>
							<p>As I walk around the paths through the healthland I take delight in the colours around me.</p>
						</a>
						<?php
					}
			?>
	</div>
	</section>
</div>
<?php get_footer(); ?>