<?php
/**
 * The template for displaying orders
 *
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

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
				$order_data = get_all_meta($post_id);

				?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header>
						<h1 class="entry-title">Program #<?php echo $post_id; ?> - <?php echo $order_data["order_status"]; ?></h1>
					</header>
					<div class="entry-content">
						<?php the_content(); ?>
						<div class="row">
		                    <div class="medium-6 columns">
		                    	<div class="panel-heading">
			                    	<?php if (isset($order_data["order_status"])){ ?>
							        <p>PROGRAM STATUS</p>
							        <h4><?php echo $order_data["order_status"]; ?></h4>
							        <?php } ?>
							    </div>
	    			            <div class="panel-heading" id="review-comments">
					            	<p>PROGRAM COMMENTS</p>
						            <?php 
						             $args = array(
										'post_id' => $post_id,
										'count' => true
									);
									$comment_count = get_comments($args);
									if($comment_count > 0){
							            $perpage = 5;
							            if(isset($_GET['comment-page'])){
							            	$comment_page = $_GET['comment-page'];
							            	$real_page = $comment_page - 1;
							            	if($real_page < 0){
							            		$real_page = 0;
							            	}
							            	$offset = $perpage * $real_page;
							            	$number = $perpage;
							            } else {
							            	$comment_page = 1;
							            	$real_page = 0;
							            	$offset = 0;
							            	$number = $perpage;
							            }
							            $args = array(
											'number' => $number,
											'offset' => $offset,
											'post_id' => $post_id,
										);
										$comments = get_comments($args);
										foreach($comments as $comment) :
											echo '<div class="comment-container">';
											$comment_date = date($config['datetime_format'], strtotime($comment->comment_date));
											echo '<div class="comment-header"><span class="comment-date">(' . $comment_date . ')</span><span class="comment-author">' . $comment->comment_author . '</span></div>';
											echo '<div class="comment-content">' . $comment->comment_content . '</div>';
											echo '</div>';
										endforeach;
										echo '<div class="comment-pagination">';
											if($offset > 0){
												$arr_params = array( 'comment-page' => $comment_page - 1 );
												echo '<a class="comment-newer" href="'.esc_url( add_query_arg( $arr_params ) ).'">Newer</a>';
											}
											if($offset + $perpage + 1 < $comment_count){
												$arr_params = array( 'comment-page' => $comment_page + 1 );
												echo '<a class="comment-older" href="'.esc_url( add_query_arg( $arr_params ) ).'">Older</a>';
											}
										echo '</div>';
									} else {
										// echo '<div class="comment-container">';
										echo '<p>No Comments</p>';
										// echo '</div>';
									}	
									?>
									
								</div>
		                    </div>
							<div class="medium-6 columns order-data">
								<div class="panel-heading">
                                    <h4 class="panel-title">Program Summary</h4>
                                </div>
                                <div class="panel-body">
									<table class="table table-striped border">
	                                    <?php
	                                    echo get_orderdata_as_table($post_id, true, true, false);
	                                    ?>
	                                    <tr class="total-costs">
	                                        <td>Total Cost</td>
	                                        <td class="summary-total-cost"><?php echo $order_data["total"]; ?></td>
	                                    </tr>
	                                </table>
	                            </div>
		                    </div>
		                </div>
						<?php
						// foreach($order_data as $order_name=>$order_val){
						// 	echo '<strong>' . $order_name . '</strong>: ' . $order_val . '<br>';
						// } 
						?>
					</div>
				</article>
			<?php endwhile; ?>
		</main>
	</div>
</div>
<?php
get_footer();