<?php

session_set_cookie_params(3600*24*7); //set session cookie lifetime to 7 days. Don't forget to change the server config, too! - change session.gc_maxlifetime to 259200 secs
session_cache_expire(3600*24*7); //sets server session cache lifetime. neets to be set before EVERY call to session_start. Or just change the default value in the server config.
session_start();

?>
