<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "off-canvas-wrap" div and all content after.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */
?>

<div class="reveal" id="errorModal" data-reveal>
  <h4>Your Order Is Missing Important Information</h4>
  <p>Please review your order and correct the following issues</p>
  <ul class="error-list">
  </ul>
  <button class="close-button" data-close aria-label="Close reveal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<footer class="footer-container">
  <div class="row">
    <div class="small-12 columns">
      <div class="footer-head">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/Headers/footer-top.png">
      </div>
      <div class="footer-bottom">
        <div class="row">
          <div class="small-12 medium-6 columns">
            Our mission is to put our customers in control and help them work more efficiently by streamlining the marketing process, so they can deliver faster, more effective solutions with ease.
          </div>
          <div class="small-12 medium-6 columns text-right">
            <a href="<?php echo home_url('contact-us'); ?>">Contact Us</a><br>
            (203)219-8103<br>
          </div>
        </div>
        <div class="row footer-links">
          <div class="small-12 columns">
            <br>
            <a href="<?php echo home_url('terms-of-use'); ?>">Terms of Use</a> - <a href="<?php echo home_url('privacy-policy'); ?>">Privacy Policy</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>

<?php if ( get_theme_mod( 'wpt_mobile_menu_layout' ) === 'offcanvas' ) : ?>
	</div><!-- Close off-canvas content -->
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>