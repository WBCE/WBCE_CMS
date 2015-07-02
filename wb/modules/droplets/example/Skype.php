//:Your skype status as an image
//:Commandline to use: [[skype?user=skypename]]
$content='<div class="popup">Check skypename!</div>';
$user = (isset($user) && ($user!='') ? $user : '');
if($user=='') { return $content; }
return '<div class="popup"><img src="http://mystatus.skype.com/'.$user.'.png?t='.time().'" alt="My Skype status" /></div>';
