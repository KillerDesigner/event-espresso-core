<?php
if( is_array( $registrations_for_free_events ) && ! empty( $registrations_for_free_events ) ) {
	echo apply_filters(
		'FHEE__registration_page_payment_options__no_payment_required_hdr',
		sprintf(
			__( '%1$sNo Payment Required%2$s', 'event_espresso' ),
			'<h6>',
			'</h6>'
		)
	);
	foreach ( $registrations_for_free_events as $registration_for_free_event ) {
		if ( $registration_for_free_event instanceof EE_Registration && $registration_for_free_event->ticket()->is_free() ) {
			if ( $registration_for_free_event->event() instanceof EE_Event ) {
				?>
				<p>
					<?php echo apply_filters(
						'FHEE__registration_page_payment_options__no_payment_required_pg',
						sprintf(
							__( '%1$s is a free event, so no billing will occur.', 'event_espresso' ),
							$registration_for_free_event->event()->name()
						)
					); ?>
				</p>
			<?php
			}
		}
	}
}
echo $default_hidden_inputs;
echo $extra_hidden_inputs;
?>
