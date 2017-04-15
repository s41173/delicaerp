<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Xmlrpc_server extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
    }

	function index()
	{
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');

		$config['functions']['Greetings'] = array('function' => 'Xmlrpc_server.process');

		$this->xmlrpcs->initialize($config);
		$this->xmlrpcs->serve();
	}


	function process($request)
	{
		$parameters = $request->output_parameters();

		$response = array( array( 'you_said'  => $parameters['0'],
					  'i_respond' => 'Not bad at all.'),
		 		          'struct');

		return $this->xmlrpc->send_response($response);
	}
}

?>