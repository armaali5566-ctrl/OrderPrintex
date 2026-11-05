<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
print_r(db_fetch_all('DESCRIBE products'));

