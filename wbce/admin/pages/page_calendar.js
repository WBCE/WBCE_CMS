Calendar.setup(
	{
	inputField  : start_date,
	ifFormat    : jscal_ifformat,
	button      : trigger_start,
	firstDay    : jscal_firstday,
	showsTime   : showsTime,
	timeFormat  : timeFormat,
	date        : jscal_today,
	range       : [1970, 2037],
	step        : 1
	}
);
Calendar.setup(
	{
	inputField  : end_date,
	ifFormat    : jscal_ifformat,
	button      : trigger_end,
	firstDay    : jscal_firstday,
	showsTime   : showsTime,
	timeFormat  : timeFormat,
	date        : jscal_today,
	range       : [1970, 2037],
	step        : 1
	}
);