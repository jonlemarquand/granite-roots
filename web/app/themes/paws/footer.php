
    <div id="js_cookie_container" class="cookie-bar">
        <p class="cookie-bar__text"><?php _e('This website uses cookies for tracking purposes.', 'paws'); ?></p>
        <a href="<?php echo get_privacy_policy_url(); ?>" target="_blank" class="cookie-bar__link"><?php _e('Read More', 'paws'); ?></a>
        <a id="js_decline_cookie_button" class="cookie-bar__link"><?php _e('Reject', 'paws'); ?></a>
				<a id="js_accept_cookie_button" class="cookie-bar__button"><?php _e('Accept', 'paws'); ?></a>
    </div>


  <footer class="footer" role="contentinfo">
       <div class="container">
        <a href="/" class="logo">Granite<span>Roots</span></a>
       </div>
  </footer>

</div>

<?php wp_footer(); ?>

</body>
</html>
