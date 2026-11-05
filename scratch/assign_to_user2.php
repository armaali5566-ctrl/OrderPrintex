<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
db_update('enquiries', ['user_id' => 2, 'customer_email' => 'customer@orderprintex.com'], 'id = 2');
db_update('quotations', ['user_id' => 2, 'customer_email' => 'customer@orderprintex.com'], 'id = 2');
echo "Updated enquiry 2 and quote 2 to user 2\n";

