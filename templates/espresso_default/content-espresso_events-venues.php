<?php //echo '<h1>' . __FILE__ . '</h1>'; ?>
<?php global $post; ?>
<?php  if ( espresso_display_venue_address_in_event_details() ) : ?>
<?php do_action( 'AHEE_event_details_before_venue_details', $post );?> 

<h3 class="event-venues-h3 ee-event-h3">
	<span class="ee-icon ee-icon-venue"></span><?php _e( 'Event Location', 'event_espresso' ); ?>
</h3><br/>
<div class="espresso-venue-dv">
	<h4><strong><?php _e( 'Venue:', 'event_espresso' ); ?></strong>&nbsp;&nbsp; <strong> <?php espresso_venue_name(); ?></strong></h4>	
	<p><span class="smaller-text tags-links"><?php echo espresso_venue_categories(); ?></span></p>
<?php  if ( $venue_phone = espresso_venue_phone( $post->ID, FALSE )) : ?>	
	<p>
		<span class="small-text"><strong><?php _e( 'Venue Phone:', 'event_espresso' ); ?></strong></span> <?php echo $venue_phone; ?> 
	</p>
<?php endif;  ?>
<?php  if ( espresso_venue_has_address( $post->ID )) : ?>	
	<strong><span class="dashicons dashicons-location-alt"></span><?php _e( 'Address:', 'event_espresso' ); ?></strong>
	<?php espresso_venue_address( 'inline' ); ?>
	<?php espresso_venue_gmap( $post->ID ); ?>
	<div class="clear"><br/></div>
<?php endif;  ?>
<?php  if ( has_excerpt() ) : ?>	
	<p>
		<strong><?php _e( 'Description:', 'event_espresso' ); ?></strong><br/>
		<?php echo espresso_venue_excerpt( $post->ID ); ?>									
	</p>
<?php endif;  ?>

</div>
<!-- .espresso-venue-dv -->
<br/>
<?php do_action( 'AHEE_event_details_after_venue_details', $post ); ?>
<?php endif;  ?>
