<br />
		<div>
			<hr />
		<p><strong><?php _e('Add Handling', $this->my_domain); echo " ($this->version)"; ?></strong><br />
		<em><?php _e('Specify an amount to add to resulting shipping prices.', $this->my_domain);?></em></p>
		<style>
			div.usc_handling_svc { max-height:	350px; overflow: auto}
		</style>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$("#usc_add_handling_type").change(function(){

					var handling_type = parseInt($(this).val(),10);

					if (isNaN(handling_type)) handling_type = 0;

					if (handling_type == 1) {
						$("#usc_handling_amount_tr").show();
						$("#usc_handling_amount_custom_tr").hide();
					}
					else if (handling_type == 2) {
						$("#usc_handling_amount_tr").hide();
						$("#usc_handling_amount_custom_tr").show();
						
					}
					else {
						$("#usc_handling_amount_tr").hide();
						$("#usc_handling_amount_custom_tr").hide();
					}				
				});
			});		
		</script>
		<table>
			<tr>
				<th style="width: 150px">
					<?php _e('Handling Options', $this->my_domain);?>
				</th>
				<td>
					<select id="usc_add_handling_type" name="<?php echo $this->options_name; ?>[add_handling]">
						<option value="0" <?php selected(0, $options['add_handling']); ?>><?php _e('Off', $this->my_domain); ?></option>
						<option value="1" <?php selected(1, $options['add_handling']); ?>><?php _e('Global', $this->my_domain); ?></option>
						<option value="2" <?php selected(2, $options['add_handling']); ?>><?php _e('Custom', $this->my_domain); ?></option>
					</select>
				</td>
			</tr>
			<tr id="usc_handling_amount_tr" <?php echo $params['show_hide']; ?>>
				<th>
					<?php _e('Amount to Add', $this->my_domain);?>
				</th>
				<td>
					<input type="text" id="usc_handling_amount" 
						   name="<?php echo $this->options_name; ?>[handling_amount]" 
						   value="<?php echo $params['handling_amt'] ?>" size="6"/>
				</td>
			</tr>
			<tr id="usc_handling_amount_custom_tr" <?php echo $params['custom_show_hide']; ?>>
				<td colspan="2">
					<p><?php _e('Service fees are checked first. Default fee is applied if no Service fee is found.',$this->my_domain);?></p>
					<?php foreach ($params['service_list'] as $carrier => $svcs) :?>
					<div class="postbox">
						<h3><?php echo $carrier; ?></h3>
						<div class="inside usc_handling_svc">
							<table>
								<thead>
									<tr>
										<th>
											<?php _e('Service', $this->my_domain); ?>
										</th>
										<th>
											<?php _e('Handling Fee', $this->my_domain); ?>
										</th>
									</tr>
								</thead>
								<tbody>
								<tr>
									<td>
										<em><?php _e('Default', $this->my_domain);?></em>
									</td>
									<td>
										<input type="text" 
										name="<?php echo $this->options_name; ?>[custom_handling][<?php echo $carrier; ?>][default]"
										size="6" value="<?php echo $options['custom_handling'][$carrier]['default']; ?>"/>
									</td>
								</tr>
							<?php foreach ($svcs as $svc) :?>
								<tr>
									<td>
									<?php echo $svc ?>
									</td>
									<td>
										<input type="text" 
										name="<?php echo $this->options_name; ?>[custom_handling][<?php echo $carrier; ?>][<?php echo "$carrier - $svc"; ?>]"
										size="6" value="<?php echo $options['custom_handling'][$carrier]["$carrier - $svc"]; ?>"	/>
									</td>
								</tr>
							<?php endforeach;?>
								</tbody>
							</table>
						</div>
					</div>
					<?php endforeach; ?>
				</td>			
			</tr>
		</table>
		
		</div>