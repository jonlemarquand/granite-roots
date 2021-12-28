<?php

// Gutenberg block editor theme supports
require_once('block_editor.php');

// Register custom ACF blocks
require_once('register_acf_blocks.php');

// Register block categories
require_once('block_categories.php');

// Conditionally allow blocks
require_once('allowed_blocks.php');

// Remove empty buttons
require_once('remove_empty_buttons.php');

// Filter buttons to add post url dynamically by ID
require_once('button_filter.php');