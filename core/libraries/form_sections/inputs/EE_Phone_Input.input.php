<?php
if ( !defined( 'EVENT_ESPRESSO_VERSION' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 *
 * EE_Phone_Input
 *
 * Validates that the phone number is either 10 digits, or like
 * 123-123-1231
 *
 * @package			Event Espresso
 * @subpackage
 * @author				Mike Nelson
 *
 */
class EE_Phone_Input extends EE_Text_Input{
	/**
	 * @param array $options
	 */
	function __construct( $options = array() ){
		//set and filter regex
		$phone_number_regex = isset( $options[ 'phone_number_regex' ] ) ? $options[ 'phone_number_regex' ] : '~^([\d]{10})|([\d]{3}-[\d]{3}-[\d]{4})$~';
		$phone_number_regex = apply_filters( 'FHEE__EE_Phone_Input____construct__phone_number_regex', $phone_number_regex, $this );
		//set and filter example
		$phone_number_example = isset( $options[ 'phone_number_example' ] ) ? $options[ 'phone_number_example' ] : '123-456-7890 or 1234567890';
		$phone_number_example = apply_filters( 'FHEE__EE_Phone_Input____construct__phone_number_example', $phone_number_example, $this );
		// add strategy
		$this->_add_validation_strategy(
			new EE_Text_Validation_Strategy(
				sprintf( __( 'Please enter a valid phone number. Example: %1$s', 'event_espresso' ), $phone_number_example ),
				$phone_number_regex
			)
		);
		parent::__construct( $options );
	}
}

// End of file EE_Phone_Input.input.php