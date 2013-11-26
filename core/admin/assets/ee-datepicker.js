var dttPickerHelper = {
	//some private defaults for the datetimepicker
	dttOptions : {
		dateFormat : 'yy-mm-dd',
		timeFormat: 'h:mm tt',
		ampm: true,
		separator: '  ',
		stepHour: 1,
		stepMinute: 5,
		hourGrid: 2,
		minuteGrid: 5,
		minDateTime: null,
		maxDateTime: null,
		numberOfMonths: 2,
		hour: null,
		minute: null,
		defaultDate: null,
		showOn:'focus'
	},


	//selector elements
	startobj: {}, //jquery selector obj for start date
	endobj: {}, //jquery selector obj for end date
	nextobj: {}, //jquery selector obj for next field to focus in on after date selected.

	pickerobj: {}, //holds the dtt picker object.


	//defaults for start and end dates
	startDate: {},
	endDate: {},


	setminDateTime: function(date, format) {
		format = typeof(format) === 'undefined' ? 'YYYY-MM-DD h:mm a' : format;
		this.dttOptions.minDateTime = moment(date, format);
		return this;
	},


	setmaxDateTime: function(date, format) {
		format = typeof(format) === 'undefined' ? 'YYYY-MM-DD h:mm a' : format;
		this.dttOptions.maxDateTime = moment(date, format);
		return this;
	},



	picker: function(start, end, next, doingstart) {
		if ( typeof(doingstart) === 'undefined' ) doingstart = true;

		this.startobj = start;
		this.endobj = end;
		
		this.pickerobj = doingstart ? this.startobj : this.endobj;

		this.nextobj = next;

		this.startDate = this.startobj.val() === '' ? moment() : moment(this.startobj.val(), 'YYYY-MM-DD h:mm a');

		this.endDate = this.endobj instanceof jQuery ? this.endobj.val() : '';

		this.endDate = this.endDate === '' ? this.startDate.clone().add('hours', 1) : moment(this.endDate, 'YYYY-MM-DD h:mm a');

		this.dttOptions.hour = doingstart ? this.startDate.hours() : this.endDate.hours();
		this.dttOptions.minute = doingstart ? this.startDate.minutes() : this.endDate.minutes();
		this.dttOptions.defaultDate = doingstart ? this.startDate.toDate() : this.endDate.toDate();

		//set min and max if necessary
		if ( !doingstart ) {
			var minDateTime = this.startDate;
			this.dttOptions.minDateTime = this.dttOptions.minDateTime === null ? minDateTime.toDate() : this.dttOptions.minDateTime;
			this.dttOptions.maxDateTime = this.dttOptions.maxDateTime === null ? minDateTime.clone().add('years', 100).toDate() : this.dttOptions.maxDateTime;
		} else {
			this.dttOptions.minDateTime = null;
			this.dttOptions.maxDateTime = null;
		}/**/


		this.dttOptions.onSelect = function(dateText, inst) {
			//get diff from original start date
		};

		this.dttOptions.onClose = function(dateText, dpinst) {
				var newDate = moment( dateText, 'YYYY-MM-DD h:mm a'),
					lastVal = moment(dpinst.lastVal, 'YYYY-MM-DD h:mm a'),
					diff = lastVal !== null ? lastVal.diff(newDate, 'minutes') : newDate;

				if ( doingstart ) {
					dttPickerHelper.startDate = newDate;
					if ( dttPickerHelper.endobj instanceof jQuery )
						dttPickerHelper.endobj.val(dttPickerHelper.endDate.format('YYYY-MM-DD h:mm a'));
					//dttPickerHelper.nextobj.focus();
				} else {
					dttPickerHelper.endDate = newDate;
					dttPickerHelper.startobj.val(dttPickerHelper.startDate.format('YYYY-MM-DD h:mm a'));
					//dttPickerHelper.nextobj.focus();
				}


				if ( dttPickerHelper.startDate.isAfter(dttPickerHelper.endDate) ) {
					if ( doingstart )
						//use the already calculated diff to set the new endDate or startDate.
						if ( dttPickerHelper.endobj instanceof jQuery )
							dttPickerHelper.endobj.val(dttPickerHelper.endDate.clone().subtract('minutes', diff).format('YYYY-MM-DD h:mm a'));
					else
						dttPickerHelper.startobj.val(dttPickerHelper.startDate.clone().subtract('minutes', diff).format('YYYY-MM-DD h:mm a') );
				}
				dttPickerHelper.resetpicker();
				return false;
			};

			this.pickerobj.datetimepicker(this.dttOptions);

	},


	resetpicker: function() {
		this.startobj = {};
		this.endobj = {};
		this.nextobj = {};
		this.startDate = {};
		this.endDate = {};
		this.dttOptions = {
			dateFormat : 'yy-mm-dd',
			timeFormat: 'h:mm tt',
			ampm: true,
			separator: '  ',
			stepHour: 1,
			stepMinute: 5,
			hourGrid: 2,
			minuteGrid: 5,
			minDateTime: null,
			maxDateTime: null,
			numberOfMonths: 2,
			hour: null,
			minute: null,
			defaultDate: null,
			showOn:'focus'
		};
		this.dttOptions.minDate = null;
		this.dttOptions.maxDate = null;
		this.dttOptions.defaultDate = null;
		jQuery.datepicker.setDefaults(this.dttOptions); //make sure we reset all instances of the datepicker.
		return this;
	}

};