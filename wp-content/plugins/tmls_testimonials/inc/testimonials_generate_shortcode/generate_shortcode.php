<?php
	
?>

	<h2 id="tmls_gene_short_title">Generate Shortcode</h2>
	
	<div id="tmls_gene_short_leftSidebar">
		
		<div class="tmls_sectionTitle">General Settings</div>
		
		<div class="tmls_rowsContainer tmls_rowsContainerOpend" >
			<div class="row">
				<label for="tmls_category">Category Name</label>
				<?php

				wp_dropdown_categories(array('taxonomy' =>'tmlscategory',
											 'show_count' => 1, 
											 'pad_counts' => 1, 
											 'id' => 'tmls_category',
											 'name' => 'tmls_category',
											 'hide_empty' => 0,
											 'show_option_none' => 'All Categories',
											 'hierarchical'=>1));
					
				?>
			</div>
			
			<div class="row">
				<label for="tmls_orderByList">Order By</label>
				<select id="tmls_orderByList" name="tmls_orderByList">
					<option value="date" selected >Publish Date</option>
					<option value="menu_order">Order</option>
					<option value="rand">Random </option>
				</select>
			</div>
			
			<div class="row">
				<label for="tmls_orderList">Order</label>
				<select id="tmls_orderList" name="tmls_orderList">
					<option value="DESC" selected >Descending</option>
					<option value="ASC">Ascending</option>
				</select>
			</div>
			
			<div class="row">
				<label for="tmls_numberInput">Number of items</label>
				<input type="text" id="tmls_numberInput" name="tmls_numberInput" value="" placeholder="All" />
			</div>
		</div>
		
		<div class="tmls_sectionTitle">Layout</div>
		
		<div class="tmls_rowsContainer" >
			<div class="row">
				<label for="tmls_layout">Layout</label>
				<select id="tmls_layout" name="tmls_layout">
					<option value="tmls_slider">slider</option>
					<option value="tmls_slider2">slider with thumbnails</option>
					<option value="tmls_grid">grid</option>
					<option value="tmls_list">list</option>
				</select>
			</div>
			
			<div class="row slider_options grid_options list_options">
				<label for="tmls_style">Items Style</label>
				<select id="tmls_style" name="tmls_style">
					<option value="style1">style 1</option>
					<option value="style2">style 2</option>
					<option value="style3">style 3</option>
					<option value="style4">style 4</option>
					<option value="style5">style 5</option>
				</select>
			</div>
			
			<div class="row grid_options">
				<label for="tmls_columns_number">Columns Number</label>
				<select id="tmls_columns_number" name="tmls_columns_number">
					<option value="2">2 columns</option>
					<option value="3" >3 columns</option>
					<option value="4" >4 columns</option>
				</select>
			</div>
		</div>
		
		
		<div class="tmls_sectionTitle">Item Style</div>
		
		<div class="tmls_rowsContainer" >
			<div class="row">
				<label for="tmls_image_size">Image Size</label>
				<select id="tmls_image_size" name="tmls_image_size">
					<option value="large_image">large</option>
					<option value="medium_image">medium</option>
					<option value="small_image">small</option>
					<option value="no_image">without image</option>
				</select>
			</div>
			
			<div class="row image_options">
				<label for="tmls_image_radius">Image Radius</label>
				<select id="tmls_image_radius" name="tmls_image_radius">
					<option value="large_radius">large radius</option>
					<option value="medium_radius" >medium radius</option>
					<option value="small_radius">small radius</option>
					<option value="no_radius">without radius</option>
				</select>
			</div>
			
			<div class="row slider2_options">
				<label for="tmls_slider2_unselectedOverlayBgColor">Unselected Overlay Bg Color</label>
				<input type="text" id="tmls_slider2_unselectedOverlayBgColor" name="tmls_slider2_unselectedOverlayBgColor" value="#FFFFFF" placeholder="#FFFFFF" />
				<div id="tmls_slider2_unselectedOverlayBgColor_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_slider2_unselectedOverlayBgColor_btn" name="tmls_slider2_unselectedOverlayBgColor_btn" value="View Color" class="button-primary" />
			</div>
			
			<div class="row">
				<label for="tmls_grayscale">Grayscale</label>
				<select id="tmls_grayscale" name="tmls_grayscale">
					<option value="enabled">enabled</option>
					<option value="disabled" selected >disabled</option>
				</select>
			</div>
		</div>
		
		<div class="tmls_sectionTitle">Rating Stars</div>
		
		<div class="tmls_rowsContainer" >
			<div class="row">
				<label for="tmls_ratingStars">Rating Stars</label>
				<select id="tmls_ratingStars" name="tmls_ratingStars">
					<option value="enabled" selected >enabled</option>
					<option value="disabled">disabled</option>
				</select>
			</div>
			
			<div class="row rating_options">
				<label for="tmls_ratingStarsSize">Rating Stars Size (px)</label>
				<select id="tmls_ratingStarsSize" name="tmls_ratingStarsSize">
					<option value="9px" >9</option>
					<option value="10px" >10</option>
					<option value="11px" >11</option>
					<option value="12px" >12</option>
					<option value="13px" >13</option>
					<option value="14px" >14</option>
					<option value="15px" >15</option>
					<option value="16px" selected >16</option>
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
			
			<div class="row rating_options">
				<label for="tmls_ratingStarscolor">Rating Stars Color</label>
				<input type="text" id="tmls_ratingStarscolor" name="tmls_ratingStarscolor" value="#F47E00" placeholder="#F47E00" />
				<div id="tmls_ratingStars_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_ratingStars_color_btn" name="tmls_ratingStars_color_btn" value="View Color" class="button-primary" />
			</div>
		</div>
		
		<div class="tmls_sectionTitle grid_options list_options">Dividers Style</div>
		
		<div class="tmls_rowsContainer grid_options list_options" >
			<div class="row border_options">
				<label for="tmls_border_style">Border Style</label>
				<select id="tmls_border_style" name="tmls_border_style">
					<option value="tmls_border tmls_dashed_border">dashed</option>
					<option value="tmls_border tmls_solid_border" >solid</option>
					<option value="no_border" >without border</option>
				</select>
			</div>
			
			<div class="row border_options border_color">
				<label for="tmls_border_color">Border Color</label>
				<input type="text" id="tmls_border_color" name="tmls_border_color" class="tmls_farbtastic_input" value="#DDDDDD" />
				<div id="tmls_border_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_border_color_btn" name="tmls_border_color_btn" value="View Color" class="button-primary" />
			</div>
		
		</div>
		
		<div class="tmls_sectionTitle slider_options slider2_options">Slider Settings</div>
		
		<div class="tmls_rowsContainer slider_options slider2_options" >
			<div class="row slider_options slider2_options">
				<label for="tmls_auto_play">Auto Play</label>
				<select id="tmls_auto_play" name="tmls_auto_play">
					<option value="true" selected >true</option>
					<option value="false">false</option>
				</select>
			</div>
			
			<div class="row slider_options slider2_options">
				<label for="tmls_transitionEffect">Transition Effect</label>
				<select id="tmls_transitionEffect" name="tmls_transitionEffect">
					<option value="crossfade" selected >crossfade</option>
					<option value="scroll"  >scroll</option>
				</select>
			</div>
			
			<div class="row slider_options slider2_options">
				<label for="tmls_pause_on_hover">Pause On Hover</label>
				<select id="tmls_pause_on_hover" name="tmls_pause_on_hover">
					<option value="false">false</option>
					<option value="true" >true</option>
				</select>
			</div>
			
			<div class="row slider_options">
				<label for="tmls_next_prev_visibility">Buttons Visibility</label>
				<select id="tmls_next_prev_visibility" name="tmls_next_prev_visibility">
					<option value="tmls_visible">visible</option>
					<option value="tmls_show_on_hover" >show on hover</option>
					<option value="tmls_hiden" >hiden</option>
				</select>
			</div>
			
			<div class="row slider_options">
				<label for="tmls_next_prev_radius">Buttons Radius</label>
				<select id="tmls_next_prev_radius" name="tmls_next_prev_radius">
					<option value="large_radius">large radius</option>
					<option value="medium_radius" >medium radius</option>
					<option value="small_radius">small radius</option>
					<option value="no_radius">without radius</option>
				</select>
			</div>
			
			<div class="row slider_options">
				<label for="tmls_next_prev_position">Buttons Position</label>
				<select id="tmls_next_prev_position" name="tmls_next_prev_position">
					<option value="">default</option>
					<option value="tmls_top" >top</option>
					<option value="tmls_bottom" >bottom</option>
				</select>
			</div>
			
			
			
			<div class="row slider_options">
				<label for="tmls_next_prev_bgcolor">Buttons background color</label>
				<input type="text" id="tmls_next_prev_bgcolor" name="tmls_next_prev_bgcolor" value="#F5F5F5" />
				<div id="tmls_next_prev_bgcolor_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_next_prev_bgcolor_btn" name="tmls_next_prev_bgcolor_btn" value="View Color" class="button-primary" />
			</div>
			
			<div class="row slider_options">
				<label for="tmls_next_prev_arrowscolor">Buttons arrows color</label>
				<select id="tmls_next_prev_arrowscolor" name="tmls_next_prev_arrowscolor">
					<option value="tmls_darkgrayarrows">dark gray</option>
					<option value="tmls_lightgrayarrows" selected >light gray</option>
					<option value="tmls_whitearrows" >white</option>
				</select>
			</div>
			
			
			
			<div class="row slider_options slider2_options">
				<label for="tmls_scroll_duration">Scroll Duration</label>
				<input type="text" id="tmls_scroll_duration" name="tmls_scroll_duration" value="500" />
			</div>
			
			<div class="row slider_options slider2_options">
				<label for="tmls_pause_duration">Pause Duration</label>
				<input type="text" id="tmls_pause_duration" name="tmls_pause_duration" value="9000" />
			</div>
		
		</div>
		
		
		
		
		<div class="tmls_sectionTitle">Font Style</div>
		
		<div class="tmls_rowsContainer" >
			<div class="row">
				<label for="tmls_font_style">Font Style</label>
				<select id="tmls_font_style" name="tmls_font_style">
					<option value="custom" >custom style</option>
					<option value="default" >current theme style</option>
				</select>
			</div>
			
			<div class="row font_options">
				<label for="tmls_text_font_family">Text Font Family</label>
				<select id="tmls_text_font_family" name="tmls_text_font_family">
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
			
			<div class="row font_options">
				<label for="tmls_text_font_color">Text Font Color</label>
				<input type="text" id="tmls_text_font_color" name="tmls_text_font_color" value="#777777" placeholder="#777777" />
				<div id="tmls_text_font_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_text_font_color_btn" name="tmls_text_font_color_btn" value="View Color" class="button-primary" />
			</div>
			
			<div class="row font_options">
				<label for="tmls_text_font_size">Text Font Size (px)</label>
				<select id="tmls_text_font_size" name="tmls_text_font_size">
					<option value="9px" >9</option>
					<option value="10px" >10</option>
					<option value="11px" >11</option>
					<option value="12px" >12</option>
					<option value="13px" >13</option>
					<option value="14px" selected >14</option>
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
			
			
			<div class="row font_options">
				<label for="tmls_name_font_family">Name Font Family</label>
				<select id="tmls_name_font_family" name="tmls_name_font_family">
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
			
			
			<div class="row font_options">
				<label for="tmls_name_font_color">Name Font Color</label>
				<input type="text" id="tmls_name_font_color" name="tmls_name_font_color" value="#777777" placeholder="#777777" />
				<div id="tmls_name_font_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_name_font_color_btn" name="tmls_name_font_color_btn" value="View Color" class="button-primary" />
			</div>
			
			<div class="row font_options">
				<label for="tmls_neme_font_size">Name Font Size (px)</label>
				<select id="tmls_neme_font_size" name="tmls_neme_font_size">
					<option value="9px" >9</option>
					<option value="10px" >10</option>
					<option value="11px" >11</option>
					<option value="12px" >12</option>
					<option value="13px" >13</option>
					<option value="14px"  >14</option>
					<option value="15px" selected >15</option>
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
			
			<div class="row font_options">
				<label for="tmls_neme_font_weight">Neme Font Weight</label>
				<select id="tmls_neme_font_weight" name="tmls_neme_font_weight">
					<option value="bold" >bold</option>
					<option value="normal" >normal</option>
				</select>
			</div>
			
			
			<div class="row font_options">
				<label for="tmls_position_font_family">Position Font Family</label>
				<select id="tmls_position_font_family" name="tmls_position_font_family">
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
			
			
			<div class="row font_options">
				<label for="tmls_position_font_color">Position Font Color</label>
				<input type="text" id="tmls_position_font_color" name="tmls_position_font_color" value="#777777" placeholder="#777777" />
				<div id="tmls_position_font_colorpicker" class="tmls_farbtastic"></div>
				<input type="button" id="tmls_position_font_color_btn" name="tmls_position_font_color_btn" value="View Color" class="button-primary" />
			</div>
			
			<div class="row font_options">
				<label for="tmls_position_font_size">Position Font Size (px)</label>
				<select id="tmls_position_font_size" name="tmls_position_font_size">
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
		
		
		</div>
		
		
		
		
	</div>
	
	<p id="noteParagraph">
		<strong>Note: </strong>copy the following shortcode in the yellow box to the page editor or post editor or testimonials widget to display the testimonials in the website.
	</p>
	
	<div id="tmls_div_shortcode">[tmls]</div>
	
	<div id="tmls_gene_short_preview"></div>
