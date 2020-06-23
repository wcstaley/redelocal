<?php 
if(isset($_GET['sortdir']) && !empty($_GET['sortdir'])){
    $sortdir = $_GET['sortdir'];
} else {
    $sortdir = 'ASC';
}
$newsortdir = ($sortdir === 'ASC' ? 'DESC' : 'ASC'); 
?>


<ul class="tabs" data-tabs id="example-tabs">
    <li class="tabs-title is-active"><a href="#panel1" aria-selected="true">Active</a></li>
    <li class="tabs-title"><a data-tabs-target="panel2" href="#panel2">Pending</a></li>
    <li class="tabs-title"><a data-tabs-target="panel3" href="#panel2">Inactive</a></li>
</ul>

<div class="tabs-content" data-tabs-content="example-tabs">
    <div class="tabs-panel is-active" id="panel1">
        <table class="table stack tablesorter">
            <thead>
                <tr>
                    <th class="sort-order"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'ID' ) ); ?>#example-tabs">ID</a></th>
                    <th class="sort-brand"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'brand' ) ); ?>#example-tabs">Brand</a></th>
                    <th class="sort-name"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'ordername' ) ); ?>#example-tabs">Name</a></th>
                    <th class="sort-type"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'type' ) ); ?>#example-tabs">Type</a></th>
                    <th class="sort-status"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'order_status' ) ); ?>#example-tabs">Status</a></th>
                    <th class="sort-marketdate"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => '_startdate' ) ); ?>#example-tabs">Market Date</a></th>
                    <th class="sort-retailer">Retailer</th>
                    <?php if(user_check_role('rede_vendor')){ ?>
                        <th class="sort-invoice">Review</th>
                    <?php } else { ?>
                        <th class="sort-invoice">Action</th>
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

                    if(!in_array($order_status, array("Active", "Awaiting Report", "Completed"))){
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
    <div class="tabs-panel" id="panel2">
        <table class="table stack tablesorter">
            <thead>
                <tr>
                    <th class="sort-order"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'ID' ) ); ?>#example-tabs">ID</a></th>
                    <th class="sort-brand"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'brand' ) ); ?>#example-tabs">Brand</a></th>
                    <th class="sort-name"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'ordername' ) ); ?>#example-tabs">Name</a></th>
                    <th class="sort-type"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'type' ) ); ?>#example-tabs">Type</a></th>
                    <th class="sort-status"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'order_status' ) ); ?>#example-tabs">Status</a></th>
                    <th class="sort-marketdate"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => '_startdate' ) ); ?>#example-tabs">Market Date</a></th>
                    <th class="sort-retailer">Retailer</th>
                    <?php if(user_check_role('rede_vendor')){ ?>
                        <th class="sort-invoice">Review</th>
                    <?php } else { ?>
                        <th class="sort-invoice">Action</th>
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

                    if($order_status !== "Pending Confirmation"){
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
    <div class="tabs-panel" id="panel3">
        <table class="table stack tablesorter">
            <thead>
                <tr>
                    <th class="sort-order"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'ID' ) ); ?>#example-tabs">ID</a></th>
                    <th class="sort-brand"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'brand' ) ); ?>#example-tabs">Brand</a></th>
                    <th class="sort-name"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'ordername' ) ); ?>#example-tabs">Name</a></th>
                    <th class="sort-type"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'type' ) ); ?>#example-tabs">Type</a></th>
                    <th class="sort-status"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => 'order_status' ) ); ?>#example-tabs">Status</a></th>
                    <th class="sort-marketdate"><a href="<?php echo add_query_arg( array( 'sortdir' => $newsortdir, 'sortby' => '_startdate' ) ); ?>#example-tabs">Market Date</a></th>
                    <th class="sort-retailer">Retailer</th>
                    <?php if(user_check_role('rede_vendor')){ ?>
                        <th class="sort-invoice">Review</th>
                    <?php } else { ?>
                        <th class="sort-invoice">Action</th>
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

                    if(in_array($order_status, array("Active", "Awaiting Report", "Completed", "Pending Confirmation"))){
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
        <script>
            $('.tablesorter').on('click', 'th a', function(e){
                e.preventDefault();
                e.stopPropagation();

                var $dest = $(e.target);

                console.log('tablesorter', $dest);
                $.get( $dest.attr('href'), function( data ) {
                    ajax_panel1 = $(data).find('#panel1 .tablesorter').html();
                    ajax_panel2 = $(data).find('#panel2 .tablesorter').html();
                    ajax_panel3 = $(data).find('#panel3 .tablesorter').html();
                    //console.log('ajax',ajax_panel1);
                    $('#panel1 .tablesorter').html(ajax_panel1);
                    $('#panel2 .tablesorter').html(ajax_panel2);
                    $('#panel3 .tablesorter').html(ajax_panel3);
                    console.log('ajax_panel1', $dest.attr('href'));
                });

                return false;
            });

        </script>
    </div>
</div>