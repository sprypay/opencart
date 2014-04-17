<?php
// Heading
$_['heading_title']      = 'SpryPay';

// Text
$_['text_payment']       = 'Payment';
$_['text_success']       = 'Success: You have modified Sprypay account details!';
$_['text_sprypay']       = '<a onclick="window.open(\'http://sprypay.ru\');"><img src="http://sprypay.ru/templates/users/images/sprypay.button.png" alt="SpryPay" title="SpryPay" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_successful']    = 'On - Always Successful';
$_['text_declined']      = 'On - Always Declined';
$_['text_off']           = 'Off';

// Entry
$_['entry_shop']         = 'Shop ID:';
$_['entry_secret']       = 'Shop secret:';
$_['entry_callback']     = 'IPN URL:<br /><span class="help">This has to be set in the Sprypay control panel.</span>';
$_['entry_test']         = 'Test Mode:';
$_['entry_total']        = 'Total:<br /><span class="help">The checkout total the order must reach before this payment method becomes active.</span>';
$_['entry_order_status'] = 'Order Status after payment:';

$_['entry_script']  = 'Script of checkout';
$_['entry_preorder_status'] = 'Order Status after confirmation:';
$_['entry_script_before']  = '<b>Before payment</b><br>Orders appear in the "Sales" => "orders" when a customer clicks on the "Confirm" button and BEFORE payment';

$_['entry_script_after']  = '<b>After payment</b><br>Order will appear in "Sales" => "Orders" when customer pay his order in sprypay.ru';

$_['entry_geo_zone']     = 'Geo Zone:';
$_['entry_status']       = 'Status:';
$_['entry_sort_order']   = 'Sort Order:';

// Error
$_['error_permission']   = 'Warning: You do not have permission to modify payment Sprypay!';
$_['error_shop']         = 'Shop ID required!';
$_['error_secret']       = 'Shop secret required!';
?>
