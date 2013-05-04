<?php
class Main extends MY_Controller{

	function __construct()
	{
		parent::__construct();
		$this->load->model('debt_model');
		$this->load->model('mailqueue_model');
		//$this->output->enable_profiler(TRUE);

	}
	function index(){

		$this->setHeaders();

		$data=$this->debt_model->getDebtOverview($this->session->userdata('id'));
		$data['tasks']=$this->debt_model->getClaimedTasks($this->session->userdata('id'));
		if(!isset($data['contacts']))
		$this->load->view('main_addcontact_view',$data);
		else
		$this->load->view('main_view',$data);
	}

	function setHeaders(){
		//$this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', $last_update).' GMT');
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0, max-age=0");
	}

	function processTask(){
		$this->setHeaders();
		if($this->input->post('accept')!=NULL&&$this->input->post('contact')!=NULL&&$this->input->post('id')!=NULL){
			$contact=$this->input->post('contact');
			$id=$this->input->post('id');
			if($this->debt_model->checkContactValidity($contact,$this->session->userdata('id'))){
				$claimed_debt=$this->debt_model->getClaimedTask($id,$this->session->userdata('id'),$contact);
				$this->debt_model->addDebt($this->session->userdata('id'),$contact,$claimed_debt[0]->DESCRIPTION,$claimed_debt[0]->SUM*(-1));
				$this->debt_model->delClaimedTask($id,$this->session->userdata('id'),$contact);
			}

		}
		if($this->input->post('decline')!=NULL&&$this->input->post('contact')!=NULL&&$this->input->post('id')!=NULL){
			$contact=$this->input->post('contact');
			$id=$this->input->post('id');
			if($this->debt_model->checkContactValidity($contact,$this->session->userdata('id'))){
				$this->debt_model->delClaimedTask($id,$this->session->userdata('id'),$contact);
			}
		}
		redirect('main');
	}

	function contactCheck($str){

		$this->load->model('debt_model');
		//echo $str;
		$contacts=$this->debt_model->getContacts($this->session->userdata('id'));

		foreach($contacts as $row){
			if($row->EMAIL==$str){
				$this->form_validation->set_message('contactCheck', 'Contact already exists');
				return FALSE;

			}
				
				
		}
		return TRUE;

	}
	

	function addContact(){
	$this->setHeaders();

		//if($this->input->post('initial')!='initial'){
			$this->form_validation->set_rules('nick', 'Name', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_contactCheck');
				

			if ($this->form_validation->run() == TRUE){
				$email=$this->input->post('email');
				$nick=$this->input->post('nick');
				$userid=$this->session->userdata('id');



				$this->load->model('debt_model');
				$this->debt_model->addContact($userid,$nick,$email);
				log_message('error','USER ADDED CONTACT, USERID: '.$userid);
				
			}
		//}
		$data=$this->debt_model->getDebtOverview($this->session->userdata('id'));
		$this->load->view('main_addcontact_view',$data);

	}

	function addDebt(){
		$this->setHeaders();
		$this->load->model('debt_model');

		$this->form_validation->set_rules('contact',"Don't hack",'required');
		$this->form_validation->set_rules('sum', 'Sum', 'required|numeric');
		$this->form_validation->set_rules('description');
		if ($this->form_validation->run() == TRUE){
			if($this->debt_model->checkContactValidity($this->input->post('contact'),$this->session->userdata('id'))){

				$this->debt_model->addDebt($this->session->userdata('id'),$this->input->post('contact'),$this->input->post('description'),$this->input->post('sum'));
			}

		}
		$contactid=$this->input->post('contact');
		$data['debtdetails']=$this->debt_model->getDebtDetails($contactid,$this->session->userdata('id'));
		$data['contact']=$this->debt_model->getContact($contactid);
		$data['debtsum']=$this->debt_model->getContactDebtSum($contactid);


		$this->load->view('detail_view',$data);

	}

	function delDebt(){
		$this->setHeaders();
		$this->load->model('debt_model');
		if($this->input->post('contact')!=NULL&&$this->input->post('debtid')!=NULL){
			if($this->debt_model->checkContactValidity($this->input->post('contact'),$this->session->userdata('id'))){
				$this->debt_model->delDebt($this->input->post('contact'),$this->input->post('debtid'));
			}
		}
		$data['debtdetails']=$this->debt_model->getDebtDetails($this->input->post('contact'),$this->session->userdata('id'));
		$data['contact']=$this->debt_model->getContact($this->input->post('contact'));
		$data['debtsum']=$this->debt_model->getContactDebtSum($this->input->post('contact'));
		$this->load->view('detail_view',$data);
	}

	function delContact(){
		$this->setHeaders();
		$this->load->model('debt_model');
		if($this->input->post('contact')!=NULL){

			if($this->debt_model->checkContactValidity($this->input->post('contact'),$this->session->userdata('id'))){

				$this->debt_model->delContact($this->input->post('contact'));
				log_message('error','USER DELETED CONTACT, USERID: '.$this->session->userdata('id').' CONTACTID: '.$this->input->post('contact'));
					
			}
		}
		redirect('main/addcontact');

	}

	function nag(){
		$this->setHeaders();
		if($this->input->post('contact')!=NULL&&($this->input->post('nagval')==1||$this->input->post('nagval')==0)){
			$this->load->model('debt_model');
			if($this->debt_model->checkContactValidity($this->input->post('contact'),$this->session->userdata('id'))){
				$this->debt_model->changeNag($this->input->post('contact'),$this->input->post('nagval'));
					
			}
		}
		redirect('main');
	}

	function nagmail(){
		$this->setHeaders();
		$this->load->model('debt_model');
		if($this->input->post('contact')!=NULL){

			if($this->debt_model->checkContactValidity($this->input->post('contact'),$this->session->userdata('id'))){
				$data['debtdetails']=$this->debt_model->getDebtDetails($this->input->post('contact'),$this->session->userdata('id'));
				$data['contact']=$this->debt_model->getContact($this->input->post('contact'));
				$data['debtsum']=$this->debt_model->getContactDebtSum($this->input->post('contact'));
				
				if(round($data['debtsum'],2)>0){
				
				$data['user']=$this->session->userdata('nick');
				$userid=$this->session->userdata('id');
				$contactid=$this->input->post('contact');
				$hash=hash_hmac('sha256',$userid.$contactid,HMAC_SKEY);
				
				$data['claimurl']=base_url("index.php/claimdebt/claim/".$userid."/".$contactid."/".$hash);
				
				$message=$this->load->view('mail_view',$data,TRUE);
				$email=$data['contact'][0]->EMAIL;
				$subject='Reminder of your debts to your friend '.$data['user'];
				
				$this->addToMailQueue($email, $subject, $message);
				
								
				$this->session->set_flashdata('error2', 'Info: 	Nag mail sent to '.$data['contact'][0]->EMAIL);
				$this->debt_model->updateLastNag($this->input->post('contact'));
				log_message('error','USER SENT NAGMAIL: USERID:'.$userid.' Mail was sent to:'.$data['contact'][0]->EMAIL);
				
				}
				if(round($data['debtsum'],2)<=0){
					$this->session->set_flashdata('error2', 'Info: 	No nag sent because there is no debts');
				}

			}

		}
		if($this->input->post('details')!=NULL){

			redirect('main/details/'.$this->input->post('contact'));
		}
		redirect('main');
	}

	/*
	function nagmailBatch($secret){
		if($secret==EMAIL_BATCH_KEY){
			$this->load->model('debt_model');
			echo "buu";
			$data['contact']=$this->debt_model->getContactsToNag();
			echo "haa";
			var_dump($data);
		}

	}
	*/


	function details($contactid){
		$this->setHeaders();
		$this->load->model('debt_model');
		if(!$this->debt_model->checkContactValidity($contactid,$this->session->userdata('id'))){
			$data=$this->debt_model->getDebtOverview($this->session->userdata('id'));
			$this->load->view('main_view',$data);
		}
		else{
			$data['debtdetails']=$this->debt_model->getDebtDetails($contactid,$this->session->userdata('id'));
			$data['contact']=$this->debt_model->getContact($contactid);
			$data['debtsum']=$this->debt_model->getContactDebtSum($contactid);
			$this->load->view('detail_view',$data);
		}
	}
	
	private function addToMailQueue($to,$subject,$message){
		$this->mailqueue_model->addToQueue($to,$subject,$message);

	}

}