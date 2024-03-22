<?php
defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(E_ALL);
ini_set('display_errors', 0);
class Recharge_wallet extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
        $this->load->model('basic_operation_m');
        $this->data['company_info'] = $this->basic_operation_m->get_query_row("select * from tbl_company limit 1");

        if ($this->session->userdata('customer_id') == '') {
            redirect('franchise');
        }
    }

    public function index()
    {
        $amount = $_POST['recharge_wallet'];
        if (isset($_POST['save'])) {
            $payUrl = "https://payment1.atomtech.in/ots/aipay/auth";
            $customer_id = $_SESSION['customer_id'];
            $franchise_info = $this->db->query("select * from tbl_customers where customer_id = '$customer_id' and isdeleted = '0'")->row();
            $transactionId = 'T' . sprintf("%09d", mt_rand(1, 999999));
            $this->load->library("atom_payment/AtompayRequest", array(
                "Login" => "550759",
                "Password" => "a73af54d",
                "ProductId" => "SOLUTION",
                "Amount" => $amount,
                "TransactionCurrency" => "INR",
                "TransactionAmount" => $amount,
                "ReturnUrl" => base_url("paymentdemo/confirm"),
                "ClientCode" => $franchise_info->cid,
                "TransactionId" => $transactionId,
                "CustomerEmailId" => $franchise_info->email,
                "CustomerMobile" => $franchise_info->phone,
                "udf1" => $franchise_info->customer_name, // optional udf1
                "udf2" => "", // optional udf2
                // "udf2" => $franchise_info->address, // optional udf2
                "udf3" => "", // optional udf3
                "udf4" => "", // optional udf4
                "udf5" => "", // optional udf5
                // "CustomerAccount" => "4215",
                //"CustomerAccount" => "639827",
                "url" => $payUrl,
                "RequestEncypritonKey" => "36368313EABE4FB6574B8D15D2F83FBB",
                "ResponseDecryptionKey" => "4065D97E7546074BA0234CC8F16EBF34",
            ), 'atompayrequestf');

            $data = array(
                'atomTokenId' => $this->atompayrequestf->payNow(),
                'transactionId' => $transactionId,
                'merchId' => "550759",
                'amount' => $amount
            );
            // print_r($transactionId);die;
            if ($_SESSION['customer_type'] == '2') {
                $this->load->view("franchise/wallet_recharge/pay_amount", $data);
            } else {
                $this->load->view("masterfranchise/wallet_recharge/pay_amount", $data);
            }

        }
    }

    public function transection_entery()
    {
        // echo '<pre>';print_r($_POST);die;
        $order = $this->db->query("select max(t_id) as id from tbl_wallet_recharge_transection")->row('id');
        $order_id = $order + 1;
        $order_id1 = 'T000' . $order_id;
        $customer_id = $_SESSION['customer_id'];
        $franchise_info = $this->db->query("select * from tbl_customers where customer_id = '$customer_id' and isdeleted = '0'")->row();
        date_default_timezone_set('Asia/Kolkata');
        $date = date('d-m-Y H:i:s');
        $data = [
            'order_id' => $order_id1,
            'customer_id' => $franchise_info->customer_id,
            'customer_cid' => $franchise_info->cid,
            'customer_name' => $franchise_info->customer_name,
            'transection_id' => $_POST['transection_id'],
            'amount' => $_POST['amount'],
            'transection_time_stamp' => date('Y-m-d h:i:sa', strtotime($date)),
        ];
        
        if ($this->db->insert('tbl_wallet_recharge_transection', $data)) {
            echo json_encode(1);
        }
    }

    // to get response pass below as return URL in your view
    public function response()
    {
        $this->load->library('session');
        $this->load->library("atom_payment/AtompayResponse", array(
            "data" => $_POST['encData'],
            "merchId" => $_POST['merchId'],
            "ResponseDecryptionKey" => "4065D97E7546074BA0234CC8F16EBF34",
        ), 'atompayresponse');
        $status = $this->atompayresponse->decryptResponseIntoArray()['responseDetails']['message'];
        if ($status == 'SUCCESS') {
            $date = $this->atompayresponse->decryptResponseIntoArray()['merchDetails']['merchTxnDate'];
            $t_id = $this->atompayresponse->decryptResponseIntoArray()['merchDetails']['merchTxnId'];
            $this->db->trans_start();
         
            $trans = $this->db->query("select * from tbl_wallet_recharge_transection where transection_id = '$t_id'")->row();
            $customer_id = $trans->customer_id;
            $franchise_info = $this->db->query("select * from tbl_customers where customer_id = '$customer_id' and isdeleted = '0'")->row();
            $branch_name = $this->db->query("select * from tbl_branch where branch_id = '$franchise_info->branch_id'")->row('branch_name');
            $this->session->set_userdata("customer_name", $franchise_info->customer_name);
            $this->session->set_userdata("email", $franchise_info->email);
            $this->session->set_userdata("customer_type", $franchise_info->customer_type);
            $this->session->set_userdata("customer_id", $franchise_info->customer_id);
            $this->session->set_userdata("branch_name", $branch_name);
            $this->session->set_userdata("branch_id", $franchise_info->branch_id);
            $wallet = $franchise_info->wallet + $trans->amount;
            $data = [
                'customer_id' => $franchise_info->customer_id,
                'franchise_id' => $franchise_info->cid,
                'transaction_id' => $t_id,
                'credit_amount' => $trans->amount,
                'balance_amount' => $wallet,
                'payment_mode' => 'Credit',
                'status' => '1',
                'refrence_no' => 'Payment Gateway transaction',
                'payment_date' => date('Y-m-d', strtotime($date)),
                'bank_name' => $this->atompayresponse->decryptResponseIntoArray()['payModeSpecificData']['bankDetails']['otsBankName'],
                'c_date' => $date,
            ];
            $this->db->insert('franchise_topup_balance_tbl', $data);
            // transaction wallet logs update 
            $message = $this->atompayresponse->decryptResponseIntoArray()['responseDetails']['message'];
           $desc =  $this->atompayresponse->decryptResponseIntoArray()['responseDetails']['description'];
           date_default_timezone_set('Asia/Kolkata'); $date = date('Y-m-d H:i');
           $this->db->update('tbl_wallet_recharge_transection', ['status' => 1, 'response_message' => $message,'response_description'=>$desc, 'response_time_stamp' => $date], ['transection_id' => $t_id]);
            // customer table updation 
            $this->db->update('tbl_customers', ['wallet' => $wallet], ['customer_id' => $customer_id]);
            $this->db->trans_complete();
            if ($this->db->trans_status() == TRUE) {
                $this->db->trans_commit();
                if ($_SESSION['customer_type'] == '2') {
                    $msg = 'Wallet updated successfully. <br>
                        Please check the transaction status below.';
                    $class = 'alert alert-success alert-dismissible';
                    $this->session->set_flashdata('notify', $msg);
                    $this->session->set_flashdata('class', $class);
                    redirect('franchise-payment-transaction');
                } else {
                    $msg = 'Wallet updated successfully. <br>
                        Please check the transaction status below.';
                    $class = 'alert alert-success alert-dismissible';
                    $this->session->set_flashdata('notify', $msg);
                    $this->session->set_flashdata('class', $class);
                    redirect('m-franchise-payment-transaction');
                }

            }
        } else {
            $date = $this->atompayresponse->decryptResponseIntoArray()['merchDetails']['merchTxnDate'];
            $t_id = $this->atompayresponse->decryptResponseIntoArray()['merchDetails']['merchTxnId'];
            date_default_timezone_set('Asia/Kolkata'); $date = date('Y-m-d H:i');
            $this->db->trans_start();
            $message = $this->atompayresponse->decryptResponseIntoArray()['responseDetails']['message'];
            $desc =  $this->atompayresponse->decryptResponseIntoArray()['responseDetails']['description'];
            $this->db->update('tbl_wallet_recharge_transection', ['status' => 2,'response_message' => $message,'response_description'=>$desc,'response_time_stamp' => $date], ['transection_id' => $t_id]);
            $this->db->trans_complete();
            if ($this->db->trans_status() == TRUE) {
                $this->db->trans_commit();
                if ($_SESSION['customer_type'] == '2') {
                    $msg = 'Wallet updated successfully. <br>
                    Please check the transaction status below.';
                    $class = 'alert alert-success alert-dismissible';
                    $this->session->set_flashdata('notify', $msg);
                    $this->session->set_flashdata('class', $class);
                    redirect('franchise-payment-transaction');
                } else {
                    $msg = 'Wallet updated successfully. <br>
                        Please check the transaction status below.';
                    $class = 'alert alert-success alert-dismissible';
                    $this->session->set_flashdata('notify', $msg);
                    $this->session->set_flashdata('class', $class);
                    redirect('m-franchise-payment-transaction');
                }
            }
        }
        // to get data from above arrays use below code

        // echo "Transaction Result: ".$this->atompayresponse->decryptResponseIntoArray()['responseDetails']['statusCode']."<br><br>";
        // echo "Merchant Transaction Id: ".$this->atompayresponse->decryptResponseIntoArray()['merchDetails']['merchTxnId']."<br><br>";
        // echo "Transaction Date: ".$this->atompayresponse->decryptResponseIntoArray()['merchDetails']['merchTxnDate']."<br><br>";
        // echo "Bank Transaction Date: ".$this->atompayresponse->decryptResponseIntoArray()['payModeSpecificData']['bankDetails']['bankTxnId']."<br><br>";

    }

    public function encrypt($data = '', $key = NULL, $salt = "")
    {
        if ($key != NULL && $data != "" && $salt != "") {
            $method = "AES-256-CBC";
            //Converting Array to bytes
            $iv = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
            $chars = array_map("chr", $iv);
            $IVbytes = join($chars);
            $salt1 = mb_convert_encoding($salt, "UTF-8"); //Encoding to UTF-8
            $key1 = mb_convert_encoding($key, "UTF-8"); //Encoding to UTF-8
            //SecretKeyFactory Instance of PBKDF2WithHmacSHA512 Java Equivalent
            $hash = openssl_pbkdf2($key1, $salt1, '256', '65536', 'sha512');
            $encrypted = openssl_encrypt($data, $method, $hash, OPENSSL_RAW_DATA, $IVbytes);
            return bin2hex($encrypted);
        } else {
            return "String to encrypt, Salt and Key is required.";
        }
    }

    public function decrypt($data = "", $key = NULL, $salt = "")
    {
        if ($key != NULL && $data != "" && $salt != "") {
            $dataEncypted = hex2bin($data);
            $method = "AES-256-CBC";
            //Converting Array to bytes
            $iv = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
            $chars = array_map("chr", $iv);
            $IVbytes = join($chars);
            $salt1 = mb_convert_encoding($salt, "UTF-8"); //Encoding to UTF-8
            $key1 = mb_convert_encoding($key, "UTF-8"); //Encoding to UTF-8
            //SecretKeyFactory Instance of PBKDF2WithHmacSHA512 Java Equivalent
            $hash = openssl_pbkdf2($key1, $salt1, '256', '65536', 'sha512');
            $decrypted = openssl_decrypt($dataEncypted, $method, $hash, OPENSSL_RAW_DATA, $IVbytes);
            return $decrypted;
        } else {
            return "Encrypted String to decrypt, Salt and Key is required.";
        }
    }

    public function refresh_transcation()
    {

        $customer_id = $_SESSION['customer_id'];

        $transaction = $this->db->query("SELECT * FROM tbl_wallet_recharge_transection WHERE status ='0' AND customer_id = '$customer_id'")->result();
        // echo '<pre>';print_r($transaction);die;
        foreach($transaction as $key => $transection) {
            $merchant_id = "550759";
            $password = "a73af54d";
            $encKey = "36368313EABE4FB6574B8D15D2F83FBB";
            $decKey = "4065D97E7546074BA0234CC8F16EBF34";
            $req_hash = "8fa0ed71bb9715066c";
            $resp_hash = "f1491920f462b7045e";
            $merchTxnId = $transection->transection_id;
            $merchTxnDate = date('Y-m-d', strtotime($transection->transection_time_stamp));
            $amount = $transection->amount;
            $txn_curn = "INR";
            $api = "TXNVERIFICATION";
            //merchID,password, merchTxnID,amount,txnCurrency, api, reqHashKey
            $str = $merchant_id . $password . $merchTxnId . $amount . $txn_curn . $api;
            $signature = hash_hmac("sha512", $str, $req_hash);
            $data = '{ "payInstrument" : { "headDetails" : { "api" : "TXNVERIFICATION", "source" : "source" }, "merchDetails" : { "merchId" : ' . $merchant_id . ', "password" : "' . $password . '", "merchTxnId" : "' . $merchTxnId . '", "merchTxnDate" : "' . $merchTxnDate . '" }, "payDetails" : { "amount" : ' . $amount . ', "txnCurrency" : "' . $txn_curn . '", "signature" :  "' . $signature . '" }}}';
            $enc = $this->encrypt($data, $encKey, $encKey);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                //  CURLOPT_URL => "https://caller.atomtech.in/ots/payment/status",
                CURLOPT_URL => "https://payment1.atomtech.in/ots/payment/status",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_PORT => 443,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "merchId=" . $merchant_id . "&encData=" . $enc,
                CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/x-www-form-urlencoded"
                    )
            )
            );
            $response = curl_exec($curl);
            $err = curl_error($curl);
            $exp_resp = explode("&", $response);
            $exp_encdata = explode("=", $exp_resp[0]);
            $resp_encData = $exp_encdata[1];
            if ($err) {
                echo '<p>Curl error: "' . $err . ". Error in gateway credentials.</p>";
                exit;
            }
            curl_close($curl);
            $dec = $this->decrypt($resp_encData, $decKey, $decKey);
            $transection_responce = json_decode($dec, true);
            // transaction faild code : OTS0506 AND SUCCESS code : OTS0000
            $transection_status = $transection_responce['payInstrument'][0]['responseDetails']['statusCode'];
            
            //   echo '<pre>';print_r($transection_responce);die;
            if ($transection_status == 'OTS0000' || $transection_status = 'OTS0002') {
                $merchTxnDate = $transection_responce['payInstrument'][0]['merchDetails']['merchTxnDate'];
                $bank_name = $transection_responce['payInstrument'][0]['payModeSpecificData']['bankDetails']['otsBankName'];
                $message = $transection_responce['payInstrument'][0]['responseDetails']['message'];
                $desc = $transection_responce['payInstrument'][0]['responseDetails']['description'];
                date_default_timezone_set('Asia/Kolkata'); $date = date('Y-m-d H:i');
                $this->db->trans_start();
                $this->db->update('tbl_wallet_recharge_transection', ['status' => 1,'response_message' => $message,'response_description'=>$desc,'response_time_stamp' => $date], ['transection_id' => $transection->transection_id]);
              
                $trans = $this->db->query("select * from tbl_wallet_recharge_transection where transection_id = '$transection->transection_id'")->row();
                $customer_id = $trans->customer_id;
                $franchise_info = $this->db->query("select * from tbl_customers where customer_id = '$customer_id' and isdeleted = '0'")->row();
                $branch_name = $this->db->query("select * from tbl_branch where branch_id = '$franchise_info->branch_id'")->row('branch_name');
                 if(empty($bank_name)){$bank_name ="Current Bank";}
                $wallet = $franchise_info->wallet + $trans->amount;
                $data = [
                    'customer_id' => $franchise_info->customer_id,
                    'franchise_id' => $franchise_info->cid,
                    'transaction_id' => $transection->transection_id,
                    'credit_amount' => $trans->amount,
                    'balance_amount' => $wallet,
                    'payment_mode' => 'Credit',
                    'status' => '1',
                    'refrence_no' => 'Payment Gateway transaction',
                    'payment_date' => date('Y-m-d', strtotime($merchTxnDate)),
                    'bank_name' => $bank_name,
                    'c_date' => $merchTxnDate,
                ];
                $this->db->insert('franchise_topup_balance_tbl', $data);
                // echo $this->db->last_query();die;
                $this->db->update('tbl_customers', ['wallet' => $wallet], ['customer_id' => $customer_id]);
                $this->db->trans_complete();
                if ($this->db->trans_status() == TRUE) {
                    $this->db->trans_commit();
                    if ($_SESSION['customer_type'] == '2') {
                        $msg = 'Wallet updated successfully. <br>
                            Please check the transaction status below.';
                        $class = 'alert alert-success alert-dismissible';
                        $this->session->set_flashdata('notify', $msg);
                        $this->session->set_flashdata('class', $class);
                        redirect('franchise-payment-transaction');
                    } else {
                        $msg = 'Wallet updated successfully. <br>
                            Please check the transaction status below.';
                        $class = 'alert alert-success alert-dismissible';
                        $this->session->set_flashdata('notify', $msg);
                        $this->session->set_flashdata('class', $class);
                        redirect('m-franchise-payment-transaction');
                    }

                }
            } else {
                date_default_timezone_set('Asia/Kolkata'); $date = date('Y-m-d H:i');
                $message = $transection_responce['payInstrument'][0]['responseDetails']['message'];
                $desc = $transection_responce['payInstrument'][0]['responseDetails']['description'];
                $this->db->trans_start();
                $this->db->update('tbl_wallet_recharge_transection', ['status' => 3,'response_message' => $message,'response_description'=>$desc, 'response_time_stamp' => $date], ['transection_id' => $transection->transection_id]);
                $this->db->trans_complete();
                if ($this->db->trans_status() == TRUE) {
                    $this->db->trans_commit();
                    if ($_SESSION['customer_type'] == '2') {
                        $msg = 'Wallet updated successfully. <br>
                        Please check the transaction status below.';
                        $class = 'alert alert-success alert-dismissible';
                        $this->session->set_flashdata('notify', $msg);
                        $this->session->set_flashdata('class', $class);
                        redirect('franchise-payment-transaction');
                    } else {
                        $msg = 'Wallet updated successfully. <br>
                            Please check the transaction status below.';
                        $class = 'alert alert-success alert-dismissible';
                        $this->session->set_flashdata('notify', $msg);
                        $this->session->set_flashdata('class', $class);
                        redirect('m-franchise-payment-transaction');
                    }
                }
            }
        }

    }


    public function transaction_list()
    {
        $customer_id = $_SESSION['customer_id'];
        $data = [];
        $data['tot'] = $this->db->query("select sum(amount) as amount from tbl_wallet_recharge_transection where customer_id = '$customer_id' and status = '1'")->row('amount');
        $data['trans'] = $this->db->query("select * from tbl_wallet_recharge_transection where customer_id = '$customer_id' order by t_id desc")->result();
        $this->load->view('franchise/wallet_recharge/payment_transection', $data);
    }
    public function transaction_master_list()
    {
        $customer_id = $_SESSION['customer_id'];
        $data = [];
        $data['tot'] = $this->db->query("select sum(amount) as amount from tbl_wallet_recharge_transection where customer_id = '$customer_id' and status = '1'")->row('amount');
        $data['trans'] = $this->db->query("select * from tbl_wallet_recharge_transection where customer_id = '$customer_id' order by t_id desc")->result();
        //$data['trans'] = $this->db->get('tbl_wallet_recharge_transection')->where(['customer_id'=>$customer_id]);   
        $this->load->view('masterfranchise/wallet_recharge/payment_transection', $data);
    }

}