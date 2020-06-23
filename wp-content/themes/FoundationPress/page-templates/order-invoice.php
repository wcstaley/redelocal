<?php
/* Template Name: Invoice */

$config = get_config();

get_header(); ?>

<?php get_template_part( 'template-parts/featured-image' ); ?>
<div class="main-container">
    <div class="main-grid">
        <main class="main-content-full-width">
            <?php
            while ( have_posts() ) :
                the_post();
                $post_id = get_the_ID();
                $order_id = $_GET['order-id'];
                $order_data = get_all_meta($order_id);
                $timestamp = get_the_date(  $config['datetime_format_simple'], $order_id );
                $current_user = wp_get_current_user();
                $user_id = $current_user->ID;

                if(!isset($order_data["tax"]) || empty($order_data["tax"])){
                    $order_data["tax"] = 0;
                }
                if($order_data["tax"] > 0){
                    $order_data["totalwtax"] = (int)$order_data["total"] + (int)$order_data["tax"];
                } else {
                    $order_data["totalwtax"] = $order_data["total"];
                }

                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header>
                        <h1 class="entry-title">Invoice</h1>
                        <span class='pdf-download align-right'><a href="<?php echo rede_invoice_pdf_link($order_id); ?>"><i class="fi-page-export-pdf large"></i> Download PDF</a></span>
                    </header>
                    <div class="entry-content">
                        <?php the_content(); ?>   
                        <div class="row">   
                            <div class="medium-7 columns detail-container">
                                <div class="row"> 
                                    <div class="medium-6 columns"> 
                                        <ul class="main-client">
                                            <li class="list-header">Red/E</li>
                                            <li><strong><?php echo $current_user->first_name; ?> <?php echo $current_user->last_name; ?></strong></li>
                                            <li>Email: <?php echo $current_user->user_email; ?></li>
                                        </ul>
                                    </div>
                                    <div class="medium-6 columns"> 
                                        <ul class="invoice-details">
                                            <li class="list-header">Invoice Details</li>
                                            <li><strong>Invoice #</strong> <span><?php echo $order_id; ?></span></li>
                                            <li><strong>Invoice Date</strong> <span><?php echo $timestamp; ?></span></li>
                                            <li><strong>Due Date:</strong> <span><?php echo date($config['datetime_format_simple'], strtotime($order_data["_enddate"])); ?></span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="medium-5 columns detail-container">
                                <div class="row">
                                    <div class="medium-12 columns"> 
                                        <ul class="send-contact">
                                            <li class="list-header">Please Send Payment To</li>
                                            <li><strong>Red/E</strong></li>
                                            <li>246 Morehouse Rd, Easton CT 06612</li>
                                            <li>(203) 219-8103</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped border">
                            <?php
                            echo get_orderdata_as_table($order_id, true, false, false);
                            ?>
                        </table>
                        <div class="row collapse">   
                            <div class="medium-12 columns">
                                <div class="table-scroll">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Summary</th>
                                                <th class="text-center">Quantity / Store</th>
                                                <th>SubTotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="invoice-item" >
                                                <td>
                                                    <strong><?php echo get_the_title( $order_id ); ?></strong><br>
                                                    <span class="clearfix">(<?php echo $order_data["type"]; ?>)</span><br>
                                                    <div>Number of Stores: <?php echo number_format((int)$order_data["storecount"]); ?></div>
                                                </td>
                                                <?php if (isset($order_data["quantity"]) && !empty($order_data["quantity"])){ ?>
                                                    <td class="text-center"><?php echo number_format((int)$order_data["quantity"]); ?></td>
                                                <?php } else { ?>
                                                    <td class="text-center">N/A</td>
                                                <?php }; ?>
                                                <td><?php echo $order_data["total"]; ?></td>
                                            </tr>

                                            <tr class="sub-total-group">
                                                <td></td>
                                                <td>
                                                    <strong>Subtotal:</strong><br>
                                                    <strong class="clearfix">Tax:</strong>
                                                </td>
                                                <td>
                                                    <span class="subtotal-dollar"><?php echo $order_data["total"]; ?></span><br>
                                                    <span class="subtotal-dollar clearfix"><?php echo $order_data["tax"]; ?></span>
                                                </td>
                                            </tr>
                                            <tr class="total-group">
                                                <td>
                                                    <span class="due-date">Due on <?php echo $order_data["marketdate"]; ?></span>
                                                </td>
                                                <td>
                                                    <strong>Total:</strong>
                                                </td>
                                                <td>
                                                    <span class="total-dollar"><?php echo $order_data["totalwtax"]; ?></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </main>
    </div>
</div>
<?php
get_footer();