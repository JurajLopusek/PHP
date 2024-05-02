<?php
session_start();
session_unset();
session_destroy();
header("location: /Webte2Final/src/index.php?action=logout-success");
?>