  if ( typeof jQuery != 'undefined' ) {
    jQuery(document).ready(function($)
    {
      if ( typeof $.tablesorter != 'undefined' ) {
        $("#myTable").tablesorter({
            headers: {
              0: {sorter: false},
              4: {sorter: false}
            },
            widgets: ['zebra'],
            widgetZebra: {
                css: ['row_b','row_a']
            }
        });
	  }
    });
  }
