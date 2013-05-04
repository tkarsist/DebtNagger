<?php
class Mailqueue_model extends CI_Model
{
	
	function addToQueue($to,$subject,$message){
		$data=array(
		'RECIPIENT'=>$to,
		'SUBJECT'=>$subject,
		'MESSAGE'=>$message
		);
		$this->db->set('MAILQUEUE.CREATED', 'NOW()', FALSE);
		$this->db->insert('MAILQUEUE',$data);
		
		
	}
	function getMailQueue(){
		$query=$this->db->get('MAILQUEUE');
		return $query->result();
	}
	function delFromQueue($id){
		$this->db->where('MAILQUEUE.ID',$id);
		$this->db->delete('MAILQUEUE');
		
	}

}