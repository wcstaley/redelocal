<?php
/* Template Name: Message Center */

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

				if ( $last_id = get_post_meta( $order_id, '_rede_edit_last', true) ) {
			    	$user_info = get_userdata($last_id);
			    } else if($order_author_id = get_post_meta($order_id, '_user', true)) {
					$user_info = get_userdata($order_author_id);
			    } else {
			    	$post_author_id = get_post_field( 'post_author', $order_id );
			    	$user_info = get_userdata($post_author_id);
			    }

                // To fix URLs previous to edit functionality
                if(!isset($order_data['serviceURL']) || empty($order_data['serviceURL'])){
                	switch($order_data['type']){
                		case 'Paid Social':
                			$order_data['serviceURL'] = home_url('/dashboard/paid-social/');
                			break;

                		case 'On-Pack':
                			$order_data['serviceURL'] = home_url('/dashboard/on-pack/');
                			break;

                		case 'Out of Home':
                			$order_data['serviceURL'] = home_url('/dashboard/out-of-home/');
                			break;

                		case 'Security Shroud':
                			$order_data['serviceURL'] = home_url('/dashboard/security-shroud-media/');
                			break;

                		case 'Mobile Media':
                			$order_data['serviceURL'] = home_url('/dashboard/mobile-media/');
                			break;

                		case 'Coupon Booster':
                			$order_data['serviceURL'] = home_url('/dashboard/coupon-booster/');
                			break;

                		case 'Sampling':
                			$order_data['serviceURL'] = home_url('/dashboard/sampling/');
                			break;
                	}
                }
                $timestamp = get_the_date(  $config['datetime_format'], $order_id );
                $current_user = wp_get_current_user();
                $user_id = $current_user->ID;
                $fullurl = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

                if(!isset($order_data["tax"]) || empty($order_data["tax"])){
                    $order_data["tax"] = 0;
                }
                if($order_data["tax"] > 0){
                    $order_data["totalwtax"] = (int)$order_data["total"] + (int)$order_data["tax"];
                } else {
                    $order_data["totalwtax"] = $order_data["total"];
                }

				$reviewOrder = array();
				$reviewOrder['details'] = array('type','ordername','brand','marketdate','enddate');

				$reviewOrder['details-extended'] = array(
					'tactic',
					'quantity',
					'sku',
					'destinationurl'
				);

				$reviewOrder['campaign-details'] = array(
					'campaignobjective',
					'campaignpurpose',
					'campaigntiming'
				);

				$reviewOrder['upgrades'] = array(
					'upgrade-1',
					'upgrade-2',
					'filenameupgrade2',
					'upgrade-3',
					'filenameupgrade3'
				);

				$reviewOrder['profile'] = array(
					'filenameseg',
					'profilegender',
					'profileage',
					'profilechildren',
					'profileincome'
				);
				
				$reviewOrder['comments'] = array(	
					'otherdetails',
					'comments',
					'otherconsiderations'
				);

				$reviewOrder['creative'] = array(	
					'tactic-custom',
					'filename',
					'pfid',
				);

				$reviewOrder['dma'] = array(
					'dma',
					'customlistname',
				);

				$reviewOrder['stores'] = array(
					'store',
					'storecount',
				);

				$reviewOrder['costs'] = array(
					'budget',
					'costperstore',
					'total',
				);


                ?>
                <input type="hidden" id="order-id" value="<?php echo $order_id; ?>"> 
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				    <div class="entry-content">
				        <div class="row" style="margin-bottom:20px;">
				            <h3>Program #: <?php echo $order_id; ?> - <span class="order-status">[<?php echo $order_data['order_status']; ?>]</span></h3>
				            <?php if ($order_data['order_status'] === "Review Order" || $order_data['order_status'] === "Review Creative" || $order_data['order_status'] === "Review Proof" ){ ?>
				            <div class="columns large-12">
				                <a class="button success  btn-rede" data-toggle="approveModal">Approve</a>
				                <a class="button secondary btn-outline btn-dark" data-toggle="conditionalModal">Conditionally Approve</a>
				                <a class="button secondary btn-outline btn-dark" data-toggle="denyModal">Request Form Resubmit</a>
				            </div>
				            <?php } ?>
				            <?php if ($order_data['order_status'] === "Review Report"){ ?>
					            <?php if (user_check_role('rede_user')){ ?>
					            <div class="columns large-12">
					                <a class="button success btn-rede" data-toggle="approveModal">Approve</a>
					                <a class="button secondary btn-outline btn-dark" data-toggle="denyModal">Conditionally Approve</a>
					            </div>
					            <?php } else { ?>
					            <div class="columns large-12">
					            	<p>Waiting for customer to review report</p>
				            	</div>
					            <?php } ?>
				            <?php } ?>
				            <?php if ($order_data['order_status'] === "Awaiting Report"){ ?>
					            <?php if (user_check_role('rede_vendor') || user_check_role('administrator')){ ?>
					            <div class="columns large-12">
					                <a class="button success btn-rede" data-toggle="approveModal">Send Report</a>
					            </div>
					            <?php } ?>
				            <?php } ?>
				            <?php if ($order_data['order_status'] === "Needs Creative"){ ?>
					            <?php if (user_check_role('rede_vendor') || user_check_role('administrator')){ ?>
			                	 <div class="columns large-12">
					                <a class="button success btn-rede" data-toggle="approveModal">Send Brief</a>
					            </div>
				                <?php } ?>
			                <?php } ?>
			                <?php if($order_data['order_status'] === "Review Brief"){ ?>
				                <?php if (user_check_role('rede_vendor') || user_check_role('administrator')){ ?>
			                	 <div class="columns large-12">
					                <a class="button success btn-rede" data-toggle="approveModal">Send Creative</a>
					                <a class="button secondary btn-outline btn-dark" data-toggle="conditionalModal">Conditionally Approve</a>
					            </div>
					            <?php } ?>
				            <?php } ?>
				            <?php if($order_data['order_status'] === "Awaiting Brief"){ ?>
				            	 <?php if (user_check_role('rede_user') || user_check_role('administrator')){ ?>
			                	 <div class="columns large-12">
					                <a class="button success btn-rede" data-toggle="approveModal">Send Brief</a>
					            </div>
					            <?php } ?>
				            <?php } ?>
				            <?php if ($order_data['order_status'] === "Confirm Details" || $order_data['order_status'] === "Creative Added"){ ?>
					            <?php if (user_check_role('rede_user') || user_check_role('administrator')){ ?>
					            <div class="columns large-12">
					                <a class="button success btn-rede" data-toggle="approveModal">Approve</a>
					                <a class="button secondary btn-outline btn-dark" data-toggle="conditionalModal">Conditionally Approve</a>
					            </div>
					            <?php } else { ?>
					            <div class="columns large-12">
					            	<p>Waiting for customer to review program</p>
				            	</div>
					            <?php } ?>
				            <?php } ?>
				        </div>

				        <?php if (in_array($order_data['order_status'], array("Active","Needs Creative","Awaiting Report", "Awaiting Brief", "Review Brief"))){ ?>
					        <div class="row">
					        	<?php if ($order_data['order_status'] === "Active" || $order_data['order_status'] === "Awaiting Report"){ ?>
				                	<h4>Upload Report</h4>
				                	<input type="hidden" id="review-upload" value="report"> 
				                <?php } else if ($order_data['order_status'] === "Needs Creative"){ ?>
				                	<h4>Upload Brief</h4>
				                	<input type="hidden" id="review-upload" value="brief"> 
				                <?php } else if ($order_data['order_status'] === "Awaiting Brief"){ ?>
				                	<h4>Upload Brief</h4>
				                	<input type="hidden" id="review-upload" value="user_brief"> 
				                <?php } else if ($order_data['order_status'] === "Review Brief"){ ?>
				                 	<h4>Upload Creative</h4>
				                	<input type="hidden" id="review-upload" value="creative"> 
				                <?php } ?>
					            <div class="large-12 columns">
						            <div class="padding-30 border bg-grey-100 margin-bottom-30">
						                <?php $ajax_nonce = wp_create_nonce( "media-nonce" ); ?>
	                        			<input type="hidden" id="media-nonce" value="<?php echo $ajax_nonce; ?>"> 
						                <input type="file" id="creativeupload" class="hide" />
						                <a class="button success" id="upload"><span class="icon wb-upload margin-right-5" aria-hidden="true"></span>Upload File</a>
						                <a class="button secondary btn-lg inverted hide" id="preview"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>Preview</a>
						                <div class="progress margin-vertical-20">
						                    <div class="progress-bar" role="progressbar" style="width: 0%;"></div>
										</div>
										<div class="fileinfo"></div>
										<input type="hidden" id="fileguid" name="fileguid" />
										<input type="hidden" id="filename" name="filename" />
									</div>
						        </div>
					        </div>
				        <?php } ?>

			            <?php if (isset($order_data["vendor_comment"]) && !empty(trim($order_data["vendor_comment"]))){ ?>
				            <div class="callout alert" data-closable="fade-out">
				                <p>Message from Service Provider</p>
				                <p><?php echo $order_data['vendor_comment']; ?></p>
				                <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
									<span aria-hidden="true">&times;</span>
								</button>
				            </div>
			            <?php } ?>

			            <div class="row" id="review-comments">
			            	<h4>Order Comments</h4>
			            	<div class="large-12 columns">
					            <?php 
					             $args = array(
									'post_id' => $order_id,
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
										'post_id' => $order_id,
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

				        <div class="row order-overview">
				        	<h4>Order Overview</h4>
				        	<span class="order-edit-link"><a href="<?php echo $order_data['serviceURL']; ?>?order-id=<?php echo $order_id; ?>" title="Edit Order <?php echo $order_id; ?>">Edit</a></span>
				        	<p>Modified: <?php echo get_the_modified_date('F j, Y', $_GET['order-id']); ?> <?php echo get_the_modified_date('g:ia', $_GET['order-id']); ?> by <?php echo $user_info->display_name; ?></p>
				        	<div class="columns large-4">
				        		<div class="row">
						            <div class="columns large-12">
						                <p>Name</p>
						                <p><?php echo get_the_title($order_id); ?></p>
						            </div>
						            <?php if (isset($order_data["brand"]) && !empty($order_data["brand"])){ ?>
						            <div class="columns large-12">
						                <p>Brand</p>
						                <p><?php echo $order_data['brand']; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["impressions"]) && !empty($order_data["impressions"])){ ?>
						            <div class="columns large-12">
						                <p>Impressions</p>
						                <p><?php echo $order_data['impressions']; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["cpm"]) && !empty($order_data["cpm"])){ ?>
						            <div class="columns large-12">
						                <p>CPM</p>
						                <p><?php echo $order_data['cpm']; ?></p>
						            </div>
						            <?php } ?>
						            <div class="columns large-12">
						                <p>Type</p>
						                <p><?php echo $order_data['type']; ?></p>
						            </div>
						            <?php if (isset($order_data["tactic"]) && !empty($order_data["tactic"])){ ?>
						            <div class="columns large-12">
						                <p>Tactic</p>
						                <p><?php echo $order_data['tactic']; ?></p>
						            </div>
						             <?php } ?>
						            <?php if (isset($order_data["quantity"]) && !empty($order_data["quantity"])){ ?>
							            <div class="columns large-12">
							                <p>Quantity</p>
							                <p><?php echo number_format((int)$order_data["quantity"]); ?></p>
							            </div>
						            <?php } ?>
						            <?php if (isset($order_data["sku"]) && !empty($order_data["sku"])){ ?>
							            <div class="columns large-12">
							                <p>SKU</p>
							                <p><?php echo $order_data["sku"] ?></p>
							            </div>
						            <?php } ?>
						            <?php if (isset($order_data["marketdate"]) && !empty($order_data["marketdate"])){ ?>
							            <div class="columns large-12">
							                <p>Market Date</p>
							                <p><?php echo $order_data["marketdate"]; ?></p>
							            </div>
						            <?php } ?>
									<?php if (isset($order_data["marketdate_out_cycle"]) && !empty($order_data["marketdate_out_cycle"])){ ?>
							            <div class="columns large-12">
							                <p>Out of Cycle Market Date</p>
							                <p><?php echo $order_data["marketdate_out_cycle"]; ?></p>
							            </div>
						            <?php } ?>
						            <?php if (isset($order_data["marketdate2"]) && !empty($order_data["marketdate2"])){ ?>
							            <div class="columns large-12">
							                <p>Second Preference Date</p>
							                <p><?php echo $order_data["marketdate2"]; ?></p>
							            </div>
						            <?php } ?>
						            <?php if (isset($order_data["timestart"]) && !empty($order_data["timestart"])){ ?>
							            <div class="columns large-12">
							                <p>Market Date</p>
							                <p><?php echo $order_data["timestart"]; ?></p>
							            </div>
						            <?php } ?>
						            <?php if (isset($order_data["timeend"]) && !empty($order_data["timeend"])){ ?>
							            <div class="columns large-12">
							                <p>Market Date</p>
							                <p><?php echo $order_data["timeend"]; ?></p>
							            </div>
						            <?php } ?>

						            <?php if (isset($order_data["enddate"]) && !empty($order_data["enddate"])){ ?>
							            <div class="columns large-12">
							                <p>End Date</p>
							                <p><?php echo $order_data["enddate"]; ?></p>
							            </div>
						            <?php } ?>

						            <?php if (isset($order_data["demographics"]) && !empty($order_data["demographics"])){ ?>
						            <div class="columns large-12">
						                <p>Demographics</p>
						                <p><?php 
						                $demographics = maybe_unserialize($order_data['demographics']);
										if(is_string($demographics)){
											$demographics = str_replace(',0', '@', $demographics);
									    	$demographics = str_replace(',', '^', $demographics);
									    	$demographics = str_replace('@', ',0', $demographics);
								        	$demographics = explode('^', $demographics);
								        }
										$htmldemographics = '<ul>';
										foreach($demographics as $demographic){
											$htmldemographics .= '<li>' . $demographic . '</li>';
										}
										$htmldemographics .= '</ul>';
										echo $htmldemographics; ?></p>
						            </div>
						             <?php } ?>

						            <?php if (isset($order_data["filenameseg"]) && !empty($order_data["filenameseg"])){ ?>
						            <div class="columns large-12">
						                <p>Custom Profile</p>
						                <p><a href="<?php echo $order_data["filenameseg"] ?>" target="_blank">Download Profile</a></p>
						            </div>
						            <?php } else { ?>
							            <?php if (isset($order_data["profilegender"]) && !empty($order_data["profilegender"])){ ?>
								            <div class="columns large-12">
								                <p>Profile Gender</p>
								                <p><?php echo $order_data["profilegender"]; ?></p>
								            </div>
							            <?php } ?>
							            <?php if (isset($order_data["profileage"]) && !empty($order_data["profileage"])){ ?>
								            <div class="columns large-12">
								                <p>Profile Age</p>
								                <p><?php echo $order_data["profileage"]; ?></p>
								            </div>
							            <?php } ?>
							            <?php if (isset($order_data["profilechildren"]) && !empty($order_data["profilechildren"])){ ?>
								            <div class="columns large-12">
								                <p>Profile Children</p>
								                <p><?php echo $order_data["profilechildren"]; ?></p>
								            </div>
							            <?php } ?>
							            <?php if (isset($order_data["profileincome"]) && !empty($order_data["profileincome"])){ ?>
								            <div class="columns large-12">
								                <p>Profile Income</p>
								                <p><?php echo htmlentities($order_data["profileincome"]); ?></p>
								            </div>
							            <?php } ?>
						            <?php } ?>
						            <?php if (isset($order_data["destinationurl"]) && !empty($order_data["destinationurl"])){ ?>
						            <div class="columns large-12">
						                <p>Destination URL</p>
						                <p><?php echo $order_data["destinationurl"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["producttype"]) && !empty($order_data["producttype"])){ ?>
						            <div class="columns large-12">
						                <p>Product Type</p>
						                <p><?php echo $order_data["producttype"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["categorytype"]) && !empty($order_data["categorytype"])){ ?>
						            <div class="columns large-12">
						                <p>Category Type</p>
						                <p><?php echo $order_data["categorytype"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["productname"]) && !empty($order_data["productname"])){ ?>
						            <div class="columns large-12">
						                <p>Product Name</p>
						                <p><?php echo $order_data["productname"]; ?></p>
						            </div>
						            <?php } ?>
									<?php if (isset($order_data["productdesc"]) && !empty($order_data["productdesc"])){ ?>
						            <div class="columns large-12">
						                <p>Product Desc</p>
						                <p><?php echo $order_data["productdesc"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["productunit"]) && !empty($order_data["productunit"])){ ?>
						            <div class="columns large-12">
						                <p>Flavor/Unit Size/Pack Size</p>
						                <p><?php echo $order_data["productunit"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["productupc"]) && !empty($order_data["productupc"])){ ?>
						            <div class="columns large-12">
						                <p>Consumer UPC</p>
						                <p><?php echo $order_data["productupc"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["productsampled"]) && !empty($order_data["productsampled"])){ ?>
						            <div class="columns large-12">
						                <p>Product Sampled</p>
						                <p><?php echo $order_data["productsampled"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["productfeatured"]) && !empty($order_data["productfeatured"])){ ?>
						            <div class="columns large-12">
						                <p>Product Featured</p>
						                <p><?php echo $order_data["productfeatured"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["productbackup"]) && !empty($order_data["productbackup"])){ ?>
						            <div class="columns large-12">
						                <p>Product Backup</p>
						                <p><?php echo $order_data["productbackup"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["productdistribution"]) && !empty($order_data["productdistribution"])){ ?>
						            <div class="columns large-12">
						                <p>Product Distribution</p>
						                <p><?php echo $order_data["productdistribution"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["sellingpoints"]) && !empty($order_data["sellingpoints"])){ ?>
						            <div class="columns large-12">
						                <p>Selling Points</p>
						                <p><?php echo $order_data["sellingpoints"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["preparation"]) && !empty($order_data["preparation"])){ ?>
						            <div class="columns large-12">
						                <p>Preparation</p>
						                <p><?php echo $order_data["preparation"]; ?></p>
						            </div>
						            <?php } ?>
						             <?php if (isset($order_data["equipment"]) && !empty($order_data["equipment"])){ ?>
						            <div class="columns large-12">
						                <p>Equipment</p>
						                <p><?php echo $order_data["equipment"]; ?></p>
						            </div>
						            <?php } ?>
						             <?php if (isset($order_data["distributiongoal"]) && !empty($order_data["distributiongoal"])){ ?>
						            <div class="columns large-12">
						                <p>Distribution Goal</p>
						                <p><?php echo $order_data["distributiongoal"]; ?></p>
						            </div>
						            <?php } ?>
									<?php if (isset($order_data["sasatshelftactic"]) && !empty($order_data["sasatshelftactic"])){ ?>
						            <div class="columns large-12">
						                <p>SAS At-Shelf Tactic</p>
						                <p><?php echo $order_data['sasatshelftactic']; ?></p>
						            </div>
									<?php } ?>
						            <?php if (isset($order_data["sasatshelfquantity"]) && !empty($order_data["sasatshelfquantity"])){ ?>
									<div class="columns large-12">
						                <p>SAS At-Shelf Quantity</p>
						                <p><?php echo $order_data['sasatshelfquantity']; ?></p>
						            </div>
						            <?php } ?>
									<?php if(isset($order_data["at-shelf-tactic-custom"]) && !empty($order_data["at-shelf-tactic-custom"])) { ?>
						            <div class="columns large-12">
						            	<p>Creative</p>
						                <p>Red/E to create SAS At-Shelf Creative</p>
						            </div>
						            <?php } ?>
									<?php if(isset($order_data["aislequantity"]) && !empty($order_data["aislequantity"])) { ?>
						            <div class="columns large-12">
						            	<p>Multiple aisle placement</p>
						                 <p><?php echo $order_data['aislequantity']; ?></p>
						            </div>
						            <?php } ?>
									<?php if(isset($order_data["aisleplacement"]) && !empty($order_data["aisleplacement"])) { ?>
						            <div class="columns large-12">
						            	<p>Placement Instructions</p>
						                 <p><?php echo $order_data['aisleplacement']; ?></p>
						            </div>
						            <?php } ?>

						            

					           </div>
					       </div>
					       <div class="columns large-4">
				        		<div class="row">
				        			<?php if (isset($order_data["vendor_reports"]) && !empty($order_data["vendor_reports"])){ ?>
						            <div class="columns large-12">
						                <p>Report</p>
						                <p><a href="<?php echo $order_data["vendor_reports"] ?>" target="_blank">View Report</a></p>
						            </div>
						            <?php } ?>
						            <?php if(!isset($order_data["rede_creative"]) && isset($order_data["tactic-custom"]) && !empty($order_data["tactic-custom"])) { ?>
						            <div class="columns large-12">
						            	<p>Creative</p>
						                <p>Red/E to create</p>
						            </div>
						            <?php } ?>
						            <?php if(isset($order_data["pfid"]) && !empty($order_data["pfid"])) { ?>
						            <div class="columns large-12">
						                <p>Creative</p>
						                <p><a href="http://digitalprint.alliedprinting.com/ShopperMarketingHub/PMGetPdfProof.aspx?DocID=<?php echo $order_data["pfid"]; ?>&UserName=dalim20151" target="_blank">Download Proof</a></p>
						            </div>
						            <?php } ?>
						            <?php if(isset($order_data["_pageflex_order"]) && !empty($order_data["_pageflex_order"])) { 
						           	$pf_order = maybe_unserialize($order_data['_pageflex_order']); ?>
						            <div class="columns large-12">
						                <p>Pageflex Order ID</p>
						                <p><?php echo $pf_order[1]; ?></p>
						            </div>
						            <?php } ?>

						            <?php if (isset($order_data["rede_creative"]) && !empty($order_data["rede_creative"])){ ?>
						            <div class="columns large-12">
						                <p>Red/E Creative</p>
						                <p><a href="<?php echo $order_data["rede_creative"] ?>" target="_blank">Download Creative</a></p>
						            </div>
						            <?php } ?>

						            <?php if (isset($order_data["filename"]) && !empty($order_data["filename"])){ ?>
						            <div class="columns large-12">
						                <p>Creative</p>
						                <p><a href="<?php echo $order_data["filename"] ?>" target="_blank">Download Creative</a></p>
						            </div>
						            <?php } ?>

						             <?php if (isset($order_data["filenameupgrade2"]) && !empty($order_data["filenameupgrade2"])){ ?>
						            <div class="columns large-12">
						                <p data-tooltip aria-haspopup="true" class="has-tip" data-disable-hover="false" tabindex="1" title="Tear Pad / Coupon Placement.">Plus Up 2</p>
						                <p><a href="<?php echo $order_data["filenameupgrade2"] ?>" target="_blank">Download File</a></p>
						            </div>
						            <?php } ?>

						             <?php if (isset($order_data["filenameupgrade3"]) && !empty($order_data["filenameupgrade3"])){ ?>
						            <div class="columns large-12">
						                <p data-tooltip aria-haspopup="true" class="has-tip" data-disable-hover="false" tabindex="1" title="Dual Sided Creative Execution.">Plus Up 3</p>
						                <p><a href="<?php echo $order_data["filenameupgrade3"] ?>" target="_blank">Download File</a></p>
						            </div>
						            <?php } ?>

						            <?php if (isset($order_data["filenamebillboards"]) && !empty($order_data["filenamebillboards"])){ ?>
						            <div class="columns large-12">
						                <p>Billboards</p>
						                <p><a href="<?php echo $order_data["filenamebillboards"] ?>" target="_blank">Download File</a></p>
						            </div>
						            <?php } ?>

						            <?php if (isset($order_data["billboardcount"]) && !empty($order_data["billboardcount"])){ ?>
							            <div class="columns large-12">
							                <p>Billboard Count</p>
							                <p><?php echo number_format((int)$order_data['billboardcount']); ?></p>
							            </div>
						            <?php } ?>

				        			<?php if (isset($order_data["customlistname"]) && !empty($order_data["customlistname"])){ ?>
							            <div class="columns large-12">
							                <p>Custom Retailer List</p>
							                <a href="<?php echo $order_data["customlistname"] ?>" target="_blank">Download List</a>
							            </div>
						            <?php } ?>

						            <?php if (isset($order_data["customshoppername"]) && !empty($order_data["customshoppername"])){ ?>
							            <div class="columns large-12">
							                <p>Custom Retailer Audience</p>
							                <a href="<?php echo $order_data["customshoppername"] ?>" target="_blank">Download List</a>
							            </div>
						            <?php } ?>

						            <?php if (isset($order_data["filenameaudience"]) && !empty($order_data["filenameaudience"])){ ?>
							            <div class="columns large-12">
							                <p>Custom Audience</p>
							                <a href="<?php echo $order_data["filenameaudience"] ?>" target="_blank">Download List</a>
							            </div>
						            <?php } ?>

						            <?php if (isset($order_data["filenamegeography"]) && !empty($order_data["filenamegeography"])){ ?>
							            <div class="columns large-12">
							                <p>Custom Geography</p>
							                <a href="<?php echo $order_data["filenamegeography"] ?>" target="_blank">Download List</a>
							            </div>
						            <?php } ?>

						            <?php if (isset($order_data["filenamesku"]) && !empty($order_data["filenamesku"])){ ?>
							            <div class="columns large-12">
							                <p>Products List</p>
							                <a href="<?php echo $order_data["filenamesku"] ?>" target="_blank">Download List</a>
							            </div>
						            <?php } ?>

						            <?php if (isset($order_data["filenameproductbeauty"]) && !empty($order_data["filenameproductbeauty"])){ ?>
							            <div class="columns large-12">
							                <p>Beauty Shot</p>
							                <a href="<?php echo $order_data["filenameproductbeauty"] ?>" target="_blank">Download List</a>
							            </div>
						            <?php } ?>

						            <?php if (isset($order_data["filenameproductshot"]) && !empty($order_data["filenameproductshot"])){ ?>
							            <div class="columns large-12">
							                <p>Product Shot</p>
							                <a href="<?php echo $order_data["filenameproductshot"] ?>" target="_blank">Download List</a>
							            </div>
						            <?php } ?>

						            <?php if (isset($order_data["filenameproductlogo"]) && !empty($order_data["filenameproductlogo"])){ ?>
							            <div class="columns large-12">
							                <p>Product Logo</p>
							                <a href="<?php echo $order_data["filenameproductlogo"] ?>" target="_blank">Download List</a>
							            </div>
						            <?php } ?>

						            <?php if (isset($order_data["dma"]) && !empty($order_data["dma"])){ ?>
							            <div class="columns large-12">
							                <p>DMAs</p>
							                <ul>
								                <?php
										        $dmas = maybe_unserialize($order_data["dma"]);
										        if(is_string($dmas)){
										        	$dmas = str_replace(', ', '@', $dmas);
										        	$dmas = str_replace(',', '^', $dmas);
										        	$dmas = str_replace('@', ', ', $dmas);
										        	$dmas = explode('^', $dmas);
										        }
										        // print_r($dmas);
										        $alldmas = get_dmas();

								            	foreach ($dmas as $dma){
								            		echo '<li>' . $dma . '</li>';
								            	}
								                ?>
								            </ul>
							            </div>
						            <?php } ?>
						            <?php if (isset($order_data["store"]) && !empty($order_data["store"])){ ?>
							            <div class="columns large-12">
							                <p>Stores</p>
							                <?php
					                			$stores = maybe_unserialize($order_data["store"]);
										        if(is_string($stores)){
										        	$stores = str_replace(', ', '@', $stores);
											    	$stores = str_replace(',', '^', $stores);
											    	$stores = str_replace('@', ', ', $stores);
											    	$stores = explode('^', $stores);
										        }

										       // die();
										        $htmlstores = '<ul>';
										        if($order_data['type'] === 'Security Shroud'){
										        	$allstores = get_dmas();
										        	foreach ($stores as $storename){
										        		if(!is_numeric($storename)){
															$htmlstores .= '<li>' . $storename . '</li>';
														}
													}

												} else if($order_data['type'] === 'Sampling'){
										        	foreach ($stores as $storename){
										        		if(!is_numeric($storename)){
															$htmlstores .= '<li>' . $storename . '</li>';
														}
													}

										        } else {
											        if($order_data['type'] === 'Out of Home'){
											            $allstores = get_ooo_stores();
											        } else {
											        	$allstores = get_stores();
											        }
											        // print_r($allstores);
											        // die();
											        foreach ($allstores as $storetype=>$storeGroup){
											        	foreach ($storeGroup as $storeList){
											            	foreach ($storeList as $store){
											            		if($order_data['type'] === 'Out of Home'){
																	if($store['num'] !== 0 && in_array($store['id'], $stores)){
												            			$htmlstores .= '<li>' . $store['name'] . '</li>';
												            		}
												            	// } else if($order_data['type'] === 'Security Shroud'){
												            	// 	if(!is_numeric($storeList['location']) && in_array($storeList['location'], $stores)){
												            	// 		$htmlstores .= '<li>' . $storeList['location'] . '</li>';
												            	// 	}
											            		} else {
												            		if($store['num'] !== 0 && in_array($store['val'], $stores)){
												            			$htmlstores .= '<li>' . $store['name'] . '</li>';
												            		}
												            	}
											            	}
											            }
											        }
											    }
										        $htmlstores .= '</ul>';

										        echo $htmlstores;
							                ?>
							            </div>
							        <?php } ?>
									<?php if (isset($order_data["storedepartment"]) && !empty($order_data["storedepartment"])){ ?>
										<div class="columns large-12">
							                <p>Store Departments</p>
											<?php
											$storedepartments = maybe_unserialize($order_data["storedepartment"]);
											if(is_string($storedepartments)){
												$storedepartments = str_replace(', ', '@', $storedepartments);
												$storedepartments = str_replace(',', '^', $storedepartments);
												$storedepartments = str_replace('@', ', ', $storedepartments);
												$storedepartments = explode('^', $storedepartments);
											}
											$htmlstores = '<ul>';
											foreach ($storedepartments as $storedepartmentname){
												if(!is_numeric($storedepartmentname)){
													$htmlstores .= '<li>' . $storedepartmentname . '</li>';
												}
											}
											$htmlstores .= '</ul>';
											echo $htmlstores;
											?>
										</div>
									<?php } ?>
						           <?php if (isset($order_data["storecount"]) && !empty($order_data["storecount"])){ ?>
							            <div class="columns large-12">
							                <p>Store Count</p>
							                <p><?php echo number_format((int)$order_data['storecount']); ?></p>
							            </div>
						            <?php } ?>

				        		</div>
				        	</div>
					       <div class="columns large-4">
				        		<div class="row">
							        <?php if (isset($order_data["destination"]) && !empty($order_data["destination"])){ ?>
						            <div class="columns large-12">
						                <p>Destination</p>
						                <p><?php echo $order_data["destination"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["dest-email"]) && !empty($order_data["dest-email"])){ ?>
						            <div class="columns large-12">
						                <p>Destination Email</p>
						                <p><?php echo $order_data["dest-email"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["dest-quantity"]) && !empty($order_data["dest-quantity"])){ ?>
						            <div class="columns large-12">
						                <p>Destination Quantity</p>
						                <p><?php echo $order_data["dest-quantity"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["specialinstructions"]) && !empty($order_data["specialinstructions"])){ ?>
						            <div class="columns large-12">
						                <p>Special Instructions</p>
						                <p><?php echo $order_data["specialinstructions"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["shippingaddress"]) && !empty($order_data["shippingaddress"])){ ?>
						            <div class="columns large-12">
						                <p>Product Coupon</p>
						                <p><?php echo $order_data["Shipping Address"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["shippinginstructions"]) && !empty($order_data["shippinginstructions"])){ ?>
						            <div class="columns large-12">
						                <p>Shipping Instructions</p>
						                <p><?php echo $order_data["shippinginstructions"]; ?></p>
						            </div>
						            <?php } ?>

				        			<?php if (isset($order_data["productcoupon"]) && !empty($order_data["productcoupon"])){ ?>
						            <div class="columns large-12">
						                <p>Product Coupon</p>
						                <p><?php echo $order_data["productcoupon"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["productsupplies"]) && !empty($order_data["productsupplies"])){ ?>
						            <div class="columns large-12">
						                <p>Product Supplies</p>
						                <p><?php echo $order_data["productsupplies"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["productcta"]) && !empty($order_data["productcta"])){ ?>
						            <div class="columns large-12">
						                <p>Product CTA</p>
						                <p><?php echo $order_data["productcta"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["productheadline"]) && !empty($order_data["productheadline"])){ ?>
						            <div class="columns large-12">
						                <p>Product Headline</p>
						                <p><?php echo $order_data["productheadline"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["productsubhead"]) && !empty($order_data["productsubhead"])){ ?>
						            <div class="columns large-12">
						                <p>Product Subhead</p>
						                <p><?php echo $order_data["productsubhead"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["productlegal"]) && !empty($order_data["productlegal"])){ ?>
						            <div class="columns large-12">
						                <p>Product Legal</p>
						                <p><?php echo $order_data["productlegal"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["redecollateral"]) && !empty($order_data["redecollateral"])){ ?>
						            <div class="columns large-12">
						                <p>RedE Collateral</p>
						                <p><?php echo $order_data["redecollateral"]; ?></p>
						            </div>
						            <?php } ?>
						            <?php if (isset($order_data["productcollateral"]) && !empty($order_data["productcollateral"])){ ?>
						            <div class="columns large-12">
						                <p>Product Collateral</p>
						                <p><?php echo $order_data["productcollateral"]; ?></p>
						            </div>
						            <?php } ?>
			        			<?php if (isset($order_data["optimization"]) && !empty($order_data["optimization"])){ ?>
						            <div class="columns large-12">
						                <p>Optimization Preferences</p>
						                <p><?php echo $order_data["optimization"]; ?></p>
						            </div>
					            <?php } ?>
					            <?php if (isset($order_data["campaignobjective"]) && !empty($order_data["campaignobjective"])){ ?>
						            <div class="columns large-12">
						                <p>Objective</p>
						                <p><?php echo $order_data["campaignobjective"]; ?></p>
						            </div>
					            <?php } ?>
					            <?php if (isset($order_data["campaignpurpose"]) && !empty($order_data["campaignpurpose"])){ ?>
						            <div class="columns large-12">
						                <p>Purpose</p>
						                <p><?php echo $order_data["campaignpurpose"]; ?></p>
						            </div>
					            <?php } ?>
					            <?php if (isset($order_data["campaigntiming"]) && !empty($order_data["campaigntiming"])){ ?>
						            <div class="columns large-12">
						                <p>Timing</p>
						                <p><?php echo $order_data["campaigntiming"]; ?></p>
						            </div>
					            <?php } ?>
					            <?php if (isset($order_data["otherdetails"]) && !empty($order_data["otherdetails"])){ ?>
						            <div class="columns large-12">
						                <p>Other Details</p>
						                <p><?php echo $order_data["otherdetails"]; ?></p>
						            </div>
					            <?php } ?>
					            <?php if (isset($order_data["otherconsiderations"]) && !empty($order_data["otherconsiderations"])){ ?>
						            <div class="columns large-12">
						                <p>Other Considerations</p>
						                <p><?php echo $order_data["otherconsiderations"]; ?></p>
						            </div>
					            <?php } ?>
					            <?php if (isset($order_data["vendor_comment"]) && !empty($order_data["vendor_comment"])){ ?>
						            <div class="columns large-12">
						                <p>Vendor Comments</p>
						                <p><?php echo $order_data['vendor_comment']; ?></p>
						            </div>
					            <?php } ?>
						            <?php if (isset($order_data["budget"]) && !empty($order_data["budget"])){ ?>
							            <div class="columns large-12">
							                <p>Budget</p>
							                <p><?php echo $order_data["budget"]; ?></p>
							            </div>
						            <?php } ?>
						            <?php if (isset($order_data["costperstore"]) && !empty($order_data["costperstore"])){ ?>
							            <div class="columns large-12">
							                <p>Cost per Store</p>
							                <p><?php echo $order_data["costperstore"]; ?></p>
							            </div>
						            <?php } ?>
						            <div class="columns large-12">
						                <p>Total</p>
						                <p><?php echo $order_data["total"];  ?></p>
						            </div>
						        </div>
					        </div>
					    </div>
				    </div>
				    <!-- Modal - Approve Creative -->
				    <div class="reveal" data-animation-in="slide-in-down ease-in-out" data-reveal id="approveModal" aria-hidden="true" aria-labelledby="approveModal" role="dialog" tabindex="-1">
				        <div class="modal-dialog">
				            <div class="modal-content">
				                <div class="modal-header" style="background-color:#AD2724;">
									<?php if($order_data['order_status'] === "Review Creative"){ ?>
				                    	<p class="modal-title">Approve Creative</p>
				                    <?php } ?>
				                    <?php if($order_data['order_status'] === "Review Proof"){ ?>
				                    	<p class="modal-title">Approve Proof</p>
				                    <?php } ?>
				                    <?php if($order_data['order_status'] === "Review Order"){ ?>
				                    	<p class="modal-title">Approve Program</p>
				                    <?php } ?>
				                    <?php if($order_data['order_status'] === "Confirm Details"){ ?>
				                    	<p class="modal-title">Approve Program</p>
				                    <?php } ?>
				                    <?php if($order_data['order_status'] === "Review Brief"){ ?>
				                    	<p class="modal-title">Send Creative</p>
				                    <?php } ?>
				                    <?php if($order_data['order_status'] === "Awaiting Brief"){ ?>
				                    	<p class="modal-title">Send Brief</p>
				                    <?php } ?>
				                    <?php if($order_data['order_status'] === "Needs Creative"){ ?>
				                    	<p class="modal-title">Send Brief</p>
				                    <?php } ?>
				                    <?php if($order_data['order_status'] === "Creative Added"){ ?>
				                    	<p class="modal-title">Approve Creative</p>
				                    <?php } ?>
				                    <?php if($order_data['order_status'] === "Awaiting Report"){ ?>
				                    	<p class="modal-title">Send Report</p>
				                    <?php } ?>
				                </div>
				                <div class="modal-body">
				                    <p>Please provide any additional feedback.</p>
				                    <textarea class="form-control" id="approveComment" rows="3"></textarea>
				                </div>
				                <div class="modal-footer">
				                    <button type="button" class="button secondary" data-close>Close</button>
				                    <?php if($order_data['order_status'] === "Confirm Details"){ ?>
				                    	<a class="button success" data-open="legal-modal">Approve</a>
				                     <?php } else if($order_data['order_status'] === "Needs Creative"){ ?>
				                    	<a class="button success" data-open="legal-modal">Send</a>
				                    <?php } else if($order_data['order_status'] === "Awaiting Report"){ ?>
				                    	<a class="button success" data-open="legal-modal">Send</a>
				                    <?php } else { ?>
				                    	<button type="button" class="button success" id="approveCreative" style="color:#fff;">Approve</button>
				                    <?php } ?>
				                	<?php $ajax_nonce = wp_create_nonce( "approve-nonce" ); ?>
                        			<input type="hidden" id="approve-nonce" value="<?php echo $ajax_nonce; ?>"> 
                        			<!-- <input type="hidden" id="review-type" value="approve"> -->
				                </div>
				            </div>
				        </div>
				    </div>
				    <!-- End Modal -->
				    <!-- Modal - Conditional Creative -->
				    <div class="reveal" data-animation-in="slide-in-down ease-in-out" data-reveal id="conditionalModal" aria-hidden="true" aria-labelledby="conditionalModal" role="dialog" tabindex="-1">
				        <div class="modal-dialog">
				            <div class="modal-content">
				                <div class="modal-header" style="background-color:#526069;">
				                
				                    <?php if($order_data['order_status'] === "Review Creative"){ ?>
				                    	<p class="modal-title">Conditionally Approve Creative</p>
				                    <?php } ?>
				                    <?php if($order_data['order_status'] === "Review Proof"){ ?>
				                    	<p class="modal-title">Conditionally Approve Proof</p>
				                    <?php } ?>
				                    <?php if($order_data['order_status'] === "Review Order"){ ?>
				                    	<p class="modal-title">Conditionally Approve Program</p>
				                    <?php } ?>
				                    <?php if($order_data['order_status'] === "Confirm Details"){ ?>
				                    	<p class="modal-title">Confirm Approval Details</p>
				                    <?php } ?>
				                    <?php if($order_data['order_status'] === "Review Brief"){ ?>
				                    	<p class="modal-title">Request More Information</p>
				                    <?php } ?>
				                    <?php if($order_data['order_status'] === "Needs Creative"){ ?>
				                    	<p class="modal-title">Conditionally Approve Program</p>
				                    <?php } ?>
				                    <?php if($order_data['order_status'] === "Creative Added"){ ?>
				                    	<p class="modal-title">Conditionally Approve Creative</p>
				                    <?php } ?>
				                    <?php if($order_data['order_status'] === "Awaiting Report"){ ?>
				                    	<p class="modal-title">Send Report</p>
				                    <?php } ?>
				                </div>
				                <div class="modal-body">
				                	<?php if(!in_array($order_data['order_status'], array('Creative Added','Confirm Details')) && in_array($order_data['type'], array('Paid Social', 'Mobile Media', 'Coupon Booster'))) { ?>
					                	<?php if(!isset($order_data['impressions']) || empty($order_data['impressions'])) { ?>
					                	<div class="row">
	    									<div class="columns small-3">
							                	<label for="impressions" class="text-right middle">Impressions</label>
							                </div>
							                <div class="columns small-9">
							                	<input type="text" class="form-control" id="impressions" name="impressions">
							                </div>
							            </div>
							            <?php } ?>
							            <?php if(!isset($order_data['cpm']) || empty($order_data['cpm'])) { ?>
							            <div class="row">
	    									<div class="columns small-3">
							                	<label for="cpm" class="text-right middle">CPM</label>
							                </div>
							                <div class="columns small-9">
							                	<input type="text" class="form-control" id="cpm" name="cpm">
							                </div>
							            </div>
							            <?php } ?>
						            <?php } ?>
				                    <p>Please provide feedback as to why you are conditionally approving the order.</p>
				                    <textarea class="form-control" id="conditionalComment" rows="3"></textarea>
				                </div>
				                <div class="modal-footer">
				                    <button type="button" class="button secondary" data-close>Close</button>
				                    <button type="button" class="button success" id="conditionalCreative" style="color:#fff;">Submit Comment</button>
				                	<?php $ajax_nonce = wp_create_nonce( "conditional-nonce" ); ?>
                        			<input type="hidden" id="conditional-nonce" value="<?php echo $ajax_nonce; ?>"> 
                        			<!-- <input type="hidden" id="review-type" value="deny"> -->
				                </div>
				            </div>
				        </div>
				    </div>
				    <!-- End Modal -->
				    <!-- Modal - Deny Creative -->
				    <div class="reveal" data-animation-in="slide-in-down ease-in-out" data-reveal id="denyModal" aria-hidden="true" aria-labelledby="denyModal" role="dialog" tabindex="-1">
				        <div class="modal-dialog">
				            <div class="modal-content">
				                <div class="modal-header" style="background-color:#526069;">
				                    <p class="modal-title">Reject/Request Resubmit</p>
				                </div>
				                <div class="modal-body">
				                    <p>Please provide feedback as to why you are rejecting the order.</p>
				                    <textarea class="form-control" id="denyCreativeComment" rows="3"></textarea>
				                </div>
				                <div class="modal-footer">
				                    <button type="button" class="button secondary" data-close>Close</button>
				                    <button type="button" class="button success" id="denyCreative" style="color:#fff;">Reject</button>
				                	<?php $ajax_nonce = wp_create_nonce( "deny-nonce" ); ?>
                        			<input type="hidden" id="deny-nonce" value="<?php echo $ajax_nonce; ?>"> 
                        			<!-- <input type="hidden" id="review-type" value="deny"> -->
				                </div>
				            </div>
				        </div>
				    </div>
				    <!-- End Modal -->
                    <div class="reveal" id="legal-modal" data-reveal>
                        <?php get_template_part( 'template-parts/legal' ); ?>
                        <button type="button" class="button success" id="approveCreative" style="color:#fff;">Approve</button>
                        <button class="close-button" data-close aria-label="Close modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </article>
            <?php endwhile; ?>
        </main>
    </div>
</div>
<?php
get_footer();