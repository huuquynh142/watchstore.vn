<?php
namespace App\Library;
	class OnePay{
		//-------------------------------------------------------------------------NOI DIA -----------------------------------------------//
		public $NOIDIA_SECURE_SECRET = "A3EFDFABA8653DF2342E8DAC29B51AF0";
		public $noidia_response_code = "";
		public $noidia_vpc_AccessCode = "D67342C2";
		public $noidiaUrl = 'https://mtf.onepay.vn/onecomm-pay/vpc.op';
		function buildNoidiaUrl($id, $phone, $amount, $backUrl, $username = '', $email = ''){
			
			$params = array('Title' => 'VPC 3-Party', 
											'virtualPaymentClientURL' => $this->noidiaUrl, 
											'vpc_Merchant' => 'ONEPAY',
											'vpc_AccessCode' => $this->noidia_vpc_AccessCode, 
											'vpc_MerchTxnRef' => time(),
											'vpc_OrderInfo' => 'Mã hóa đơn  '.$id,
											'vpc_Amount' => $amount,
											'vpc_ReturnURL' => $backUrl, 
											'vpc_Version' => '2', 
											'vpc_Command' => 'pay', 
											'vpc_Locale' => 'vn',
											'vpc_Currency' => 'VND', 
											'vpc_TicketNo' => '127.0.0.1',
											'vpc_SHIP_Street01' => '95 chua boc',
											'vpc_SHIP_Provice' => 'Dong da',
											'vpc_SHIP_City' => 'Ha Noi', 
											'vpc_SHIP_Country' => 'Viet Nam', 
											'vpc_Customer_Phone' => $phone, 
											'vpc_Customer_Email' => $email, 
											'vpc_Customer_Id' => $username);

			$SECURE_SECRET = $this->NOIDIA_SECURE_SECRET;

			// add the start of the vpcURL querystring parameters
			// *****************************Lấy giá trị url cổng thanh toán*****************************
			$vpcURL = $params["virtualPaymentClientURL"] . "?";

			// Remove the Virtual Payment Client URL from the parameter hash as we 
			// do not want to send these fields to the Virtual Payment Client.
			// bỏ giá trị url và nút submit ra khỏi mảng dữ liệu
			unset($params["virtualPaymentClientURL"]); 
			unset($params["SubButL"]);

			//$stringHashData = $SECURE_SECRET; *****************************Khởi tạo chuỗi dữ liệu mã hóa trống*****************************
			$stringHashData = "";
			// sắp xếp dữ liệu theo thứ tự a-z trước khi nối lại
			// arrange array data a-z before make a hash
			ksort ($params);

			// set a parameter to show the first pair in the URL
			// đặt tham số đếm = 0
			$appendAmp = 0;

			foreach($params as $key => $value) {

					// create the md5 input and URL leaving out any fields that have no value
					// tạo chuỗi đầu dữ liệu những tham số có dữ liệu
					if (strlen($value) > 0) {
							// this ensures the first paramter of the URL is preceded by the '?' char
							if ($appendAmp == 0) {
									$vpcURL .= urlencode($key) . '=' . urlencode($value);
									$appendAmp = 1;
							} else {
									$vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
							}
							//$stringHashData .= $value; *****************************sử dụng cả tên và giá trị tham số để mã hóa*****************************
							if ((strlen($value) > 0) && ((substr($key, 0,4)=="vpc_") || (substr($key,0,5) =="user_"))) {
							$stringHashData .= $key . "=" . $value . "&";
					}
					}
			}
			//*****************************xóa ký tự & ở thừa ở cuối chuỗi dữ liệu mã hóa*****************************
			$stringHashData = rtrim($stringHashData, "&");
			// Create the secure hash and append it to the Virtual Payment Client Data if
			// the merchant secret has been provided.
			// thêm giá trị chuỗi mã hóa dữ liệu được tạo ra ở trên vào cuối url
			if (strlen($SECURE_SECRET) > 0) {
					//$vpcURL .= "&vpc_SecureHash=" . strtoupper(md5($stringHashData));
					// *****************************Thay hàm mã hóa dữ liệu*****************************
					$vpcURL .= "&vpc_SecureHash=" . strtoupper(hash_hmac('SHA256', $stringHashData, pack('H*',$SECURE_SECRET)));
			}

			// FINISH TRANSACTION - Redirect the customers using the Digital Order
			// ===================================================================
			// chuyển trình duyệt sang cổng thanh toán theo URL được tạo ra
			return $vpcURL;
		}
		
		public function getNoidiaResponse($params){
			
			$vpc_Txn_Secure_Hash = $params ["vpc_SecureHash"];
			unset ( $params ["vpc_SecureHash"] );

			// set a flag to indicate if hash has been validated
			$errorExists = false;
			$SECURE_SECRET = $this->NOIDIA_SECURE_SECRET;
			if (strlen ( $SECURE_SECRET ) > 0 && $params ["vpc_TxnResponseCode"] != "7" && $params ["vpc_TxnResponseCode"] != "No Value Returned") {
				
					//$stringHashData = $SECURE_SECRET;
					//*****************************khởi tạo chuỗi mã hóa rỗng*****************************
					$stringHashData = "";
				
				// sort all the incoming vpc response fields and leave out any with no value
				foreach ( $params as $key => $value ) {
			//        if ($key != "vpc_SecureHash" or strlen($value) > 0) {
			//            $stringHashData .= $value;
			//        }
			//      *****************************chỉ lấy các tham số bắt đầu bằng "vpc_" hoặc "user_" và khác trống và không phải chuỗi hash code trả về*****************************
							if ($key != "vpc_SecureHash" && (strlen($value) > 0) && ((substr($key, 0,4)=="vpc_") || (substr($key,0,5) =="user_"))) {
							$stringHashData .= $key . "=" . $value . "&";
					}
				}
			//  *****************************Xóa dấu & thừa cuối chuỗi dữ liệu*****************************
					$stringHashData = rtrim($stringHashData, "&");	
				
				
			//    if (strtoupper ( $vpc_Txn_Secure_Hash ) == strtoupper ( md5 ( $stringHashData ) )) {
			//    *****************************Thay hàm tạo chuỗi mã hóa*****************************
				if (strtoupper ( $vpc_Txn_Secure_Hash ) == strtoupper(hash_hmac('SHA256', $stringHashData, pack('H*',$SECURE_SECRET)))) {
					// Secure Hash validation succeeded, add a data field to be displayed
					// later.
					$hashValidated = "CORRECT";
				} else {
					// Secure Hash validation failed, add a data field to be displayed
					// later.
					$hashValidated = "INVALID HASH";
				}
			} else {
				// Secure Hash was not validated, add a data field to be displayed later.
				$hashValidated = "INVALID HASH";
			}

			// Define Variables
			// ----------------
			// Extract the available receipt fields from the VPC Response
			// If not present then let the value be equal to 'No Value Returned'
			// Standard Receipt Data
			$amount = $this->null2unknown ( $params ["vpc_Amount"] );
			$locale = $this->null2unknown ( $params ["vpc_Locale"] );
			//$batchNo = $this->null2unknown ( $params ["vpc_BatchNo"] );
			$command = $this->null2unknown ( $params ["vpc_Command"] );
			//$message = $this->null2unknown ( $params ["vpc_Message"] );
			$version = $this->null2unknown ( $params ["vpc_Version"] );
			//$cardType = $this->null2unknown ( $params ["vpc_Card"] );
			$orderInfo = $this->null2unknown ( $params ["vpc_OrderInfo"] );
			//$receiptNo = $this->null2unknown ( $params ["vpc_ReceiptNo"] );
			$merchantID = $this->null2unknown ( $params ["vpc_Merchant"] );
			//$authorizeID = $this->null2unknown ( $params ["vpc_AuthorizeId"] );
			$merchTxnRef = $this->null2unknown ( $params ["vpc_MerchTxnRef"] );
			$transactionNo = $this->null2unknown ( $params ["vpc_TransactionNo"] );
			//$acqResponseCode = $this->null2unknown ( $params ["vpc_AcqResponseCode"] );
			$txnResponseCode = $this->null2unknown ( $params ["vpc_TxnResponseCode"] );
			
			$this->noidia_response_code = $txnResponseCode;
			
			$transStatus = "";
			
			if($hashValidated=="CORRECT" && $txnResponseCode=="0"){
				return "OK";
			}elseif ($hashValidated=="INVALID HASH" && $txnResponseCode=="0"){
				return "PENDING";
			}else {
				return "FAILED";
			}
		}
		public function getNoidiaResponseString(){
			return $this->getNoidiaResponseDescription($this->noidia_response_code);
		}
		
		function getNoidiaResponseDescription($responseCode) {
	
			switch ($responseCode) {
				case "0" :
					$result = "Giao dịch thành công - Approved";
					break;
				case "1" :
					$result = "Ngân hàng từ chối giao dịch - Bank Declined";
					break;
				case "3" :
					$result = "Mã đơn vị không tồn tại - Merchant not exist";
					break;
				case "4" :
					$result = "Không đúng access code - Invalid access code";
					break;
				case "5" :
					$result = "Số tiền không hợp lệ - Invalid amount";
					break;
				case "6" :
					$result = "Mã tiền tệ không tồn tại - Invalid currency code";
					break;
				case "7" :
					$result = "Lỗi không xác định - Unspecified Failure ";
					break;
				case "8" :
					$result = "Số thẻ không đúng - Invalid card Number";
					break;
				case "9" :
					$result = "Tên chủ thẻ không đúng - Invalid card name";
					break;
				case "10" :
					$result = "Thẻ hết hạn/Thẻ bị khóa - Expired Card";
					break;
				case "11" :
					$result = "Thẻ chưa đăng ký sử dụng dịch vụ - Card Not Registed Service(internet banking)";
					break;
				case "12" :
					$result = "Ngày phát hành/Hết hạn không đúng - Invalid card date";
					break;
				case "13" :
					$result = "Vượt quá hạn mức thanh toán - Exist Amount";
					break;
				case "21" :
					$result = "Số tiền không đủ để thanh toán - Insufficient fund";
					break;
				case "99" :
					$result = "Người sủ dụng hủy giao dịch - User cancel";
					break;
				default :
					$result = "Giao dịch thất bại - Failured";
			}
			return $result;
		}
		
		

		// If input is null, returns string "No Value Returned", else returns input
		function null2unknown($data)
		{
				if ($data == "") {
						return "No Value Returned";
				} else {
						return $data;
				}
		}
	}