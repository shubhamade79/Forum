<?php
echo "Logging You Out. Please Wait...";

// Destroy the session
session_start();
session_destroy();

// Redirect the user to another page
header("Location: /Forum");
exit;
?>