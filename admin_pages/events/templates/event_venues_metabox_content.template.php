<table class="form-table">
	<tr>

		<td valign="top" class="manual-venue">
				<h4>
					<?php _e('Venue Information', 'event_espresso'); ?>
				</h4>
				<input type="hidden" id="venue-id" name="venue_id" value="<?php echo $_venue->ID(); ?>" />
				<p>
					<label for="ven-title"><?php _e('Title:', 'event_espresso'); ?></label><br/>
					<input size="20"id="ven-title" type="text"  value="<?php echo $_venue->name() ?>" name="venue_title" />
				</p>
				<p>
					<label for="ven-website"><?php _e('Website:', 'event_espresso'); ?></label><br/>
					<input size="20" id="ven-website" type="text"  value="<?php echo stripslashes_deep($_venue->venue_url()) ?>" name="venue_url" />
				</p>
				<p>
					<label for="ven-phone"><?php _e('Phone:', 'event_espresso'); ?></label><br/>
					<input size="20" id="ven-phone" type="text" name="venue_phone" value="<?php echo $_venue->phone(); ?>" />
				</p>
				<hr />
				<p><?php _e('Venues are only saved with events if there is a Venue title present.', 'event_espresso'); ?></p>
		</td>
		
		<td valign="top" class="manual-venue">
			<fieldset>
				<h4><?php _e('Physical Location', 'event_espresso'); ?></h4>
				<p>
					<label for="phys-addr"><?php _e('Address:', 'event_espresso'); ?></label><br/>
					<input size="20" id="phys-addr" type="text"  value="<?php echo $_venue->address(); ?>" name="address" />
				</p>
				<p>
					<label for="phys-addr-2"><?php _e('Address 2:', 'event_espresso'); ?></label><br/>
					<input size="20" id="phys-addr-2" type="text"  value="<?php echo $_venue->address2(); ?>" name="address2" />
				</p>
				<p>
					<label for="phys-city"><?php _e('City:', 'event_espresso'); ?></label><br/>
					<input size="20" id="phys-city" type="text"  value="<?php echo $_venue->city(); ?>" name="city" />
				</p>
				<p>
					<label for="phys-state"><?php _e('State:', 'event_espresso'); ?></label><br/>
					<?php echo $states_dropdown; ?>
				</p>
				<p>
					<label for="phys-country"><?php _e('Country:', 'event_espresso'); ?></label><br/>
					<?php echo $countries_dropdown; ?>
				</p>
				<p>
					<label for="zip-postal"><?php _e('Zip/Postal Code:', 'event_espresso'); ?></label><br/>
					<input size="20" id="zip-postal" type="text"  value="<?php echo $_venue->zip(); ?>" name="zip" />
				</p>
				
				<p>
					<?php _e('Google Map Link (for email):', 'event_espresso'); ?>
					<?php echo $_venue->google_map_link(); ?> 
				</p>

				<?php if( isset( EE_Registry::instance()->CFG->map_settings->use_google_maps ) && EE_Registry::instance()->CFG->map_settings->use_google_maps ) { ?>
				<p>
					<label for="enable_for_gmap">
						<?php _e('Display Google Map for this venue? ', 'event_espresso') ?>
					</label>
					<?php echo $enable_for_gmap; ?> 
				</p>
				<?php } ?>
			</fieldset>
		</td>
			
		<td valign="top" class="manual-venue">
			<fieldset id="virt-location">
				<h4>
					<?php _e('Virtual Location', 'event_espresso'); ?>
				</h4>
				<p>
					<label for="url-event" style="display:inline-block; width:100px; vertical-align:top;">
						<?php _e('URL of Event:', 'event_espresso'); ?>
					</label>
					<textarea id="url-event" cols="30" rows="4" name="virtual_url"><?php echo $_venue->virtual_url(); ?></textarea>
				</p>
				<p>
					<label for="call-in-num" style="display:inline-block; width:100px;">
						<?php _e('Call in Number:', 'event_espresso'); ?>
					</label>
					<input id="call-in-num" size="20" type="text"  value="<?php echo $_venue->virtual_phone(); ?>" name="virtual_phone" />
				</p>
			</fieldset>
		</td>
		
	</tr>
</table>
<?php if( EE_Registry::instance()->CFG->map_settings || ! isset( EE_Registry::instance()->CFG->map_settings->use_google_maps ) || ! EE_Registry::instance()->CFG->map_settings->use_google_maps ) { ?>
<p class="ee-notice">
	<?php
		echo sprintf( 
			__('To display a Google Map for event venues, go to %sEvent Espresso General Settings%sGoogle Maps%s, and set "Activate Google Maps" to "Yes"', 'event_espresso' ),
			'<b>',
			'</b> &raquo; <b>',
			'</b>'
		); 
	?>
</p>						
<?php } ?>