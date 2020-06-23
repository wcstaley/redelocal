<?php
/* Template Name: Account */

get_header(); ?>

<div class="main-container">
    <div class="main-grid">
        <main class="main-content-full-width">
            <?php
            while ( have_posts() ){
                the_post();
                $post_id = get_the_ID();
                $current_user = wp_get_current_user();
				$user_id = $current_user->ID;
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                  	<header>
	                    <h1 class="entry-title"><?php the_title(); ?></h1>
	                </header>
					<form method="post" runat="server">
						<div class="row">
							<div class="medium-6 columns">
								<label class="control-label" for="inputFirstName">First Name</label>
								<input type="text" class="form-control" id="inputFirstName" name="first_name" value="<?php echo $current_user->first_name ?>" autocomplete="off">
							</div>
							<div class="medium-6 columns">
								<label class="control-label" for="inputLastName">Last Name</label>
								<input type="text" class="form-control" id="inputLasttName" name="last_name" value="<?php echo $current_user->last_name ?>" autocomplete="off">
							</div>
						</div>
						<div class="row">
							<div class="medium-12 columns">
								<label class="control-label" for="inputEmail">E-Mail</label>
								<input type="email" class="form-control" id="inputEmail" name="user_email" value="<?php echo $current_user->user_email ?>" autocomplete="off">
							</div>
						</div>
						<div class="row">
							<div class="medium-12 columns">
								<label class="control-label" for="inputOldPassword">Old Password</label>
								<input type="password" class="form-control" id="inputOldPassword" name="inputOldPassword" placeholder="Enter your old password" autocomplete="off">
							</div>
						</div>
						<div class="row">
							<div class="medium-12 columns">
								<label class="control-label" for="inputNewPassword">New Password</label>
								<input type="password" class="form-control" id="inputNewPassword" name="inputNewPassword" placeholder="Enter your new password" autocomplete="off">
							</div>
						</div>
						<div class="row">
							<div class="medium-12 columns">
								<?php $ajax_nonce = wp_create_nonce( "account-nonce" ); ?>
	                            <input type="hidden" id="account-nonce" value="<?php echo $ajax_nonce; ?>"> 
								<button type="submit" class="button success btn-update-account">Update</button>
							</div>
						</div>
					</form>
				</article>
        	<?php } //end while ?>
        </main>
    </div>
</div>
<?php
get_footer();
