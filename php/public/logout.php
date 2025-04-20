<?php
session_start();
session_destroy();
header('Location: /avgFlask/php/public/login.php');
exit();
