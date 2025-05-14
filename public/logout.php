<?php
// public/logout.php

session_start();
session_destroy();

// Redirige al usuario al front controller (index.php)
header("Location: index.php", true, 302);
exit;
?>