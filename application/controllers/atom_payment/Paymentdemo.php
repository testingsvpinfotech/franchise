<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Paymentdemo extends CI_Controller {

    public function pay()
    {
        
          $transactionId =  sprintf("%06d", mt_rand(1, 999999));
          $payUrl = "https://paynetzuat.atomtech.in/paynetz/epi/fts";
         
          $this->load->library("atom_payment/AtompayRequest",array(
                    "Login" => "317157",
                    "Password" => "Test@123",
                    "ProductId" => "NSE",
                    "Amount" => "50.00",
                    "TransactionCurrency" => "INR",
                    "TransactionAmount" => "50.00",
                    "ReturnUrl" => base_url("paymentdemo/confirm"),
                    "ClientCode" => "007",
                    "TransactionId" => $transactionId,
                    "CustomerName" => "Atom Dev", // udf1
                    "CustomerEmailId" => "atomdev@gmail.com", // udf2
                    "CustomerMobile" => "8888888888", // udf3
                    "CustomerBillingAddress" => "Andheri Mumbai", // udf4
                    "CustomerAccount" => "639827",
                    "ReqHashKey" => "KEY123657234",
                    "mode" => 'uat',  // set prod for production
                    "RequestEncypritonKey" => "A4476C2062FFA58980DC8F79EB6A799E",
                    "Salt" => "A4476C2062FFA58980DC8F79EB6A799E",
                    "ResponseDecypritonKey" => "75AEF0FA1B94B3C10D4F5B268F757F11",
                    "ResponseSalt" => "75AEF0FA1B94B3C10D4F5B268F757F11",
            ),'atompayrequest'); 
        
            echo $this->atompayrequest->payNow();
    }
    
    public function confirm()
    {

        $this->load->library('atom_payment/AtompayResponse'); 

        $this->atompayresponse->setRespHashKey("KEYRESP123657234");
        $this->atompayresponse->setResponseEncypritonKey("8E41C78439831010F81F61C344B7BFC7");
        $this->atompayresponse->setSalt("8E41C78439831010F81F61C344B7BFC7");

        $arrayofdata = $this->atompayresponse->decryptResponseIntoArray($_POST['encdata']);
        
        
      if(!empty($arrayofdata)){
        
        if($this->atompayresponse->validateResponse($arrayofdata)){

             echo "<pre>";
             print_r($arrayofdata);
             echo "<br><br>";  

              if($arrayofdata['f_code'] == "Ok"){

                   echo "payment has been successfully completed.";

               }else if($arrayofdata['f_code'] == "F"){

                   echo "Payment has been failed !!";

               }else if($arrayofdata['f_code'] == "C") {
                 
                   echo "Payment has been cancelled by the user !!";
                  
               }else{
                  
                  echo "Payment has been failed due to unknown reason !!";
                  
              }
            
         }else{
            
            log_message('error', 'Error while validate signature data from atom payment gateway.');
            echo "Payment failed due to techical error caused during making the payment";
            
        }
        
      }else{
          
           log_message('error', 'Data received is not proper encoded data ! Data received ='.$_POST['encdata']);
           echo "Payment failed due to techical error caused during making the payment";
     }  
        
           
    }
    
    
    
    
}
