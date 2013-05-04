<?php

class MailBatch extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('debt_model');
		$this->load->model('mailqueue_model');
		$this->load->library('email');


	}

	private function sendMail($email,$subject,$message){


		$this->email->from('debtnagger@gmail.com','Debt Nagger');
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();




	}

	function processMailQueue($secret){
		if($secret==EMAIL_BATCH_KEY){

			$time = microtime();
			$time = explode(' ', $time);
			$time = $time[1] + $time[0];
			$start = $time;
				
			$date=date('Y-m-d H:i:s', now());
			#echo $date." Mail Queue batch started";
			#echo "\n";

			$queue=$this->mailqueue_model->getMailQueue();
			$counter=0;

			foreach ($queue as $row){
				$email=$row->RECIPIENT;
				$subject=$row->SUBJECT;
				$message=$row->MESSAGE;
				$id=$row->ID;

				$this->sendMail($email, $subject, $message);
				$this->mailqueue_model->delFromQueue($id);
				$counter=$counter+1;
			}

			$time = microtime();
			$time = explode(' ', $time);
			$time = $time[1] + $time[0];
			$finish = $time;
			$executetime = round(($finish - $start), 4);

			if($counter!=0){
			echo $date." Mail queue batch started. ".$counter." mails was sent on the bachrun. Execution time: ".$executetime." seconds";
			echo "\n";
			}
		}
	}

	function nagmailBatch($secret){
		if($secret==EMAIL_BATCH_KEY){

			$time = microtime();
			$time = explode(' ', $time);
			$time = $time[1] + $time[0];
			$start = $time;

			$data['contact']=$this->debt_model->getContactsToNag();

			$date=date('Y-m-d H:i:s', now());
			echo $date." Mail batch started";
			echo "\n";
			$counter=0;
			foreach($data['contact'] as $row){
				
				$user=$this->debt_model->getUser($row->FK_USER_ID);
				$contactid=$row->ID;
				$userid=$user[0]->ID;
				$usernick=$user[0]->NICK;
				$usermail=$user[0]->EMAIL;
				$contactemail=$row->EMAIL;

				$data['debtdetails']=$this->debt_model->getDebtDetails($contactid,$userid);
				//taa on turha, toistoa
				$data['contact']=$this->debt_model->getContact($contactid);
				$data['debtsum']=$this->debt_model->getContactDebtSum($contactid);
				$data['user']=$usernick;

				if(round($data['debtsum'],2)>0){
					$counter=$counter+1;
					$hash=hash_hmac('sha256',$userid.$contactid,HMAC_SKEY);
					$data['claimurl']=MAIL_BASE_URL."index.php/claimdebt/claim/".$userid."/".$contactid."/".$hash;
					$subject=('Reminder of your debts to your friend '.$usernick);
					$message=$this->load->view('mail_view',$data,TRUE);

					$this->sendMail($contactemail, $subject, $message);

					//$this->session->set_flashdata('error2', 'Info: 	Nag mail sent to '.$data['contact'][0]->EMAIL);
					$this->debt_model->updateLastNag($contactid);

					echo'Info: 	Nag mail sent to '.$data['contact'][0]->EMAIL. " ---Details-- "."Userid: ".$userid." UserNick: ".$usernick." UserMail: ".$usermail." ContactID: ".$contactid." ContactMail: ".$contactemail;
					echo "\n";
				}
			}
			$time = microtime();
			$time = explode(' ', $time);
			$time = $time[1] + $time[0];
			$finish = $time;
			$executetime = round(($finish - $start), 4);

			if($counter==0)
			echo "No mails were sent. Execution time: ".$executetime." seconds";
			else
			echo $counter." mails was sent on the bachrun. Execution time: ".$executetime." seconds";
			echo "\n";
		}

	}
	function batchRunMailQueue($secret){
		
		if($secret!=EMAIL_BATCH_KEY)
		die();
		
		// config
		$lockfile = './cronjob.lock'; // lockfile name

		//how many minutes to just sit there before removing a stale lockfile and trying again
		//in case of failure on prior run
		$hardstart = 60;

		// end config
		//////////////////////////////////////////

		//is lockfile stale ?
		$seconds = $hardstart * 60;
		if (file_exists("$lockfile")){
			if (file_exists("$lockfile") && ((time() - filemtime("$lockfile")) > $seconds)) {
				if (unlink("$lockfile")){
					$del = $del + 1;
					echo "Deleted: $lockfile ";
				}
			} else {
				echo "\n".'last batch is still running. Exiting '."\n"; die();
			}
		} else {
			// create lockfile and start fetching
			$handle = fopen("$lockfile", "w");
			$stamp = date("YmdHis");
			fwrite($handle , $stamp);
			fclose($handle);

			// put the rest of your script here
			$this->processMailQueue($secret);
			//while(moveImagesFromQueueToSystem()){

			//}

			// remove the lockfile just before exiting ...
			unlink("$lockfile");
		}
		//  die() is preferable to exit()  on command line scripts,  while very similar
		// die() halts processing and immdediatly frees the resources, exit() actually
		// continues to be parsed, and only frees resources afterwards.
		die();


	}
}

?>
