<?php namespace App\Library;

/**
 * @file Integrate with 11street store
 * @date 2018-06-04
 * @version 1.0
 * @author MJMZ <mohdjazli@live.com>
 */
class 11Street {

	public static $api_url = 'http://api.11street.my/rest';

	public function __construct($openapikey='')
	{
		if ($merchant_code) {
	      $this->setField('MerchantCode', $merchant_code);
	    }
	}
	
}