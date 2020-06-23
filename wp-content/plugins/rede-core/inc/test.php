<?php
// For testing only.
define('WP_USE_THEMES', false);
require_once('../../../../wp-load.php');
require_once('../../../../wp-admin/includes/media.php');
require_once('../../../../wp-admin/includes/file.php');
require_once('../../../../wp-admin/includes/image.php');
?>
<form action="http://localhost/rede/wp-content/plugins/rede-core/inc/test.php" method="post">
	<input type="text" class="form-control" id="ordername" name="ordername">
	<input type="file" id="segment" class="hide" />
	<select data-plugin="selectpicker" id="tactic" name="tactic">
        <option value="IRC">IRC</option>
        <option value="Necker">Necker</option>
        <option value="Hang Tag">Hang Tag</option>
    </select>
    <input type="hidden" name="quantity" value="1" />
    <input type="hidden" name="sku" value="234" />
    <input type="hidden" name="marketdate" value="12/24/2018" />
	<input type="hidden" name="storecount" value="12" />
	<input type="hidden" name="total" id="total" />
	<input type="hidden" name="costperstore" id="costperstore" />
	<input type="hidden" name="type" id="type" value="On-Pack" />
	<input type="hidden" name="ordertype" id="ordertype" value="onpack" />
	<?php $ajax_nonce = wp_create_nonce( "order-nonce" ); ?>
	<input type="hidden" name="security" id="order-nonce" value="<?php echo $ajax_nonce; ?>"> 
	<input type="hidden" name="pfid" id="pfid" value="" />
	<input type="submit" value="Submit">
</form>
<?php
rede_ajax_create_order();