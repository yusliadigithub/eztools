<?php

/**
 * @return array 
 * @author MJMZ <mohdjazli@live.com>
 */

return [

	// put all common contants here
	'common' => [
		'app_environment'=>'development', // value mus be (production / development)
		'paginate'=> 10,
		'address_limit' => 5, // limit address for guest user 
		'systemtimezone'=> 'Asia/Kuala_Lumpur',
		'cpanelsubdomain' => 0,
		'paymentslip_limit' => 3,
		'molpay'=> 'MOLPay', //this value should be same (case sensitive) as value in master_payment_method.payment_method_description
		'ipay88'=> 'ipay88', //this value should be same (case sensitive) as value in master_payment_method.payment_method_description
		'senangpay'=> 'SenangPay', //this value should be same (case sensitive) as value in master_payment_method.payment_method_description
	],

];