<?php

// Sample AJAX
add_action('wp_ajax_example_call', 'example_call');
add_action('wp_ajax_nopriv_example_call', 'example_call');


function example_call(){
    $test = 'Ajax Test';
    var_dump($test);
    die();
}