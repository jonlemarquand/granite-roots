<?php get_header(); ?>

<?php the_post(); ?>
<div class="container">
	<img src="<?php echo the_post_thumbnail_url() ?>" class="blog-featured-image"/>
	<div class="left-column">
		<p class="subtitle"><?php the_date(); ?></p>
		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>
	</div>
</div>
<?php get_footer(); ?>