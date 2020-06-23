<?php
/* Template Name: Campus Media */

get_header(); ?>

<div class="callout alert" data-closable>
  <p>Log-in with your College Media credentials. Forgot your password? Contact <a href="mailto:support@rede-marketing.com">support@rede-marketing.com</a>.</p>
  <p>Check the <a href="<?php echo home_url('creative-specs'); ?>" target="_blank">Creative Specs</a> page for more information about College Media's creative requirements.</p>
  <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<iframe src="https://app.flytedesk.com/login"></iframe>

<?php
get_footer();
