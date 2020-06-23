<?php
/* Template Name: Order Confirm */

get_header(); ?>

<div class="main-container">
    <div class="main-grid">
        <main class="main-content-full-width">
            <?php
            while ( have_posts() ){
                the_post();
                $post_id = get_the_ID();
                $order_id = $_GET['order-id'];
                $order_data = get_all_meta($order_id);
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <form id="confirm-form" method="post">
                        <div class="row">
                            <div class="medium-6 columns">
                                <h2><?php the_title(); ?></h2>
                                <p class="margin-top-20">
                                    Please review your account selections and total budget. By submitting this you are liable for the costs of the program as identified here. Cancellation or modification of order will result in any costs incurred to date. 
                                    <?php if ($order_data['type'] !== "Mobile Media" && $order_data['type'] !== "Out of Home" && $order_data['type'] !== "Paid Social" && $order_data['type'] !== "Coupon Booster" && $order_data['type'] !== "Security Shroud"){ ?>
                                        Client is responsible for Letter of Authorization from retailers. If client waives responsibility for LOA client is responsible for all merchandising costs.
                                    <?php } ?>
                                </p>
                                <p class="font-weight-500 margin-top-20">
                                    <h5>Additional Comments</h5>
                                    <?php if ($order_data['type'] !== "Mobile Media" && $order_data['type'] !== "Coupon Booster" && $order_data['type'] !== "Paid Social" && $order_data['type'] !== "Out of Home"){ ?>
                                        <br />
                                        <span class="font-weight-300">Include any specific product or product placement details.</span>
                                    <?php } ?>
                                </p>
                                <textarea id="comments" name="comments" class="form-control" rows="7">  </textarea>
                                <p>If you have any questions please email <a href="mailto:support@rede-marketing.com">support@rede-marketing.com</a></p>
                            </div>
                            <div class="medium-6 columns order-data">
                                <div class="panel panel-bordered border">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">Program Summary</h4>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-striped border">
                                            <?php
                                            echo get_orderdata_as_table($order_id, true);
                                            ?>
                                            <tr class="total-costs">
                                                <td>Total Cost</td>
                                                <td class="summary-total-cost"><?php echo $order_data["total"]; ?></td>
                                            </tr>
                                        </table>
                                        <div class="confirm-buttons">
                                            <?php $nonce_action = 'confirm_' . $order_id;
                                            wp_nonce_field( $nonce_action, 'confirm-nonce' ); ?>
                                            <a class="button success" data-open="legal-modal">Place Your Order</a>
                                            <a type="button" class="button secondary" href="/rede/dashboard">Cancel</a>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="reveal" id="legal-modal" data-reveal>
                                <?php get_template_part( 'template-parts/legal' ); ?>
                                <button type="submit" class="btnsubmit button success">Agree and Place Order</button>
                                <button class="close-button" data-close aria-label="Close modal" type="button">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </article>
        <?php } //end while ?>
        </main>
    </div>
</div>
<?php
get_footer();
