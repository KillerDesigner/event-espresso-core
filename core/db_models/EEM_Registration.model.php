<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) exit('No direct script access allowed');
/**
 * Event Espresso
 *
 * Event Registration and Management Plugin for WordPress
 *
 * @ package			Event Espresso
 * @ author				Seth Shoultes
 * @ copyright		(c) 2008-2011 Event Espresso  All Rights Reserved.
 * @ license			http://eventespresso.com/support/terms-conditions/   * see Plugin Licensing *
 * @ link					http://www.eventespresso.com
 * @ version		 	4.0
 *
 * ------------------------------------------------------------------------
 *
 * Registration Model
 *
 * @package			Event Espresso
 * @subpackage		includes/models/
 * @author				Brent Christensen
 *
 * ------------------------------------------------------------------------
 */
require_once ( EE_MODELS . 'EEM_Base.model.php' );
require_once ( EE_CLASSES . 'EE_Registration.class.php' );


class EEM_Registration extends EEM_Base {

  	// private instance of the Registration object
	private static $_instance = NULL;

	/**
	 * Keys are the status IDs for registrations (eg, RAP, RCN, etc), and the values 
	 * are status codes (eg, approved, cancelled, etc)
	 * @var array 
	 */
	private static $_reg_status;

	/**
	 * The value of REG_count for a primary registrant
	 */
	const PRIMARY_REGISTRANT_COUNT = 1;

	
	
	/**
	 * Status ID (STS_ID on esp_status table) to indicate a pending registration
	 */
	const status_id_pending = 'RPN';
	
	
	/**
	 * Status ID (STS_ID on esp_status table) to indicate a cancelled registration
	 */
	const status_id_cancelled = 'RCN';
	
	
	/**
	 * Status ID (STS_ID on esp_status table) to indicate an approved registration
	 */
	const status_id_approved = 'RAP';
	
	
	/**
	 * Status ID (STS_ID on esp_status table) to indicate an unapproved registration
	 */
	const status_id_not_approved = 'RNA';
	/**
	 *		private constructor to prevent direct creation
	 *		@Constructor
	 *		@access protected
	 *		@param string $timezone string representing the timezone we want to set for returned Date Time Strings (and any incoming timezone data that gets saved).  Note this just sends the timezone info to the date time model field objects.  Default is NULL (and will be assumed using the set timezone in the 'timezone_string' wp option)
	 *		@return void
	 */
	protected function __construct( $timezone ) {
		$this->singular_item = __('Registration','event_espresso');
		$this->plural_item = __('Registrations','event_espresso');
		$this->_get_registration_status_array();
//		require_once(EE_CLASSES . 'EE_Registration.class.php');
		$this->_allowed_statuses=apply_filters('FHEE__EEM_Registration__allowed_statuses', self::$_reg_status );

		$this->_tables = array(
			'Registration'=>new EE_Primary_Table('esp_registration','REG_ID')
		);
		$this->_fields = array(
			'Registration'=>array(
				'REG_ID'=>new EE_Primary_Key_Int_Field('REG_ID', __('Registration ID','event_espresso')),
				'EVT_ID'=>new EE_Foreign_Key_Int_Field('EVT_ID', __('Even tID','event_espresso'), false, 0, 'Event'),
				'ATT_ID'=>new EE_Foreign_Key_Int_Field('ATT_ID', __('Attendee ID','event_espresso'), false, 0, 'Attendee'),
				'TXN_ID'=>new EE_Foreign_Key_Int_Field('TXN_ID', __('Transaction ID','event_espresso'), false, 0, 'Transaction'),
				'TKT_ID'=>new EE_Foreign_Key_Int_Field('TKT_ID', __('Ticket ID','event_espresso'), false, 0, 'Ticket'),
				'STS_ID'=>new EE_Foreign_Key_String_Field('STS_ID', __('Status ID','event_espresso'), false, EEM_Registration::status_id_not_approved, 'Status'),
				'REG_date'=>new EE_Datetime_Field('REG_date', __('Time registration occured','event_espresso'), false, current_time('timestamp'), $timezone ),
				'REG_final_price'=>new EE_Money_Field('REG_final_price', __('Final Price of registration','event_espresso'), false, 0),
				'REG_session'=>new EE_Plain_Text_Field('REG_session', __('Session ID of registration','event_espresso'), false, ''),
				'REG_code'=>new EE_Plain_Text_Field('REG_code', __('Unique Code for this registration','event_espresso'), false, ''),
				'REG_url_link'=>new EE_Plain_Text_Field('REG_url_link', __('String to be used in URL for identifying registration','event_espresso'), false, ''),
				'REG_count'=>new EE_Integer_Field('REG_count', __('Count of this registration in the group registraion ','event_espresso'), true, 1),
				'REG_group_size'=>new EE_Integer_Field('REG_group_size', __('Number of registrations on this group','event_espresso'), false, 1),
				'REG_att_is_going'=>new EE_Boolean_Field('REG_att_is_going', __('Flag indicating the registrant plans on attending','event_espresso'), false, false),	
			)
		);
		$this->_model_relations = array(
			'Event'=>new EE_Belongs_To_Relation(),
			'Attendee'=>new EE_Belongs_To_Relation(),
			'Transaction'=>new EE_Belongs_To_Relation(),
			'Ticket'=>new EE_Belongs_To_Relation(),
			'Status'=>new EE_Belongs_To_Relation(),
			'Answer'=>new EE_Has_Many_Relation(),
			'Checkin'=>new EE_Has_Many_Relation()
		);
		
		parent::__construct( $timezone );
	}




	/**
	 *		This funtion is a singleton method used to instantiate the Espresso_model object
	 *
	 *		@access public
	 *		@param string $timezone string representing the timezone we want to set for returned Date Time Strings (and any incoming timezone data that gets saved).  Note this just sends the timezone info to the date time model field objects.  Default is NULL (and will be assumed using the set timezone in the 'timezone_string' wp option)
	 *		@return EEM_Registration instance
	 */
	public static function instance( $timezone = NULL ){

		// check if instance of Espresso_model already exists
		if ( self::$_instance === NULL ) {
			// instantiate Espresso_model
			self::$_instance = new self( $timezone );
		}

		//we might have a timezone set, let set_timezone decide what to do with it
		self::$_instance->set_timezone( $timezone );
		
		// Espresso_model object
		return self::$_instance;
	}








	/**
	 * 		get list of registration statuses
	*		@access private
	*		@return void
	*/
	public static function reg_status_array( $exclude = array() ) {
		call_user_func_array( array( EEM_Registration::instance(), '_get_registration_status_array' ), array($exclude ) );
		return self::$_reg_status;
	}








	/**
	 * 		get list of registration statuses
	*		@access private
	*		@return void
	*/
	private function _get_registration_status_array( $exclude = array() ) {

		global $wpdb;
		$SQL = 'SELECT STS_ID, STS_code FROM '. $wpdb->prefix . 'esp_status WHERE STS_type = "registration"';
		$results = $wpdb->get_results( $SQL );

		self::$_reg_status = array();
		foreach ( $results as $status ) {
			if ( ! in_array( $status->STS_ID, $exclude )) {
				self::$_reg_status[ $status->STS_ID ] = $status->STS_code;
			}
		}
	}




	/**
	 * This returns a wpdb->results array of all registration date month and years matching the incoming query params and grouped by month and year.
	 * @param  array  $query_params Array of query_parms as described in the comments for EEM_Base::get_all()
	 * @return wpdb results array
	 */
	public function get_reg_months_and_years( $where_params ) {
		$query_params[0] = $where_params;
		$query_params['group_by'] = array('reg_year', 'reg_month');
		$columns_to_select = array(
			'reg_year' => array('YEAR(REG_date)', '%s'),
			'reg_month' => array('MONTHNAME(REG_date)', '%s')
			);
		return $this->_get_all_wpdb_results( $query_params, OBJECT, $columns_to_select );
	}




	/**
	*		retreive ALL registrations for a particular Attendee from db
	* 		@access		public
	* 		@param		int		$ATT_ID
	*		@return 	EE_Registration[]
	*/
	public function get_all_registrations_for_attendee( $ATT_ID = FALSE, $status_array = FALSE ) {

		if ( ! $ATT_ID ) {
			return FALSE;
		}
		if ( $registrations = $this->get_all( array( array( 'ATT_ID' => $ATT_ID )))) {
			return $registrations;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * Gets a registration given their REG_url_link. Yes, this should usually 
	 * be passed via a GET parameter.
	 * @param string $REG_url_link
	 * @return EE_Registration
	 */
	public function get_registration_for_reg_url_link($REG_url_link){
		if(!$REG_url_link){
			return false;
		}
		return $this->get_one(array(array('REG_url_link'=>$REG_url_link)));
	}




	/**
	*		retreive registration for a specific transaction attendee from db
	* 
	* 		@access		public
	* 		@param		$TXN_ID
	* 		@param		$ATT_ID
	* 		@param		$att_nmbr 	in case the ATT_ID is the same for multiple registrations (same details used) then the attendee number is required
	*		@return 		mixed		array on success, FALSE on fail
	*/
	public function get_registration_for_transaction_attendee( $TXN_ID = FALSE, $ATT_ID = FALSE, $att_nmbr = FALSE ) {
		return $this->get_one(array(
			array(
				'TXN_ID'=>$TXN_ID,
				'ATT_ID'=>$ATT_ID),
			'limit'=>array($att_nmbr-1,1)
		));
	}


	/**
	*		get the number of registrations per day  for the Registration Admin page Reports Tab.
	 *		(doesn't utilize models because it's a fairly specialized query)
	* 		@access		public
	 *		@param $period string which can be passed to php's strtotime functino (eg "-1 month")
	 *		@return stdClass[] with properties regDate and total
	*/
	public function get_registrations_per_day_report( $period = '-1 month' ) {
		$sql_date = date("Y-m-d H:i:s", strtotime($period));
		$results = $this->_get_all_wpdb_results(
				array(
					array('REG_date'=>array('>=',$sql_date)),
					'group_by'=>'regDate',
					'order_by'=>array('REG_date'=>'DESC')
				), 
				OBJECT, 
				array(
					'regDate'=>array('DATE(Registration.REG_date)','%s'),
					'total'=>array('count(REG_ID)','%d')
				));
		return $results;
	}





	/**
	*		get the number of registrations per event  for the Registration Admin page Reports Tab
	* 		@access		public
	 *		@return stdClass[] each with properties event_name, reg_limit, and total
	*/
	public function get_registrations_per_event_report( $period = '-1 month' ) {		
		$date_sql = date("Y-m-d H:i:s", strtotime($period));
		$results = $this->_get_all_wpdb_results(array(
			array(
				'REG_date'=>array('>=',$date_sql)
			),
			'group_by'=>'Event.EVT_name',
			'order_by'=>'Event.EVT_name',
			'limit'=>array(0,24)),
			OBJECT, 
			array(
				'event_name'=>array('Event_CPT.post_title','%s'),
				'total'=>array('COUNT(REG_ID)','%s')
			)
		);
		
		return $results;

	}


	/**
	 * Returns the EE_Registration of the primary attendee on the transaction id provided
	 * @param int $TXN_ID
	 * @return EE_Registration
	 */
	public function get_primary_registration_for_transaction_ID( $TXN_ID = FALSE){
		if( ! $TXN_ID ){
			return false;
		}
		return $this->get_one(array(array('TXN_ID'=>$TXN_ID,'REG_count'=>  EEM_Registration::PRIMARY_REGISTRANT_COUNT)));
	}


	/**
	 *		get_event_registration_count
	 *
	 *		@access public
	 *		@param int $EVT_ID 
	 *		@param boolean $for_incomplete_payments
	 *		@return int
	 */
	public function get_event_registration_count ( $EVT_ID, $for_incomplete_payments = FALSE ) {		
		$query_params = array(
			array(
				'EVT_ID'=>$EVT_ID,
				'STS_ID' => EEM_Registration::status_id_approved
			),
			'default_where_conditions' => 'none'
		);
		if( $for_incomplete_payments ){
			$query_params[0]['Transaction.STS_ID']=array('!=',  EEM_Transaction::complete_status_code);
		}

		if( EE_Registry::instance()->CFG->registration->pending_counts_reg_limit ){
			$query_params[0]['OR'] = array( 'STS_ID' => EEM_Registration::status_id_pending, 'STS_ID*' => EEM_Registration::status_id_approved );
		}
		return $this->count($query_params);	
	}




}
// End of file EEM_Registration.model.php
// Location: /includes/models/EEM_Registration.model.php