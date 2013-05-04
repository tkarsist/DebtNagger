<?php

class Session extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model('mailqueue_model');
		$this->load->model('user_model');
		//$this->output->enable_profiler(TRUE);
	}

	function login()
	{
		if($this->session->userdata('logged_in'))
		redirect('/');
		$this->load->view('login');

	}

	function forgot(){
		$this->load->view('forgot_view');
	}

	function reset(){
		$stamp=$this->input->post('stamp');
		$userid=$this->input->post('id');
		$hash=$this->input->post('hash');
		if($this->pwdResetHashCheck($userid, $stamp, $hash)){

			$pwd=$this->input->post('pwd');
			$this->user_model->resetPwd($userid,$pwd);
			$user=$this->user_model->getUser($userid);
			$this->createSession($user[0]);
			log_message('error','PASSWORD RESETTED: USERID:'.$userid.' EMAIL:'.$user[0]->EMAIL);
			redirect('main');

		}

			
			

	}

	function verify($userid,$stamp,$hash){
		if($this->pwdResetHashCheck($userid, $stamp, $hash)){
			$data['userid']=$userid;
			$data['stamp']=$stamp;
			$data['hash']=$hash;
			$this->load->view('reset_pwd_view',$data);
		}
		else
		redirect('main');

			
	}
	private function pwdResetHashCheck($userid,$stamp,$hash){

		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$start = $time;
		$currstamp=round($start,'0');


		if(($currstamp-$stamp)<VALID_PWD_RESET_TIME){

			$user=$this->user_model->getUser($userid);



			$userorig=$user[0]->ID;
			$nick=$user[0]->NICK;
			$lastlogin=$user[0]->LASTLOGIN;
			$email=$user[0]->EMAIL;

			$calchash=hash_hmac('sha256',$lastlogin.$nick.$lastlogin.$email.$userorig.$stamp,FORGOT_PWD_KEY);



			if($calchash==$hash){
				return true;
			}
			else{
				log_message('error','FATAL :PASSWORD RESET. HASH MISMATCH. USERID:'.$userid);
				return false;
			}

		}
		log_message('error','PASSWORD RESET. Link too old');
		return false;
	}

	function forgotSend(){
			
			
		if($this->input->post('email')!=NULL){
			$data=$this->user_model->checkMail($this->input->post('email'));
			if($data!=NULL){
				$userid=$data[0]->ID;
				$nick=$data[0]->NICK;
				$lastlogin=$data[0]->LASTLOGIN;
				$email=$this->input->post('email');
				$time = microtime();
				$time = explode(' ', $time);
				$time = $time[1] + $time[0];
				$start = $time;
				$stamp=round($start,'0');
				

				$hash=hash_hmac('sha256',$lastlogin.$nick.$lastlogin.$email.$userid.$stamp,FORGOT_PWD_KEY);
				$data['verifyurl']=base_url("index.php/session/verify/".$userid."/".$stamp."/".$hash);

				$message=$this->load->view('verifypwd_view',$data,TRUE);
				$subject='Password reset for DebtNagger';
				
				$this->mailqueue_model->addToQueue($email,$subject,$message);
				
				
				$this->session->set_flashdata('error2', 'Info: 	Password reset send to '.$email);
				log_message('error','PASSWORD FORGOTTEN SENT: USERID:'.$userid.' EMAIL:'.$email);
				$this->load->view('forgotthankyou_view');
					

			}
			else{
				log_message('error','PASSWORD FORGOTTEN WRONG MAIL: '.$this->input->post('email'));
				$this->session->set_flashdata('error2', 'Provide valid mail!!!');
				redirect('session/forgot');
			}


		}
	}

	function confirm($regid,$hash){
		$calchash=hash_hmac('sha256',$regid.REGISTER_KEY,REGISTER_KEY);
		if($calchash==$hash){

			$reguser=$this->user_model->getRegUser($regid);
			if($reguser!=NULL&&$this->user_model->checkMail($reguser[0]->EMAIL)==NULL){
				$id=$this->user_model->addUser($reguser[0]->NICK,$reguser[0]->EMAIL,$reguser[0]->PWD);
				$this->user_model->delRegUser($regid);
				$user=$this->user_model->getUser($id);
				$this->createSession($user[0]);
				$this->session->set_flashdata('error2', 'Welcome to DebtNagger');
				log_message('error','USER CONFIRMED ACCOUNT: USERID:'.$id.' EMAIL:'.$user[0]->EMAIL);
				redirect('main');
			}
			else{
				log_message('error','CONFIRM REGISTER: HASH OK. NO USER FOUND OR USER ALREADY EXISTS. REGID: '.$regid);
				redirect('main');
			}
		}
		else{
			log_message('error','CONFIRM REGISTER: WRONG HASH FOR REGID: '.$regid);
			redirect('main');
		}
	}


	function register(){

		if($this->input->post('submit')=='Register'&&$this->input->post('password_check')>=5){
				

			$this->form_validation->set_rules('nick', 'Name', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_userCheck');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

			if ($this->form_validation->run() == TRUE){
				$nick=$this->input->post('nick');
				$email=$this->input->post('email');
				$pwd=$this->input->post('password');
				$regid=$this->user_model->addRegUser($nick,$email,$pwd);

				$hash=hash_hmac('sha256',$regid.REGISTER_KEY,REGISTER_KEY);
				$data['verifyurl']=base_url("index.php/session/confirm/".$regid."/".$hash);

				$message=$this->load->view('verifyreg_view',$data,TRUE);
				$subject='Confirm registration for DebtNagger';
				$this->mailqueue_model->addToQueue($email,$subject,$message);
				
				$this->session->set_flashdata('error2', 'Info: 	Password reset send to '.$email);
				log_message('error','REGISTER MAIL SENT: REGID:'.$regid.' EMAIL:'.$email);
				$this->load->view('forgotthankyou_view');

			}
			else{
				log_message('error','REGISTER: FORM VALIDATION FAILED');
				$this->load->view('register_view');
			}
		}
		else{
			log_message('error','REGISTER: INPUT PARAMETER WRONG OR BOT ACCESS, TIME LESS THAN 5 seconds');

			$this->load->view('register_view');
		}
	}

	function userCheck($str){

		if($this->user_model->checkMail($str)!=NULL){
			$this->form_validation->set_message('userCheck', 'Email address already in use');
			return false;
		}
		else
		return true;


	}

	function authenticate()
	{

		//$this->load->model('user_model', '', true);

		$user = $this->input->post('user');

		$auth_response=$this->user_model->authenticate($user['email'], $user['password']);


			

		if ($auth_response!=NULL)
		{

			$this->createSession($auth_response);
			log_message('error','USER LOGGED IN: USERID:'.$auth_response->ID.' EMAIL:'.$auth_response->EMAIL);

			redirect('/');


		}
		else{

			log_message('error','INVALID LOGIN FOR USER: '.$user['email']);
			$this->session->set_flashdata('error', 'Error in login');
			redirect('session/login');
		}
	}

	private function createSession($auth_response){
		$this->user_model->updateLogin($auth_response->ID);
		if($this->agent->is_mobile()){
			$newdata = array(
                   'id'  => $auth_response->ID,
                   'nick'     => $auth_response->NICK,
                   'logged_in' => TRUE,
        			'is_mobile'=>TRUE
			);
		}
		else{
			$newdata = array(
                   'id'  => $auth_response->ID,
                   'nick'     => $auth_response->NICK,
                   'logged_in' => TRUE,
        			'is_mobile'=>FALSE
			);
		}


		$this->session->set_userdata($newdata);
		//$this->load->view('main_view');
	}

	function logout()
	{
		$this->session->unset_userdata('logged_in');
		$this->session->sess_destroy();
		//echo "hahaa";


		redirect('session/login');

	}
}
