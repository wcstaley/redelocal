<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="ProofDenied.aspx.cs" Inherits="RedE.Web.Root.RedE.Email.ProofDenied" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
									<td style="margin:0; padding: 15px; background-color: #2a2b2d;font-family: Arial, Helvetica, sans-serif; border-left: 1px solid #cccccc;border-right: 1px solid #cccccc;">
										<table style="width:100%;">
											<tbody>
												<tr>
													<td style="text-align:left; margin:0; padding:0; width:50%; border-collapse:collapse; border-spacing:0;">
														<a href="https://www.rede-marketing.com" style=" text-decoration:none; margin:0; padding:0;"><img width="50" height="50" src="http://rede-marketing.com/root/Content/Images/logo/rede_100.jpg" alt="Red-E"></a>
													</td>
													<td align="right" style="text-align:right; margin:0; padding:0; width:50%; border-collapse:collapse; border-spacing:0;">
															<table style="height: 40px; background-color: #2491d7 transparent;" width="200" cellspacing="0" cellpadding="0" align="right">
																<tbody>
																	<tr>
																		<%--<td style="border-radius: 3px; text-align: center; background-color: #B62B27; color: #ffffff; font-size: 14px; line-height: 20px;"><a style="text-decoration: none; color: #ffffff;font-family: Arial, Helvetica, sans-serif; " href="http://rede-marketing.com/rede/review-center?guid=<%= guid %>">Review Proof</a></td>--%>
																	</tr>
																</tbody>
															</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								<tr style="border-left: 1px solid #cccccc;border-right: 1px solid #cccccc;">
									<td style="font-family:Arial, Helvetica, Sans-serif; background-color:#fff; border-bottom: solid 1px #ccc;">
										<table style="background-color:#fff;border-collapse:collapse; border-spacing:0">
											<tr>
												<td style="padding: 10px 20px 30px;">
													<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif; line-height:14px; font-size:14px;color: #666666;text-align:center;">
                                                        <strong>FINAL REPORT DENIED</strong>
													</p>
													<p style="padding: 0 0 5px; font-family: Arial, Helvetica, Sans-serif;text-align:left;line-height:22px; font-size:14px;color: #666666;">
														Greetings,<br/><br/>
														Final Report for order <strong><%= order_id %></strong> has been denied. A Red/E account executive will call to assist you with your programs creative requirements.
													</p>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td style="line-height:21px; padding:15px 0 0 0;font-family:Arial, Helvetica, Sans-serif; font-size:12px;text-align:center;color: #a1a2a5;">
										&copy; 2017 Red-E. All Rights Reserved.<br/>
										[Insert Address Here]<br/>
                                        <a href="#" style="color:#B62B27; text-decoration:none;">Contact Us</a>
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

