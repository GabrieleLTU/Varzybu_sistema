<?php

Class Login_Database extends CI_Model {

// Insert registration data in database
/*public function registration_insert($data) {

// Query to check whether username already exist or not
$condition = "user_name =" . "'" . $data['user_name'] . "'";
$this->db->select('*');
$this->db->from('user_login');
$this->db->where($condition);
$this->db->limit(1);
$query = $this->db->get();
if ($query->num_rows() == 0) {

// Query to insert data in database
$this->db->insert('user_login', $data);
if ($this->db->affected_rows() > 0) {
return true;
}
} else {
return false;
}
}*/

// Read data using username and password
	public function login($data) {

	$this->load->database();
		$condition = "Naud_vardas =" . "'" . $data['Naud_vardas'] . "' AND " . "Slaptazodis =" . "'" . MD5($data['Slaptazodis']) . "'";
		//echo "<script>console.log( '".$condition."' );</script>";
		$this->db->select('*');
		$this->db->from('naudotojai');
		$this->db->where($condition);
		$this->db->limit(1);
		
		//$query = "SELECT * FROM naudotojai WHERE Naud_vardas='Gabriele' AND Slaptazodis='slaptazodis' LIMIT 1";
					
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) 
			{return true;} 
		else  {return false;}
	}
	
	public function login2($data) {

	$this->load->database();
		$condition = "E_pastas =" . "'" . $data['E_pastas'] . "' ";
		//echo "<script>console.log( '".$condition."' );</script>";
		$this->db->select('*');
		$this->db->from('naudotojai');
		$this->db->where($condition);
		$this->db->limit(1);
		
		//$query = "SELECT * FROM naudotojai WHERE Naud_vardas='Gabriele' AND Slaptazodis='slaptazodis' LIMIT 1";
					
		$query = $this->db->get();
		
		if ($query->num_rows() == 1) 
			{return true;} 
		else  {return false;}
	}

// Read data from database to show data in admin page
	public function read_user_information($username) {

	$condition = "Naud_vardas =" . "'" . $username . "'";
	$this->db->select('*');
	$this->db->from('naudotojai');
	$this->db->where($condition);
	$this->db->limit(1);
	$query = $this->db->get();

	if ($query->num_rows() == 1) {
	return $query->result();
	} else {
	return false;
	}
	}
	
	public function read_user_information2($epastas) {

	$condition = "E_pastas =" . "'" . $epastas . "'";
	$this->db->select('*');
	$this->db->from('naudotojai');
	$this->db->where($condition);
	$this->db->limit(1);
	$query = $this->db->get();

	if ($query->num_rows() == 1) {
	return $query->result();
	} else {
	return false;
	}
	}

}

?>