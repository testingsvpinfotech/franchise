<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
//require_once('FedEx.php');
//require_once('TrackService/Track.php');

class Track {
	private $request = array();
	protected $_soapClient;
	//private $request;
	private $endPoint;	
	private $key;
	private $password;
	private $acct_num;
	private $meter_num;
	private $k;
	private $p;
	private $a;
	private $m;
	private $service_id;
	private $major;
	private $intermediate;
	private $minor;
	private $customerTransactionId;
    /**
     *  Constructor requires the key, password,
     *  account number and password for the FedEx
     *  account to use the API. You may also override
     *  the default WSDL file.
     *
     *  @param string   // FedEx Key
     *  @param string   // FedEx account password
     *  @param string   // Account number
     *  @param string   // Meter number
     */
     	public function __construct($wsdlFile = 'TrackService_v10.wsdl')
	{
		$wsdlPath = __DIR__.'/TrackService_v10.wsdl';
		// Will throw a SoapFault exception if wsdlPath is unreachable
		$this->_soapClient = new \SoapClient($wsdlPath, array('trace' => true));		
		// Initialize request to an empty array
		$this->request = array();
		$this->customerTransactionId = "Defualt ID";
		$this->key = 'ejetE7glG2N7rUn0';
		$this->password = '78dJxxQlaCzcOE5AADFkHiena';
		$this->acct_num = '510087720';
		$this->meter_num = '114007781';
		// TODO: Change to env() and default to beta
		$this->endPoint = 'https://wsbeta.fedex.com:443/web-services';
		      $this->setCustomerTransactionId('');
	$this->setVersion('trck', 10, 0, 0);
	}/*
	public function __construct($key, $passwd, $acct, $meter,
        $wsdlFile = 'TrackService_v10.wsdl')
    {
        parent::__construct($wsdlFile, $key, $passwd, $acct, $meter);
        // TODO: Set this in env()?
        $this->endPoint = 'https://wsbeta.fedex.com:443/web-services';
  
    }*/
    /**
     *  Gets the tracking detials for the
     *  given tracking number and returns
     *  the FedEx request as an object.
     *
     *  @param string   // Tracking #
     *  @return SoapClient Object
     */
    public function getByTrackingId($id) {
    	// Request syntax needed to track by tracking id
    	$this->request['SelectionDetails'] = array(
			'PackageIdentifier' => array(
				'Type' => 'TRACKING_NUMBER_OR_DOORTAG',
				'Value' => $id // Tracking ID to track
			)
		);
    	$req = $this->buildRequest($this->request); echo '<pre>'; print_r($req);
    	return $this->getSoapClient()->track($req);;
    }

	
	/**
	 * 	Create a new FedEx instance.
	 *	Requires a WSDL file, and FedEx
	 *	API credentials.
	 *
	 *	@param string 	// WSDL File
	 *	@param string 	// Key
	 * 	@param string 	// Password
	 *	@param string 	// Account Number
	 *	@param string 	// Meter number
	 * 	@return void
	 */

	/**
	 * 	Retrieve the SoapClient object.
	 *
	 *	@return SoapClient
	 */
	public function getSoapClient()
    {
        return $this->_soapClient;
    }
    /**
     *	Sets the TransactionDetail/CustomerTransactionId
     *	for the current request.
     *
     *	@param string 	// Any text up to 40 characters
     *	@return void
     */
    public function setCustomerTransactionId($id) {
    	$this->customerTransactionId = $id;
    }
	/**
     *	Gets the TransactionDetail/CustomerTransactionId
     *	for the current request.
     *
     *	@param string 	// Any transaction ID
     *	@return string
     */
    public function getCustomerTransactionId($id) {
    	return $this->customerTransactionId;
    }
    /**
     *	Sets the API service to be used
     *	and the version information.
     *
     *	@param string 	// Service to use, ie. "trck"
     * 	@param int 		// Major version to use, ie. 9
     * 	@param int 		// Intermediate version to use, ie. 1
     * 	@param int 		// Minor version to use, ie. 0
     */
    public function setVersion($service_id, $major, $intermediate, $minor) {
    	$this->service_id = $service_id;
    	$this->major = $major;
    	$this->intermediate = $intermediate;
    	$this->minor = $minor;
    }
    /**
     * 	Builds and returns the basic request array to be sent
     *	with each request to the FedEx API. This assumes the
     *	setVersion method has already been called by an extended class.
   	 *
     *	Takes an optional parameter, $addReq. This parameter is 
     *	used to set the additonal request details. These details
     *	are determined by the particular service being called and
     *	are passed by the extended service classes.
     *
     *	@param array 	// Additonal request details
     */
    public function buildRequest($addReq = null) {
    	// Build Authentication
    	$this->request['WebAuthenticationDetail'] = array(
			'UserCredential'=> array(
				'Key' 		=> $this->key, 
				'Password'	=> $this->password
			)
		);
		//Build Client Detail
		$this->request['ClientDetail'] = array(
			'AccountNumber' => $this->acct_num, 
			'MeterNumber' 	=> $this->meter_num
		);
		// Build Customer Transaction Id
		$this->request['TransactionDetail'] = array(
			'CustomerTransactionId' => $this->customerTransactionId
		);
		
		// Build API Version info
		$this->request['Version'] = array(
			'ServiceId' 	=> $this->service_id, 
			'Major' 		=> $this->major, 
			'Intermediate'	=> $this->intermediate, 
			'Minor' 		=> $this->minor
		);
		// Enable detailed scans
		$this->request['ProcessingOptions'] = 'INCLUDE_DETAILED_SCANS';
		if(!is_null($addReq))
			$this->request = array_merge($this->request, $addReq);
		return $this->request;
    }
}