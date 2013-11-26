<?php

if (!defined('EVENT_ESPRESSO_VERSION') )
	exit('NO direct script access allowed');

/**
 * Event Espresso
 *
 * Event Registration and Management Plugin for WordPress
 *
 * @ package		Event Espresso
 * @ author			Seth Shoultes
 * @ copyright		(c) 2008-2011 Event Espresso  All Rights Reserved.
 * @ license		http://eventespresso.com/support/terms-conditions/   * see Plugin Licensing *
 * @ link			http://www.eventespresso.com
 * @ version		3.2
 *
 * ------------------------------------------------------------------------
 *
 * EE_Messages_Email_Payment_Reminder_Validator class
 *
 * Holds any special validation rules for template fields with Email messenger and Payment message type.
 *
 * @package		Event Espresso
 * @subpackage	includes/core/messages/validators/email/EE_Messages_Email_Payment_Reminder_Validator.class.php
 * @author		Darren Ethier
 *
 * ------------------------------------------------------------------------
 */
class EE_Messages_Email_Payment_Reminder_Validator extends EE_Messages_Validator {


	public function __construct( $fields, $context ) {
		$this->_m_name = 'email';
		$this->_mt_name = 'payment_reminder';

		parent::__construct( $fields, $context );
	}

	/**
	 * at this point no custom validation needed for this messenger/message_type combo.
	 */
	protected function _modify_validator() {
		$new_config = $this->_MSGR->get_validator_config();

		//modify just event_list
		$new_config['event_list'] = array(
			'shortcodes' => array('event', 'transaction', 'ticket_list', 'attendee')
			);
		$new_config['ticket_list'] = array(
			'shortcodes' => array('ticket', 'event_list', 'transaction', 'attendee')
			);
		$new_config['content'] = array(
			'shortcodes' => array('event_list', 'ticket_list', 'transaction', 'registration', 'organization', 'attendee')
			);
		$this->_MSGR->set_validator_config( $new_config );
	}

} //end class EE_Messages_Email_Payment_Reminder_Validator