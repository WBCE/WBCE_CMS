<?php
/**
@file
@brief This file simply blocks directory access.
*/
// no directory access
header("Location: ../index.php",TRUE,301);
