<?php
// For testing only.
define('WP_USE_THEMES', false);
require_once('../../../../wp-load.php');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content - Type" content="text / html; charset = utf - 8">
		<meta name="viewport" content="width = device - width">
		<meta name="format - detection" content="address = no; email = no; telephone = no">
		<title>Order Details</title>
	</head>
	<body style="background-color: #eee;">
		<table   border="0" cellspacing="0" style="width:100%; height:100%; padding: 10px 0 40px 0; background-color:#eee; color:#eee">
			<tbody>
				<tr>
					<td align="center"  valign="top" style="padding: 15px 15px; height:100%; width:100%;background-color:#eee;">
						<table align="center" style=" width:544px;  padding:0; border-collapse:collapse; border-spacing:0;">
							<tbody>
								<tr>
									<td style="margin:0; padding: 15px; background-color: #fff;font-family: Arial, Helvetica, sans-serif; border-left: 1px solid #cccccc;border-right: 1px solid #cccccc;">
										<table style="width:100%;">
											<tbody>
												<tr>
													<td style="text-align:center; margin:0; padding:0; width:100%; border-collapse:collapse; border-spacing:0;">
														<a href="https://www.rede-marketing.com" style=" text-decoration:none; margin:0; padding:0;"><img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/Logo/rede_100.jpg" alt="Red/E" /></a>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>

								{{ email_button }}

								<tr style="border-left: 1px solid #cccccc;border-right: 1px solid #cccccc;">
									<td style="font-family:Arial, Helvetica, Sans-serif; background-color:#fff; border-bottom: solid 1px #ccc;">
										<table style="background-color:#fff;border-collapse:collapse; border-spacing:0">
											<tr>
												<td style="padding: 10px 20px 30px;">
													<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif; line-height:14px; font-size:14px;color: #666666;text-align:center;">
                                                        <strong>{{ email_head }}</strong>
													</p>
													<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
														{{ email_body }}
													</p>
												</td>
											</tr>
										</table>
									</td>
								</tr>

                                {{ email_order }}
								<tr>
									<td style="line-height:21px; padding:15px 0 0 0;font-family:Arial, Helvetica, Sans-serif; font-size:12px;text-align:center;color: #a1a2a5;">
										&copy; 2017 Red-E. All Rights Reserved.<br/>
                                        <a href="<?php echo home_url('/contact'); ?>" style="color:#B62B27; text-decoration:none;">Contact Us</a>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</body>
</html>
