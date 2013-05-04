<?php

class User_model extends CI_Model
{
	public function authenticate($email, $password)
	{

		$hash=$this->calcHash($password);
			
		$user = $this->db->select('ID,NICK,EMAIL')->get_where('USER', array(
                'EMAIL' => $email,
            	'PWD' =>$hash
		))->row();



		return $user;

	}

	private function calcHash($password){
		$salt=PASSWORD_KEY;
		$hash=sha1($salt.$password);
		return $hash;
	}

	public function resetPwd($userid,$password){
		$this->db->where('USER.ID',$userid);
		$pwd=$this->calcHash($password);
		$data=array('PWD'=>$pwd);
		$this->db->update("USER",$data);
			
	}
	public function updateLogin($userid){
		$this->db->where('USER.ID',$userid);
		//echo $userid;
			
		$this->db->set('USER.LASTLOGIN', 'NOW()', FALSE);
		$this->db->update('USER');
	}

	public function checkMail($email){
		$this->db->where('USER.EMAIL',$email);
		$this->db->select('USER.ID, USER.LASTLOGIN, USER.NICK');
		$query=$this->db->get('USER');
		return $query->result();
	}
	public function getUser($userid){
		$this->db->where('USER.ID',$userid);
		$this->db->select('USER.ID, USER.NICK,USER.EMAIL,USER.LASTLOGIN');
		$query=$this->db->get('USER');
		return $query->result();
	}
	public function addRegUser($nick,$email,$pwd){
		$hashedpwd=$this->calcHash($pwd);
		$data=array(
    		"NICK"=>$nick,
    		"EMAIL"=>$email,
    		"PWD"=>$hashedpwd
		);
			
		$this->db->set('REGISTER.CREATED', 'NOW()', FALSE);
			
		$this->db->insert('REGISTER',$data);
		$id=$this->db->insert_id();
			
		return $id;
	}
	public function getRegUser($regid){
		$this->db->where('REGISTER.ID',$regid);
		$query=$this->db->get('REGISTER');
		return $query->result();
	}
	public function delRegUser($regid){
		$this->db->where('REGISTER.ID',$regid);
		$this->db->delete('REGISTER');
	}

	public function addUser($nick,$email,$pwd){
		$data=array(
		"NICK"=>$nick,
		"EMAIL"=>$email,
		"PWD"=>$pwd
		);
		$this->db->set('USER.CREATED', 'NOW()', FALSE);
		$this->db->insert('USER',$data);
		$id=$this->db->insert_id();
		return $id;

	}
}