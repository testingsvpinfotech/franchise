<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mylibrary {

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function send_email($to)
    {
        $this->CI->load->library('email');
       /* $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;

        $this->CI->email->initialize($config);*/
        
        $this->CI->email->from('info@rajcargo.net', 'Rajcargo Admin');
        $this->CI->email->to($to); 
        $this->CI->email->subject("Your Shipment Update");
        $this->CI->email->message("Your Shipment at location");    

        $this->CI->email->send();
    }
}