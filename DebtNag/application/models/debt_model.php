<?php
class Debt_model extends CI_Model
{
	function addContact($userid,$nick,$email){


		$data=array(
		"NICK" => $nick, 
		"EMAIL" => $email, 
		"NAG"=>"1",
		"FK_USER_ID" => $userid
		);

		$this->db->insert('CONTACT', $data);
		$contactid=$this->db->insert_id();
		$this->updateNextNag($contactid);



	}

	function addDebt($userid,$contactid,$description,$sum){
		$data=array(
		'FK_USER_ID'=>$userid,
		'FK_CONTACT_ID'=>$contactid,
		'DESCRIPTION'=>$description,
		'SUM'=>$sum
		);
		//var_dump($data);
		$this->db->set('DEBT.DATE', 'NOW()', FALSE);
		$this->db->insert('DEBT',$data);
	}

	function addDebtClaim($userid,$contactid,$description,$sum){
		$data=array(
		'FK_USER_ID'=>$userid,
		'FK_CONTACT_ID'=>$contactid,
		'DESCRIPTION'=>$description,
		'SUM'=>$sum
		);
		//var_dump($data);
		$this->db->set('CLAIMED_DEBT.DATE', 'NOW()', FALSE);
		$this->db->insert('CLAIMED_DEBT',$data);
	}

	function updateLastNag($contactid){
		$date=date('Y-m-d H:i:s', now());
		//alle on kovakoodattu paiva
		$newdate=strtotime('+14 day',strtotime($date));
		$nextnag=date('Y-m-d H:i:s',$newdate);

		$data=array(
		'CONTACT.NEXTNAG'=>$nextnag
		);

		$this->db->where('CONTACT.ID',$contactid);
		$this->db->set('CONTACT.LASTNAG', 'NOW()', FALSE);

		$this->db->update('CONTACT',$data);
	}

	function updateNextNag($contactid){
		$date=date('Y-m-d H:i:s', now());
		//alle on kovakoodattu paiva
		$newdate=strtotime('+14 day',strtotime($date));
		$nextnag=date('Y-m-d H:i:s',$newdate);

		$data=array(
		'CONTACT.NEXTNAG'=>$nextnag
		);

		$this->db->where('CONTACT.ID',$contactid);


		$this->db->update('CONTACT',$data);
	}

	function getContactsToNag(){
		$this->db->where('CONTACT.NAG','1');
		$date=date('Y-m-d', now());
		//$this->db->where('CONTACT.NEXTNAG',$date);
		$this->db->like('CONTACT.NEXTNAG',$date);
		$this->db->select('CONTACT.ID,CONTACT.NICK,CONTACT.EMAIL, CONTACT.FK_USER_ID');
		$query=$this->db->get('CONTACT');
		return $query->result();
	}

	function delDebt($contactid,$debtid){
		$this->db->where('FK_CONTACT_ID',$contactid);
		$this->db->where('DEBT.ID',$debtid);
		$this->db->delete('DEBT');
	}

	function getUser($userid){
		$this->db->where('USER.ID',$userid);
		$this->db->select('USER.ID, USER.NICK,USER.EMAIL');
		$query=$this->db->get('USER');
		return $query->result();
	}



	function delContact($contactid){
		$this->db->where('CONTACT.ID',$contactid);
		$this->db->delete('CONTACT');
	}

	function changeNag($contactid,$nagval){
		if($nagval==1){
			$this->updateNextNag($contactid);
		}
		$this->db->where('CONTACT.ID',$contactid);
		$this->db->from('CONTACT');
		$data=array('NAG'=>$nagval);
		$this->db->update("CONTACT",$data);
	}

	function getDebtOverView($userid){

		$data['contacts']=$this->getContacts($userid);

		foreach ($data['contacts'] as $row){
			$debtdata[$row->ID]=$this->getContactDebtSum($row->ID);

		}
		if(!isset($debtdata))
		return false;
		$data['contactSum']=$debtdata;

		return $data;

	}

	function getContacts($userid){
		$this->db->select('CONTACT.ID,CONTACT.NICK,CONTACT.NAG,CONTACT.EMAIL,CONTACT.NEXTNAG,CONTACT.LASTNAG');
		$this->db->from('CONTACT');
		$this->db->where('FK_USER_ID',$userid);
		$query=$this->db->get();
		return $query->result();

	}

	function getContact($contactid){
		$this->db->select('CONTACT.ID,CONTACT.NICK,CONTACT.EMAIL,CONTACT.NAG,CONTACT.LASTNAG,CONTACT.NEXTNAG');
		$this->db->from('CONTACT');
		$this->db->where('CONTACT.ID',$contactid);
		$query=$this->db->get();
		return $query->result();

	}

	function checkContactValidity($contactid,$userid){
		$this->db->select('CONTACT.ID');
		$this->db->from('CONTACT');
		$this->db->where('FK_USER_ID',$userid);
		$this->db->where('CONTACT.ID',$contactid);



		if($this->db->count_all_results()==1)
		return true;
		return false;

	}

	function getContactDebtSum($contactid){

		$debts=$this->getDebtByContact($contactid);
		$sum=0;
		foreach ($debts as $row){

			$sum=$sum+$row->SUM;

		}



		return $sum;
	}

	function getDebtByContact($contactid){
		//hakee kaikki yhden hemmon laskut (vain summat jne)
		$this->db->where('FK_CONTACT_ID',$contactid);

		$query=$this->db->get('DEBT');

		return $query->result();
	}

	function getDebtDetails($contactid,$userid){
		$this->db->where('FK_CONTACT_ID',$contactid);
		$this->db->where('DEBT.FK_USER_ID',$userid);

		$query=$this->db->get('DEBT');

		return $query->result();
	}

	function getClaimedTasks($userid){
		$this->db->select('CLAIMED_DEBT.FK_USER_ID, CLAIMED_DEBT.SUM,CLAIMED_DEBT.ID,CLAIMED_DEBT.DATE,CLAIMED_DEBT.DESCRIPTION,CLAIMED_DEBT.FK_CONTACT_ID,CONTACT.NICK');
		$this->db->where('CLAIMED_DEBT.FK_USER_ID',$userid);
		$this->db->from('CLAIMED_DEBT');
		$this->db->join('CONTACT','CLAIMED_DEBT.FK_CONTACT_ID=CONTACT.ID');
		$query=$this->db->get();
		
		return $query->result();
		
	}
	function getClaimedTask($id,$userid,$contactid){
		$this->db->where('CLAIMED_DEBT.ID',$id);
		$this->db->where('CLAIMED_DEBT.FK_USER_ID',$userid);
		$this->db->where('CLAIMED_DEBT.FK_CONTACT_ID',$contactid);
		$query=$this->db->get('CLAIMED_DEBT');

		return $query->result();
	}
	
	
	function delClaimedTask($id,$userid,$contactid){
		$this->db->where('CLAIMED_DEBT.ID',$id);
		$this->db->where('CLAIMED_DEBT.FK_USER_ID',$userid);
		$this->db->where('CLAIMED_DEBT.FK_CONTACT_ID',$contactid);
		$this->db->delete('CLAIMED_DEBT');
		
	}

}