<?php

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));


//Fetch the default content for Template 
 
$query = "SELECT * FROM ".TABLE_PREFIX."search WHERE extra = ''";
$results = $database->query($query);

// Query current settings in the db, then loop through them and print them
while($setting = $results->fetchRow())
{
    $setting_name = $setting['name'];
    $setting_value = htmlspecialchars(($setting['value']));
    switch($setting_name) {
        // Search header
        case 'header':
            $SEARCH_HEADER=$setting_value;
        break;
        // Search results header
        case 'results_header':
            $SEARCH_RESULTS_HEADER= $setting_value;
        break;
        // Search results loop
        case 'results_loop':
            $SEARCH_RESULTS_LOOP=$setting_value;
        break;
        // Search results footer
        case 'results_footer':
            $SEARCH_RESULTS_FOOTER=$setting_value;
        break;
        // Search no results
        case 'no_results':
            $SEARCH_NO_RESULTS=$setting_value;
        break;
        // Search footer
        case 'footer':
            $SEARCH_FOOTER=$setting_value;
        break;
        // Search module-order
        case 'module_order':
            $SEARCH_MODULE_ORDER=$setting_value;
        break;
        // Search max lines of excerpt
        case 'max_excerpt':
            $SEARCH_MAX_EXCERPT=$setting_value;
        break;
        // time-limit
        case 'time_limit':
            $SEARCH_TIME_LIMIT=$setting_value;
        break;
        // Search template
        case 'template':
            $SEARCH_TEMPLATE = $setting_value;
        break;
    }
}
 
