<?php

@header('HTTP/1.1 301 Moved Permanently', TRUE, 301);
exit(header('Location: ../index.php'));