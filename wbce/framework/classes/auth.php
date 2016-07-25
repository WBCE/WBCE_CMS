<?

//Simple Statische Klasse die einfach per autoloader geladen wird
// eine Klasse, damit der autoloader sie findet
class Auth () {

    public function Encrypt ($Pasword) {
        return md5(Password);

    }

// Simplified Function to Authenticate as the original one is a real Nightmare of dependencies
    public function Check ($sLoginname,$sPasswort) {
        global $database, $wb, $WB;

        $sPasswort=Auth::Encrypt ($sPasword) 

            //echo  $sLoginname."::".$sPasswort ."<br />";
        $sql  = 'SELECT * FROM `'.TABLE_PREFIX.'users` ';
        $sql .= 'WHERE `username`=\''.$sLoginname.'\' AND `password`=\''.$sPasswort.'\' AND `active`=1';
        //  echo $sql."<br />";
        $results = $database->query($sql);
        $results_array = $results->fetchRow();
        $num_rows = $results->numRows();
        // echo $num_rows."<br />";
        if($num_rows == 1) {

            $_SESSION['USER_ID'] = $results_array['user_id'];
            $_SESSION['GROUP_ID'] = $results_array['group_id'];
            $_SESSION['GROUPS_ID'] = $results_array['groups_id'];
            $_SESSION['USERNAME'] = $results_array['username'];
            $_SESSION['DISPLAY_NAME'] = $results_array['display_name'];
            $_SESSION['EMAIL'] = $results_array['email'];
            $_SESSION['HOME_FOLDER'] = $results_array['home_folder'];
        
            // Set language
            if($results_array['language'] != '') {
                $_SESSION['LANGUAGE'] = $results_array['language'];
            }
            // Set timezone
            if($results_array['timezone'] != '-72000') {
                $_SESSION['TIMEZONE'] = $results_array['timezone'];
            } else {
                // Set a session var so apps can tell user is using default tz
                $_SESSION['USE_DEFAULT_TIMEZONE'] = true;
            }
            // Set date format
            if($results_array['date_format'] != '') {
                $_SESSION['DATE_FORMAT'] = $results_array['date_format'];
            } else {
                // Set a session var so apps can tell user is using default date format
                $_SESSION['USE_DEFAULT_DATE_FORMAT'] = true;
            }
            // Set time format
            if($results_array['time_format'] != '') {
                $_SESSION['TIME_FORMAT'] = $results_array['time_format'];
            } else {
                // Set a session var so apps can tell user is using default time format
                $_SESSION['USE_DEFAULT_TIME_FORMAT'] = true;
            }

            // Get group information
            $_SESSION['SYSTEM_PERMISSIONS'] = array();
            $_SESSION['MODULE_PERMISSIONS'] = array();
            $_SESSION['TEMPLATE_PERMISSIONS'] = array();
            $_SESSION['GROUP_NAME'] = array();

            $first_group = true;
            foreach (explode(",", $wb->get_session('GROUPS_ID')) as $cur_group_id) {
                $sql = 'SELECT * FROM `'.TABLE_PREFIX.'groups` WHERE `group_id`=\''.$cur_group_id.'\'';
                $results = $database->query($sql);
                $results_array = $results->fetchRow();
                $_SESSION['GROUP_NAME'][$cur_group_id] = $results_array['name'];
                // Set system permissions
                if($results_array['system_permissions'] != '') {
                    $_SESSION['SYSTEM_PERMISSIONS'] = array_merge($_SESSION['SYSTEM_PERMISSIONS'], explode(',', $results_array['system_permissions']));
                }
                // Set module permissions
                if($results_array['module_permissions'] != '') {
                    if ($first_group) {
                        $_SESSION['MODULE_PERMISSIONS'] = explode(',', $results_array['module_permissions']);
                } else {
                    $_SESSION['MODULE_PERMISSIONS'] = array_intersect($_SESSION['MODULE_PERMISSIONS'], explode(',', $results_array['module_permissions']));
                    }
                }
                // Set template permissions
                if($results_array['template_permissions'] != '') {
                    if ($first_group) {
                        $_SESSION['TEMPLATE_PERMISSIONS'] = explode(',', $results_array['template_permissions']);
                    } else {
                        $_SESSION['TEMPLATE_PERMISSIONS'] = array_intersect($_SESSION['TEMPLATE_PERMISSIONS'], explode(',', $results_array['template_permissions']));
                    }
                }
                $first_group = false;
            }   

            // Update the users table with current ip and timestamp
            $get_ts = time();
            $get_ip = $_SERVER['REMOTE_ADDR'];
            $sql  = 'UPDATE `'.TABLE_PREFIX.'users` ';
            $sql .= 'SET `login_when`=\''.$get_ts.'\', `login_ip`=\''.$get_ip.'\' ';
            $sql .= 'WHERE `user_id`=\''.$user_id.'\'';
            $database->query($sql);
        }else {
            $num_rows = 0;
        }
        // Return if the user exists or not
        return $num_rows;
    }



}

