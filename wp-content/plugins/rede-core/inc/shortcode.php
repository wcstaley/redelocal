<?php

function rede_image_path( $atts ){
	return get_stylesheet_directory_uri() . '/dist/assets/images/';
}
add_shortcode( 'rede_file_path', 'rede_image_path' );