<?php
/*
Template Name: Asset Portal - Sales Dashboard
*/

get_header(); ?>

<div class="main-container">
	<div class="main-grid">
		<main class="main-content-full-width">
			<div class="row top-row" data-equalizer data-equalize-on="medium">
				<div class="columns small-12 large-4 quick-start" data-equalizer-watch>
					<h3>Quick Start</h3>
					<?php
					$quickstart_overview = get_post_meta($post->ID, 'quickstart_overview', true);
					echo '<p>' . $quickstart_overview . '</p>';
					?>
					<form action="">
						<div class="result-overlay"></div>
						<div class="row">
							<div class="columns small-12 large-4">
								<label for="objective_options">Objective</label>
							</div>
							<div class="columns small-12 large-8">
								<select name="objective_options" id="objective_options">
									<?php $options = get_post_meta($post->ID, 'objective_options', true); 
                                    // print_r($options); die(); 
                                    foreach($options as $option){ ?>
									<option value="<?php echo $option; ?>">
										<?php echo $option; ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="columns small-12 large-4">
								<label for="timing_options">Timing</label>
							</div>
							<div class="columns small-12 large-8">
								<select name="timing_options" id="timing_options">
									<?php $options = get_post_meta($post->ID, 'timing_options', true); 
                                    // print_r($options); die(); 
                                    foreach($options as $option){ ?>
									<option value="<?php echo $option; ?>">
										<?php echo $option; ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="columns small-12 large-4">
								<label for="budget_options">Budget</label>
							</div>
							<div class="columns small-12 large-8">
								<select name="budget_options" id="budget_options">
									<?php $options = get_post_meta($post->ID, 'budget_options', true); 
                                    // print_r($options); die(); 
                                    foreach($options as $option){ ?>
									<option value="<?php echo $option; ?>">
										<?php echo $option; ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="columns small-12 large-4">

							</div>
							<div class="columns small-12 large-8">
								<a href="#" class="button success quickstart-submit">View Tactics</a>
							</div>
						</div>
					</form>
				</div>
				<div class="columns small-12 large-8 my-account-wrap" data-equalizer-watch>
					<div class="my-account">
						<h3>My Account</h3>
						<div class="row">
							<div class="columns small-12 large-4">
								<?php 
                                $current_user = wp_get_current_user();
                                $udata = get_userdata( $current_user->ID );
                                $registered = $udata->user_registered;
                                ?>
								<ul>
									<!-- Add users name -->
									<!-- <li>Date Created: <?php echo date( "M Y", strtotime( $registered ) ); ?></li> -->
									<!-- <li>Manager:</li> -->
									<li>Account Name: <?php echo $udata->first_name .  " " . $udata->last_name; ?></li>
                                    <li>Remaining Budget: <span class="remaining-budget"></span></li>
									<li>Active Campaigns:
										<?php echo user_active_campaigns(); ?>
									</li>
								</ul>
							</div>
							<div class="columns small-12 large-8">
							<canvas id="myChart" width="400" height="200"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
            while ( have_posts() ){
                the_post();
            ?>

			<?php } //end while ?>
			<div class="row tactics-row">
				<div class="columns small-12 dash-tactics">
					<h3>Tactics</h3>
					<?php get_template_part( 'template-parts/dashboard', 'services' ) ?>
				</div>
			</div>
			<div class="row campaigns-row">
				<div class="columns small-12 dash-campaigns">
					<?php get_template_part( 'template-parts/dashboard', 'campaigns' ) ?>
				</div>
			</div>

		</main>
	</div>
</div>
<?php
get_footer();
