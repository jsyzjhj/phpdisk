<?php 
##
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.google.com
#
#	$Id: PayResponseHandler.class.php 121 2014-03-04 12:38:05Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
##

require ("ResponseHandler.class.php");
class PayResponseHandler extends ResponseHandler {
	
	/**
	*@Override
	*/
	function isTenpaySign() {
		$cmdno = $this->getParameter("cmdno");
		$pay_result = $this->getParameter("pay_result");
		$date = $this->getParameter("date");
		$transaction_id = $this->getParameter("transaction_id");
		$sp_billno = $this->getParameter("sp_billno");
		$total_fee = $this->getParameter("total_fee");		
		$fee_type = $this->getParameter("fee_type");
		$attach = $this->getParameter("attach");
		$key = $this->getKey();
		
		$signPars = "";
		//组织签名串
		$signPars = "cmdno=" . $cmdno . "&" .
				"pay_result=" . $pay_result . "&" .
				"date=" . $date . "&" .
				"transaction_id=" . $transaction_id . "&" .
				"sp_billno=" . $sp_billno . "&" .
				"total_fee=" . $total_fee . "&" .
				"fee_type=" . $fee_type . "&" .
				"attach=" . $attach . "&" .
				"key=" . $key;
				
		$sign = strtolower(md5($signPars));
		
		$tenpaySign = strtolower($this->getParameter("sign"));
		
		//debug信息
		$this->_setDebugInfo($signPars . " => sign:" . $sign .
				" tenpaySign:" . $this->getParameter("sign"));
		
		return $sign == $tenpaySign;
		
	}
	
}


?>