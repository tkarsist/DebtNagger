<?php

class MY_Controller extends CI_Controller
{
	
    function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        
        
    	if (!$this->session->userdata('logged_in'))
		{
			
			//$this->load->view('login');
			redirect('session/login');
		}
    }
	
	/*
	function __construct()
	{
		parent::__construct();
		
		$this->load->library('session');
		
		if (!$this->session->userdata('logged_in'))
		{
			echo "kukkuu";
			$this->load->view('login');
		}
	}
		*/
	

	
}