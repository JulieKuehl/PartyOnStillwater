<?php
	$tmls_current_user = wp_get_current_user();
?>

	<h2 id="tmls_gene_short_title">Submission Form</h2>
	
	<div id="tmls_gene_short_leftSidebar">
		
		<div class="tmls_sectionTitle">General Settings</div>
		
		<div class="tmls_rowsContainer tmls_rowsContainerOpend" >
			<div class="row">
				<label for="tmls_form_width">Form Width (px)</label>
				<input type="text" id="tmls_form_width" name="tmls_form_width" value="450" />
			</div>
		
		</div>
		
		<div class="tmls_sectionTitle">Notification email</div>
		
		<div class="tmls_rowsContainer" >
			<div class="row">
				<label for="tmls_notificationEmail">Notification Email</label>
				<select id="tmls_notificationEmail" name="tmls_notificationEmail">
					<option value="enabled" >Enabled</option>
					<option value="disabled" selected >Disabled</option>
				</select>
			</div>
			
			<div class="row notificationEmail_options">
				<label for="tmls_emailTo">Send Email To</label>
				<?php wp_dropdown_users(array('name' => 'tmls_emailTo','id' => 'tmls_emailTo')); ?>
			</div>
			
			<div class="row notificationEmail_options">
				<label for="tmls_emailSubject">Notification Email Subject</label>
				<input type="text" id="tmls_emailSubject" name="tmls_emailSubject" value="A new testimonil has been received" />
			</div>
			
			<div class="row notificationEmail_options">
				<label for="tmls_emailMessage">Notification Email Message</label>
				<textarea id="tmls_emailMessage" name="tmls_emailMessage" >A new testimonial message is waiting for approval.</textarea>
			</div>
		</div>
		
		
		<div class="tmls_sectionTitle">Labels Text</div>
		
		<div class="tmls_rowsContainer" >
			<div class="row">
				<label for="tmls_name_label_text">Name Label Text</label>
				<input type="text" id="tmls_name_label_text" name="tmls_name_label_text" value="Name" />
			</div>
			
			<div class="row">
				<label for="tmls_position_label_text">Position Label Text</label>
				<input type="text" id="tmls_position_label_text" name="tmls_position_label_text" value="Position" />
			</div>
			
			<div class="row">
				<label for="tmls_companyname_label_text">Company Name label Text</label>
				<input type="text" id="tmls_companyname_label_text" name="tmls_companyname_label_text" value="Company Name" />
			</div>
			
			<div class="row">
				<label for="tmls_companywebsite_label_text">Company Website label Text</label>
				<input type="text" id="tmls_companywebsite_label_text" name="tmls_companywebsite_label_text" value="Company Website" />
			</div>
			
			<div class="row">
				<label for="tmls_email_label_text">Email Label Text</label>
				<input type="text" id="tmls_email_label_text" name="tmls_email_label_text" value="Email" />
			</div>
			
			<div class="row">
				<label for="tmls_rating_label_text">Rating Label Text</label>
				<input type="text" id="tmls_rating_label_text" name="tmls_rating_label_text" value="Rating" />
			</div>
			
			<div class="row">
				<label for="tmls_testimonial_label_text">Testimonial Label Text</label>
				<input type="text" id="tmls_testimonial_label_text" name="tmls_testimonial_label_text" value="Testimonial" />
			</div>
			
			<div class="row">
				<label for="tmls_captcha_label_text">Captcha Label Text</label>
				<input type="text" id="tmls_captcha_label_text" name="tmls_captcha_label_text" value="Are you Human?" />
			</div>
		</div>
		
		<div class="tmls_sectionTitle">Labels Style</div>
		
		<div class="tmls_rowsContainer" >
			<div class="row">
				<label for="tmls_label_fontcolor">Labels Font Color</label>
				<input type="text" id="tmls_label_fontcolor" name="tmls_label_fontcolor" value="#999999" />
				<div id="tmls_label_fontcolor_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_label_fontcolor_btn" name="tmls_label_fontcolor_btn" value="View Color" class="button-primary" />
			</div>
			
			<div class="row">
				<label for="tmls_label_fontsize">Labels Font Size (px)</label>
				<select id="tmls_label_fontsize" name="tmls_label_fontsize">
					<option value="9px" >9</option>
					<option value="10px" >10</option>
					<option value="11px" >11</option>
					<option value="12px" >12</option>
					<option value="13px" selected >13</option>
					<option value="14px" >14</option>
					<option value="15px" >15</option>
					<option value="16px" >16</option>
					<option value="17px" >17</option>
					<option value="18px" >18</option>
					<option value="19px" >19</option>
					<option value="20px" >20</option>
					<option value="21px" >21</option>
					<option value="22px" >22</option>
					<option value="23px" >23</option>
					<option value="24px" >24</option>
					<option value="25px" >25</option>
					<option value="26px" >26</option>
					<option value="27px" >27</option>
					<option value="28px" >28</option>
					<option value="29px" >29</option>
					<option value="30px" >30</option>
					<option value="31px" >31</option>
					<option value="32px" >32</option>
					<option value="33px" >33</option>
					<option value="34px" >34</option>
					<option value="35px" >35</option>
					<option value="36px" >36</option>
				</select>
			</div>
			
			<div class="row">
				<label for="tmls_label_fontweight">Labels Font Weight</label>
				<select id="tmls_label_fontweight" name="tmls_label_fontweight">
					<option value="bold" >bold</option>
					<option value="normal" selected >normal</option>
				</select>
			</div>
			
			<div class="row">
				<label for="tmls_label_fontfamily">Labels Font Family</label>
				<select id="tmls_label_fontfamily" name="tmls_label_fontfamily">
					<option value="" >current theme font</option>
					<option value="Georgia, serif" >Georgia</option>
					<option value="'Palatino Linotype', 'Book Antiqua', Palatino, serif" >Palatino Linotype</option>
					<option value="'Times New Roman', Times, serif" >Times New Roman</option>
					<option value="Arial, Helvetica, sans-serif" >Arial</option>
					<option value="'Arial Black', Gadget, sans-serif" >Arial Black</option>
					<option value="'Comic Sans MS', cursive, sans-serif" >Comic Sans MS</option>
					<option value="Impact, Charcoal, sans-serif" >Impact</option>
					<option value="'Lucida Sans Unicode', 'Lucida Grande', sans-serif" >Lucida Sans Unicode</option>
					<option value="Tahoma, Geneva, sans-serif" >Tahoma</option>
					<option value="'Trebuchet MS', Helvetica, sans-serif" >Trebuchet MS</option>
					<option value="Verdana, Geneva, sans-serif" >Verdana</option>
					<option value="'Courier New', Courier, monospace" >Courier New</option>
					<option value="'Lucida Console', Monaco, monospace" >Lucida Console</option>
				</select>
			</div>
		
		</div>
		
		<div class="tmls_sectionTitle">Inputs Style</div>
		
		<div class="tmls_rowsContainer" >
			<div class="row">
				<label for="tmls_inputs_fontcolor">Inputs Font Color</label>
				<input type="text" id="tmls_inputs_fontcolor" name="tmls_inputs_fontcolor" value="#999999" />
				<div id="tmls_inputs_fontcolor_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_inputs_fontcolor_btn" name="tmls_inputs_fontcolor_btn" value="View Color" class="button-primary" />
			</div>
			
			<div class="row">
				<label for="tmls_inputs_fontsize">Inputs Font Size (px)</label>
				<select id="tmls_inputs_fontsize" name="tmls_inputs_fontsize">
					<option value="9px" >9</option>
					<option value="10px" >10</option>
					<option value="11px" >11</option>
					<option value="12px" selected >12</option>
					<option value="13px" >13</option>
					<option value="14px" >14</option>
					<option value="15px" >15</option>
					<option value="16px" >16</option>
					<option value="17px" >17</option>
					<option value="18px" >18</option>
					<option value="19px" >19</option>
					<option value="20px" >20</option>
					<option value="21px" >21</option>
					<option value="22px" >22</option>
					<option value="23px" >23</option>
					<option value="24px" >24</option>
					<option value="25px" >25</option>
					<option value="26px" >26</option>
					<option value="27px" >27</option>
					<option value="28px" >28</option>
					<option value="29px" >29</option>
					<option value="30px" >30</option>
					<option value="31px" >31</option>
					<option value="32px" >32</option>
					<option value="33px" >33</option>
					<option value="34px" >34</option>
					<option value="35px" >35</option>
					<option value="36px" >36</option>
				</select>
			</div>
			
			<div class="row">
				<label for="tmls_inputs_fontweight">Inputs Font Weight</label>
				<select id="tmls_inputs_fontweight" name="tmls_inputs_fontweight">
					<option value="bold"  >bold</option>
					<option value="normal" selected >normal</option>
				</select>
			</div>
			
			<div class="row">
				<label for="tmls_inputs_fontfamily">Inputs Font Family</label>
				<select id="tmls_inputs_fontfamily" name="tmls_inputs_fontfamily">
					<option value="" >current theme font</option>
					<option value="Georgia, serif" >Georgia</option>
					<option value="'Palatino Linotype', 'Book Antiqua', Palatino, serif" >Palatino Linotype</option>
					<option value="'Times New Roman', Times, serif" >Times New Roman</option>
					<option value="Arial, Helvetica, sans-serif" >Arial</option>
					<option value="'Arial Black', Gadget, sans-serif" >Arial Black</option>
					<option value="'Comic Sans MS', cursive, sans-serif" >Comic Sans MS</option>
					<option value="Impact, Charcoal, sans-serif" >Impact</option>
					<option value="'Lucida Sans Unicode', 'Lucida Grande', sans-serif" >Lucida Sans Unicode</option>
					<option value="Tahoma, Geneva, sans-serif" >Tahoma</option>
					<option value="'Trebuchet MS', Helvetica, sans-serif" >Trebuchet MS</option>
					<option value="Verdana, Geneva, sans-serif" >Verdana</option>
					<option value="'Courier New', Courier, monospace" >Courier New</option>
					<option value="'Lucida Console', Monaco, monospace" >Lucida Console</option>
				</select>
			</div>
			
			<div class="row">
				<label for="tmls_inputs_bordercolor">Inputs Border Color</label>
				<input type="text" id="tmls_inputs_bordercolor" name="tmls_inputs_bordercolor" value="#eeeeee" />
				<div id="tmls_inputs_bordercolor_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_inputs_bordercolor_btn" name="tmls_inputs_bordercolor_btn" value="View Color" class="button-primary" />
			</div>
			
			<div class="row">
				<label for="tmls_inputs_bgcolor">Inputs Background Color</label>
				<input type="text" id="tmls_inputs_bgcolor" name="tmls_inputs_bgcolor" value="transparent" />
				<div id="tmls_inputs_bgcolor_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_inputs_bgcolor_btn" name="tmls_inputs_bgcolor_btn" value="View Color" class="button-primary" />
			</div>
			
			<div class="row">
				<label for="tmls_inputs_borderradius">Inputs Border Radius</label>
				<select id="tmls_inputs_borderradius" name="tmls_inputs_borderradius">
					<option value="large_radius">large radius</option>
					<option value="medium_radius" >medium radius</option>
					<option value="small_radius" selected >small radius</option>
					<option value="no_radius">without radius</option>
				</select>
			</div>
		</div>
		
		<div class="tmls_sectionTitle">Messages Text</div>
		
		<div class="tmls_rowsContainer" >
			<div class="row">
				<label for="tmls_success_message">Success Message</label>
				<input type="text" id="tmls_success_message" name="tmls_success_message" value="Thank you! Your testimonial has been successfully sent" />
			</div>
			
			<div class="row">
				<label for="tmls_namerequired_message">Name Required Message</label>
				<input type="text" id="tmls_namerequired_message" name="tmls_namerequired_message" value="Name is required" />
			</div>
			
			<div class="row">
				<label for="tmls_emailrequired_message">Email Required Message</label>
				<input type="text" id="tmls_emailrequired_message" name="tmls_emailrequired_message" value="Email is required" />
			</div>
			
			<div class="row">
				<label for="tmls_testimonialrequired_message">Testimonial Required Message</label>
				<input type="text" id="tmls_testimonialrequired_message" name="tmls_testimonialrequired_message" value="Testimonial is required" />
			</div>
			
			<div class="row">
				<label for="tmls_invalidemail_message">Invalid Email Message</label>
				<input type="text" id="tmls_invalidemail_message" name="tmls_invalidemail_message" value="Invalid email format" />
			</div>
			
			<div class="row">
				<label for="tmls_invalidcompanywebsite_message">Invalid Company Website Message</label>
				<input type="text" id="tmls_invalidcompanywebsite_message" name="tmls_invalidcompanywebsite_message" value="Invalid company website URL" />
			</div>
			
			<div class="row">
				<label for="tmls_captchaanswerrequired_message">Captcha Answer Required Message</label>
				<input type="text" id="tmls_captchaanswerrequired_message" name="tmls_captchaanswerrequired_message" value="Captcha answer is required" />
			</div>
			
			<div class="row">
				<label for="tmls_invalidcaptchaanswer_message">Invalid Captcha Answer Message</label>
				<input type="text" id="tmls_invalidcaptchaanswer_message" name="tmls_invalidcaptchaanswer_message" value="Invalid captcha answer" />
			</div>
			
			<div class="row">
				<label for="tmls_alreadysent_message">Testimonial already sent Message</label>
				<input type="text" id="tmls_alreadysent_message" name="tmls_alreadysent_message" value="Your testimonial already sent" />
			</div>
			
		</div>
		
		
		<div class="tmls_sectionTitle">Messages Style</div>
		
		<div class="tmls_rowsContainer" >
		
			<div class="row">
				<label for="tmls_validationmessage_fontcolor">Validation Message Font Color</label>
				<input type="text" id="tmls_validationmessage_fontcolor" name="tmls_validationmessage_fontcolor" value="#e14d43" />
				<div id="tmls_validationmessage_fontcolor_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_validationmessage_fontcolor_btn" name="tmls_validationmessage_fontcolor_btn" value="View Color" class="button-primary" />
			</div>
			
			<div class="row">
				<label for="tmls_validationmessage_fontsize">Validation Message Font Size (px)</label>
				<select id="tmls_validationmessage_fontsize" name="tmls_validationmessage_fontsize">
					<option value="9px" >9</option>
					<option value="10px" >10</option>
					<option value="11px" >11</option>
					<option value="12px" >12</option>
					<option value="13px" selected >13</option>
					<option value="14px" >14</option>
					<option value="15px" >15</option>
					<option value="16px" >16</option>
					<option value="17px" >17</option>
					<option value="18px" >18</option>
					<option value="19px" >19</option>
					<option value="20px" >20</option>
					<option value="21px" >21</option>
					<option value="22px" >22</option>
					<option value="23px" >23</option>
					<option value="24px" >24</option>
					<option value="25px" >25</option>
					<option value="26px" >26</option>
					<option value="27px" >27</option>
					<option value="28px" >28</option>
					<option value="29px" >29</option>
					<option value="30px" >30</option>
					<option value="31px" >31</option>
					<option value="32px" >32</option>
					<option value="33px" >33</option>
					<option value="34px" >34</option>
					<option value="35px" >35</option>
					<option value="36px" >36</option>
				</select>
			</div>
			
			<div class="row">
				<label for="tmls_validationmessage_fontweight">Validation Message Font Weight</label>
				<select id="tmls_validationmessage_fontweight" name="tmls_validationmessage_fontweight">
					<option value="bold"  >bold</option>
					<option value="normal" selected >normal</option>
				</select>
			</div>
			
			<div class="row">
				<label for="tmls_validationmessage_fontfamily">Validation Message Font Family</label>
				<select id="tmls_validationmessage_fontfamily" name="tmls_validationmessage_fontfamily">
					<option value="" >current theme font</option>
					<option value="Georgia, serif" >Georgia</option>
					<option value="'Palatino Linotype', 'Book Antiqua', Palatino, serif" >Palatino Linotype</option>
					<option value="'Times New Roman', Times, serif" >Times New Roman</option>
					<option value="Arial, Helvetica, sans-serif" >Arial</option>
					<option value="'Arial Black', Gadget, sans-serif" >Arial Black</option>
					<option value="'Comic Sans MS', cursive, sans-serif" >Comic Sans MS</option>
					<option value="Impact, Charcoal, sans-serif" >Impact</option>
					<option value="'Lucida Sans Unicode', 'Lucida Grande', sans-serif" >Lucida Sans Unicode</option>
					<option value="Tahoma, Geneva, sans-serif" >Tahoma</option>
					<option value="'Trebuchet MS', Helvetica, sans-serif" >Trebuchet MS</option>
					<option value="Verdana, Geneva, sans-serif" >Verdana</option>
					<option value="'Courier New', Courier, monospace" >Courier New</option>
					<option value="'Lucida Console', Monaco, monospace" >Lucida Console</option>
				</select>
			</div>
			
			
			
			
			
			
			
			
			
			
			<div class="row">
				<label for="tmls_successmessage_fontcolor">Success Message Font Color</label>
				<input type="text" id="tmls_successmessage_fontcolor" name="tmls_successmessage_fontcolor" value="#a3b745" />
				<div id="tmls_successmessage_fontcolor_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_successmessage_fontcolor_btn" name="tmls_successmessage_fontcolor_btn" value="View Color" class="button-primary" />
			</div>
			
			<div class="row">
				<label for="tmls_successmessage_fontsize">Success Message Font Size (px)</label>
				<select id="tmls_successmessage_fontsize" name="tmls_successmessage_fontsize">
					<option value="9px" >9</option>
					<option value="10px" >10</option>
					<option value="11px" >11</option>
					<option value="12px" >12</option>
					<option value="13px" selected >13</option>
					<option value="14px" >14</option>
					<option value="15px" >15</option>
					<option value="16px" >16</option>
					<option value="17px" >17</option>
					<option value="18px" >18</option>
					<option value="19px" >19</option>
					<option value="20px" >20</option>
					<option value="21px" >21</option>
					<option value="22px" >22</option>
					<option value="23px" >23</option>
					<option value="24px" >24</option>
					<option value="25px" >25</option>
					<option value="26px" >26</option>
					<option value="27px" >27</option>
					<option value="28px" >28</option>
					<option value="29px" >29</option>
					<option value="30px" >30</option>
					<option value="31px" >31</option>
					<option value="32px" >32</option>
					<option value="33px" >33</option>
					<option value="34px" >34</option>
					<option value="35px" >35</option>
					<option value="36px" >36</option>
				</select>
			</div>
			
			<div class="row">
				<label for="tmls_successmessage_fontweight">Success Message Font Weight</label>
				<select id="tmls_successmessage_fontweight" name="tmls_successmessage_fontweight">
					<option value="bold"  >bold</option>
					<option value="normal" selected >normal</option>
				</select>
			</div>
			
			<div class="row">
				<label for="tmls_successmessage_fontfamily">Success Message Font Family</label>
				<select id="tmls_successmessage_fontfamily" name="tmls_successmessage_fontfamily">
					<option value="" >current theme font</option>
					<option value="Georgia, serif" >Georgia</option>
					<option value="'Palatino Linotype', 'Book Antiqua', Palatino, serif" >Palatino Linotype</option>
					<option value="'Times New Roman', Times, serif" >Times New Roman</option>
					<option value="Arial, Helvetica, sans-serif" >Arial</option>
					<option value="'Arial Black', Gadget, sans-serif" >Arial Black</option>
					<option value="'Comic Sans MS', cursive, sans-serif" >Comic Sans MS</option>
					<option value="Impact, Charcoal, sans-serif" >Impact</option>
					<option value="'Lucida Sans Unicode', 'Lucida Grande', sans-serif" >Lucida Sans Unicode</option>
					<option value="Tahoma, Geneva, sans-serif" >Tahoma</option>
					<option value="'Trebuchet MS', Helvetica, sans-serif" >Trebuchet MS</option>
					<option value="Verdana, Geneva, sans-serif" >Verdana</option>
					<option value="'Courier New', Courier, monospace" >Courier New</option>
					<option value="'Lucida Console', Monaco, monospace" >Lucida Console</option>
				</select>
			</div>
		
		
		
		</div>
		
		
		
		
		
		
		<div class="tmls_sectionTitle">Button Settings</div>
		
		<div class="tmls_rowsContainer" >
			<div class="row">
				<label for="tmls_button_text">Button Text</label>
				<input type="text" id="tmls_button_text" name="tmls_button_text" value="SEND TESTIMONIAL" />
			</div>
			
			<div class="row">
				<label for="tmls_button_fontcolor">Button Font Color</label>
				<input type="text" id="tmls_button_fontcolor" name="tmls_button_fontcolor" value="#999999" />
				<div id="tmls_button_fontcolor_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_button_fontcolor_btn" name="tmls_button_fontcolor_btn" value="View Color" class="button-primary" />
			</div>
			
			<div class="row">
				<label for="tmls_button_fontsize">Button Font Size (px)</label>
				<select id="tmls_button_fontsize" name="tmls_button_fontsize">
					<option value="9px" >9</option>
					<option value="10px" >10</option>
					<option value="11px" >11</option>
					<option value="12px" selected >12</option>
					<option value="13px" >13</option>
					<option value="14px" >14</option>
					<option value="15px" >15</option>
					<option value="16px" >16</option>
					<option value="17px" >17</option>
					<option value="18px" >18</option>
					<option value="19px" >19</option>
					<option value="20px" >20</option>
					<option value="21px" >21</option>
					<option value="22px" >22</option>
					<option value="23px" >23</option>
					<option value="24px" >24</option>
					<option value="25px" >25</option>
					<option value="26px" >26</option>
					<option value="27px" >27</option>
					<option value="28px" >28</option>
					<option value="29px" >29</option>
					<option value="30px" >30</option>
					<option value="31px" >31</option>
					<option value="32px" >32</option>
					<option value="33px" >33</option>
					<option value="34px" >34</option>
					<option value="35px" >35</option>
					<option value="36px" >36</option>
				</select>
			</div>
			
			<div class="row">
				<label for="tmls_button_fontweight">Button Font Weight</label>
				<select id="tmls_button_fontweight" name="tmls_button_fontweight">
					<option value="bold" selected >bold</option>
					<option value="normal" >normal</option>
				</select>
			</div>
			
			<div class="row">
				<label for="tmls_button_fontfamily">Button Font Family</label>
				<select id="tmls_button_fontfamily" name="tmls_button_fontfamily">
					<option value="" >current theme font</option>
					<option value="Georgia, serif" >Georgia</option>
					<option value="'Palatino Linotype', 'Book Antiqua', Palatino, serif" >Palatino Linotype</option>
					<option value="'Times New Roman', Times, serif" >Times New Roman</option>
					<option value="Arial, Helvetica, sans-serif" >Arial</option>
					<option value="'Arial Black', Gadget, sans-serif" >Arial Black</option>
					<option value="'Comic Sans MS', cursive, sans-serif" >Comic Sans MS</option>
					<option value="Impact, Charcoal, sans-serif" >Impact</option>
					<option value="'Lucida Sans Unicode', 'Lucida Grande', sans-serif" >Lucida Sans Unicode</option>
					<option value="Tahoma, Geneva, sans-serif" >Tahoma</option>
					<option value="'Trebuchet MS', Helvetica, sans-serif" >Trebuchet MS</option>
					<option value="Verdana, Geneva, sans-serif" >Verdana</option>
					<option value="'Courier New', Courier, monospace" >Courier New</option>
					<option value="'Lucida Console', Monaco, monospace" >Lucida Console</option>
				</select>
			</div>
			
			<div class="row">
				<label for="tmls_button_bordercolor">Button Border Color</label>
				<input type="text" id="tmls_button_bordercolor" name="tmls_button_bordercolor" value="#eeeeee" />
				<div id="tmls_button_bordercolor_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_button_bordercolor_btn" name="tmls_button_bordercolor_btn" value="View Color" class="button-primary" />
			</div>
			
			<div class="row">
				<label for="tmls_button_bgcolor">Button Background Color</label>
				<input type="text" id="tmls_button_bgcolor" name="tmls_button_bgcolor" value="transparent" />
				<div id="tmls_button_bgcolor_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_button_bgcolor_btn" name="tmls_button_bgcolor_btn" value="View Color" class="button-primary" />
			</div>
			
			<div class="row">
				<label for="tmls_button_borderradius">Button Border Radius</label>
				<select id="tmls_button_borderradius" name="tmls_button_borderradius">
					<option value="large_radius" >large radius</option>
					<option value="medium_radius" >medium radius</option>
					<option value="small_radius" selected >small radius</option>
					<option value="no_radius">without radius</option>
				</select>
			</div>
			
			<div class="row">
				<label for="tmls_button_hover_fontcolor">Button Hover Font Color</label>
				<input type="text" id="tmls_button_hover_fontcolor" name="tmls_button_hover_fontcolor" value="#999999" />
				<div id="tmls_button_hover_fontcolor_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_button_hover_fontcolor_btn" name="tmls_button_hover_fontcolor_btn" value="View Color" class="button-primary" />
			</div>
			
			<div class="row">
				<label for="tmls_button_hover_bordercolor">Button Hover Border Color</label>
				<input type="text" id="tmls_button_hover_bordercolor" name="tmls_button_hover_bordercolor" value="#eeeeee" />
				<div id="tmls_button_hover_bordercolor_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_button_hover_bordercolor_btn" name="tmls_button_hover_bordercolor_btn" value="View Color" class="button-primary" />
			</div>
			
			<div class="row">
				<label for="tmls_button_hover_bgcolor">Button Hover Background Color</label>
				<input type="text" id="tmls_button_hover_bgcolor" name="tmls_button_hover_bgcolor" value="#f5f5f5" />
				<div id="tmls_button_hover_bgcolor_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_button_hover_bgcolor_btn" name="tmls_button_hover_bgcolor_btn" value="View Color" class="button-primary" />
			</div>
			
			<input type="hidden" id="tmls_captcha_encryption_key" name="tmls_captcha_encryption_key" value="<?php echo str_shuffle('abcdef123456@!'); ?>" />
			
		</div>
		
	</div>
	
	<p id="noteParagraph">
		<strong>Note: </strong>copy the following shortcode in the yellow box to the page editor or post editor or testimonials widget to display the submission form in the website.
	</p>
	
	<div id="tmls_form_div_shortcode">[tmls_form]</div>
	
	<div id="tmls_form_gene_short_preview"></div>
