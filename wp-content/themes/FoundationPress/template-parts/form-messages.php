<?php
if(isset($_GET['order-id'])){ 
	$order_status = get_post_meta($_GET['order-id'], 'order_status', true);
	if ( $last_id = get_post_meta( $_GET['order-id'], '_rede_edit_last', true) ) {
    	$user_info = get_userdata($last_id);
    } else if($order_author_id = get_post_meta($_GET['order-id'], '_user', true)) {
		$user_info = get_userdata($order_author_id);
    } else {
    	$post_author_id = get_post_field( 'post_author', $_GET['order-id'] );
    	$user_info = get_userdata($post_author_id);
    }
	?>
<div class="callout alert" data-closable>
  <h5>You are editing a saved program.</h5>
  <p>Current Status: <?php echo $order_status;?> <br> Modified: <?php echo get_the_modified_date('F j, Y', $_GET['order-id']); ?> <?php echo get_the_modified_date('g:ia', $_GET['order-id']); ?> by <?php echo $user_info->display_name; ?></p>
  <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php } ?>