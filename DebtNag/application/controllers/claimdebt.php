<?php

class Claimdebt extends CI_Controller
{
	//protected $secret="dsldslsaiii98287200лл)(#";
	protected $secret=HMAC_SKEY;

	function __construct()
	{
		parent::__construct();

		$this->load->model('debt_model');
		$this->load->model('mailqueue_model');
		
		$this->load->helper('url');
		//$this->output->enable_profiler(TRUE);

	}



	function paid(){
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0, max-age=0");
		$secret=$this->secret;


		if($this->input->post('user')!=null&&$this->input->post('contact')!=NULL&&$this->input->post('hash')!=NULL){
			$userid=$this->input->post('user');
			$contactid=$this->input->post('contact');
			$hash=$this->input->post('hash');

			if(hash_hmac('sha256',$userid.$contactid,$secret)==$hash){

				$this->form_validation->set_rules('sum', 'Sum', 'required|numeric');
				$this->form_validation->set_rules('description');
				if ($this->form_validation->run() == FALSE){
					$this->claim($userid,$contactid,$hash);
				}
				else{
					if($this->input->post('description')!=NULL)
					$description1=$this->input->post('description');
					else
					$description1="No message";
					$description="CONTACT MSG: ".$description1;
					$sum=$this->input->post('sum');
					$this->debt_model->addDebtClaim($userid,$contactid,$description,$sum);
					$user=$this->debt_model->getUser($userid);
					$contact=$this->debt_model->getContact($contactid);
					$subject=$contact[0]->NICK." sent you message via DebtNag";
					$data['nick']=$contact[0]->NICK;
					$data['description1']=$description1;
					$data['sum']=$sum;
					$message=$this->load->view('info_mail_view',$data,TRUE);
					$email=$user[0]->EMAIL;	
					//$this->sendInfoEvent($user[0]->EMAIL, $subject, $message);
					$this->addToMailQueue($email, $subject, $message);
					log_message('error','INFO MAIL (CONTACT CLAIM) SENT TO: '.$email);
					log_message('error','CLAIMED DEBT SUBMIT: SUBMIT SUCCESSFUL');
					$this->load->view('thankyou_view');
				}
			}
			else
			log_message('error','CLAIM DEBT SUBMIT FAILED. ERROR IN HASH, USERID: '.$userid.' CONTACTID: '.$contactid);
				
		}
		else
		log_message('error','CLAIMED DEBT SUBMIT: HACK IN INPUT PARAMS');


	}

	function claim($userid,$contactid,$hash){
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0, max-age=0");

		$secret=$this->secret;

		if(hash_hmac('sha256',$userid.$contactid,$secret)==$hash){
			$data['debtdetails']=$this->debt_model->getDebtDetails($contactid,$userid);
			$data['userid']=$userid;
			$data['hash']=$hash;

			$data['contact']=$this->debt_model->getContact($contactid);
			$data['debtsum']=$this->debt_model->getContactDebtSum($contactid);

			if($data['debtdetails']!=NULL)
			$this->load->view('claimdebt_view',$data);
			else
			redirect('main');
		}
		else
		log_message('error','CLAIM DEBT HASH CHECK FAILED USERID: '.$userid.' CONTACTID: '.$contactid);

	}


	private function addToMailQueue($to,$subject,$message){
		$this->mailqueue_model->addToQueue($to,$subject,$message);

	}

}