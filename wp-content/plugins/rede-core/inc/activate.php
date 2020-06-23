<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// This file only executes when the Hookist Core plugin is activated

// Remove custom added roles
remove_role( 'rede_user' );

// Remove unneeded built in roles
remove_role( 'basic_contributor' );
remove_role( 'contributor' );
remove_role( 'author' );
remove_role( 'editor' );
remove_role( 'subscriber' );

$result = add_role(
    'rede_user',
    __( 'User' ),
    array(
        'read'          => true,
        'edit_posts'    => true,
        'upload_files'  => true,
    )
);

$result = add_role(
    'rede_vendor',
    __( 'Vendor' ),
    array(
        'read'          => true,
        'edit_posts'    => true,
        'upload_files'  => true,
    )
);