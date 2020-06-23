<?php
/* Template Name: User Orders */

get_header(); ?>

<?php get_template_part( 'template-parts/featured-image' ); ?>
<div class="main-container">
    <div class="main-grid">
        <main class="main-content-full-width">
            <?php
            while ( have_posts() ){
                the_post();
                if(isset($_GET['sortdir']) && !empty($_GET['sortdir'])){
                    $sortdir = $_GET['sortdir'];
                } else {
                    $sortdir = 'ASC';
                }
                $newsortdir = ($sortdir === 'ASC' ? 'DESC' : 'ASC');

            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header>
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <span class='csv-download align-right'><a href="<?php echo rede_campaign_csv_link(); ?>"><i class="fi-page-export-pdf large"></i>Download Excel</a></span>
                </header>
                <div class="entry-content">
                    <?php the_content(); ?>
                    <table class="table stack tablesorter">
                        <thead>
                            <tr>
                                <th><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'ID' ) ); ?>">ID</a></th>
                                <th><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'brand' ) ); ?>">Brand</a></th>
                                <th><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'ordername' ) ); ?>">Name</a></th>
                                <th><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'type' ) ); ?>">Type</a></th>
                                <th><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'order_status' ) ); ?>">Status</a></th>
                                <th><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => '_startdate' ) ); ?>">Market Date</a></th>
                                <th>Retailer</th>
                                <?php if(user_check_role('rede_vendor')){ ?>
                                    <th>Review</th>
                                <?php } else { ?>
                                    <th>Action</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            global $current_user;
                            wp_get_current_user();
                            if(user_check_role('rede_vendor')){
                                $author_query = array(
                                    'post_type' => 'rede-order',
                                    'posts_per_page' => '-1',
                                    'meta_query' => array(
                                        'user_clause' => array(
                                            'key'     => '_vendor',
                                            'value'   => $current_user->ID,
                                            'compare' => '=',
                                        )
                                    )
                                );
                            } else {
                                $author_query = array(
                                    'post_type' => 'rede-order',
                                    'posts_per_page' => '-1',
                                    'meta_query' => array(
                                        'user_clause' => array(
                                            'key'     => '_user',
                                            'value'   => $current_user->ID,
                                            'compare' => '=',
                                        )
                                    )
                                );
                            }
                            if(isset($_GET['sortby']) && $_GET['sortby'] !== 'ID'){
                                $author_query['meta_query']['sort_clause'] = array(
                                    'key' => $_GET['sortby'],
                                    'compare' => 'EXISTS'
                                );
                                $author_query['orderby'] = array(
                                    'sort_clause' => $sortdir
                                );
                            } else if(isset($_GET['sortby']) && $_GET['sortby'] === 'ID'){
                                $author_query['orderby'] = 'ID';
                                $author_query['order'] = $sortdir;
                            }
                            // print_r($author_query);
                            // die();
                            $author_posts = new WP_Query($author_query);
                            while($author_posts->have_posts()) : $author_posts->the_post();
                                $postid = $author_posts->post->ID;
                                $order_status = get_post_meta($postid, 'order_status', true);

                                if(user_check_role('rede_vendor') && $order_status === "Pending Confirmation"){
                                    continue;
                                }

                                $type = get_post_meta($postid, 'type', true);
                                $marketdate = get_post_meta($postid, 'marketdate', true);
                                $brand = get_post_meta($postid, 'brand', true);
                                $serviceURL = get_post_meta($postid, 'serviceURL', true);
                                $retailer = get_first_store($postid);
                                ?>
                                <tr>
                                    <?php if(user_check_role('rede_vendor')){ ?>
                                    <td><?php echo $postid; ?></td>
                                    <?php } else { ?>
                                    <?php if($order_status !== "Pending Confirmation"){ ?>
                                    <td><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo $postid; ?></a></td>
                                    <?php } else {?>
                                    <td><a href="<?php echo $serviceURL; ?>?order-id=<?php echo $postid; ?>" title="<?php the_title_attribute(); ?>"><?php echo $postid; ?></a></td>
                                    <?php } ?>
                                    <?php } ?>
                                    <td><?php echo $brand; ?></td>
                                    <td><?php the_title(); ?></td>
                                    <td><?php echo $type; ?></td>
                                    <td><?php echo $order_status; ?></td>
                                    <td><?php echo $marketdate; ?></td>
                                    <td><?php echo $retailer; ?></td>
                                    <?php if(user_check_role('rede_vendor')){ ?>
                                        <td><a href="<?php echo home_url( '/review-center' ); ?>?order-id=<?php echo $postid ?>">Review</a></td>
                                    <?php } else { ?>
                                    <?php if(in_array($order_status, array("Active", "Review Report", "Awaiting Report", "Completed"))){ ?>
                                        <td><a href="<?php echo home_url( '/invoice' ); ?>?order-id=<?php echo $postid ?>">Invoice</a></td>
                                    <?php } else if(in_array($order_status, array("Needs Creative","Review Order"))){ ?>
                                        <td>Pending</td>
                                    <?php } else if(in_array($order_status, array("Confirm Details", "Creative Added"))){ ?>
                                        <td><a href="<?php echo home_url( '/review-center' ); ?>?order-id=<?php echo $postid; ?>" title="<?php the_title_attribute(); ?>">Review</a></td>
                                    <?php } else if(in_array($order_status, array("Pending Confirmation"))){ ?>
                                        <td><a href="<?php echo $serviceURL; ?>?order-id=<?php echo $postid; ?>" title="<?php the_title_attribute(); ?>">Edit</a></td>
                                    <?php } else {?>
                                        <td>Pending</td>
                                    <?php } ?>
                                    <?php } ?>
                                </tr>       
                            <?php endwhile; ?> 
                        </tbody>
                    </table>
                </div>
            </article>
            <?php } //end while ?>
        </main>
    </div>
</div>
<?php
get_footer();