<?php

class zinutes extends CI_Model {

        public $Siuntejo_id;
        public $Gavejo_id;
        public $Kada;
        public $Ar_perskaityta;
        public $Zinute;

        
		/*public function susirasineja_su($N_ID)
		{
			$query = $this->db->query(
			"select nvard.Naud_vardas, nvard.N_ID 
			from 
				(select distinct (CASE WHEN `Siuntejo_id`=$N_ID THEN `Gavejo_id` WHEN `Gavejo_id`=$N_ID THEN `Siuntejo_id` end) as naudotojas FROM `zinutes` where `Siuntejo_id`=$N_ID || `Gavejo_id`=$N_ID) as naud 
			left join 
			    (select naudotojai.Naud_vardas, naudotojai.N_ID from naudotojai) as nvard 
			ON naud.naudotojas=nvard.N_ID");
				return $query->result();
		}*/
		
		public function adm_susirasineja_su($N_ID)
		{
			/*$query = $this->db->query(
			"select nvard.Naud_vardas, nvard.N_ID 
			from 
				(select distinct (CASE WHEN `Siuntejo_id`=$N_ID THEN `Gavejo_id` WHEN `Gavejo_id`=$N_ID THEN `Siuntejo_id` end) as naudotojas FROM `zinutes` where `Siuntejo_id`=$N_ID || `Gavejo_id`=$N_ID || `Siuntejo_id`=0 || `Gavejo_id`=0) as naud 
			left join 
			    (select naudotojai.Naud_vardas, naudotojai.N_ID from naudotojai) as nvard 
			ON naud.naudotojas=nvard.N_ID");*/
			$query = $this->db->query(
			"select nvard.Naud_vardas, nvard.N_ID, naud.neperskaityta 
			from (select distinct (CASE WHEN `Siuntejo_id`=$N_ID OR `Siuntejo_id`=0 THEN `Gavejo_id` WHEN `Gavejo_id`=$N_ID OR `Gavejo_id`=0 THEN `Siuntejo_id` end) as naudotojas, sum((case when Ar_perskaityta=0 and Siuntejo_id !=0 then 1 else 0 end)) as neperskaityta
												   FROM `zinutes` 
												   where `Siuntejo_id`=$N_ID || `Gavejo_id`=$N_ID || `Siuntejo_id`=0 || `Gavejo_id`=0 
												   GROUP BY naudotojas) as naud 
			left join (select naudotojai.Naud_vardas, naudotojai.N_ID from naudotojai) as nvard 
			ON naud.naudotojas=nvard.N_ID");
				return $query->result();
		}
		
		public function adm_get_susirasinejima($a_id, $b_id)//naudid su kuo
		{
			$query = $this->db->query(
			"select * FROM zinutes WHERE ((Siuntejo_id=$a_id or Siuntejo_id=0) AND Gavejo_id=$b_id) || (Siuntejo_id=$b_id AND (Gavejo_id=$a_id or Gavejo_id=0)) ORDER BY Kada asc");
				return $query->result();
		}
		
		public function get_susirasinejima($a_id, $b_id)
		{
			$query = $this->db->query(
			"select * FROM zinutes WHERE (Siuntejo_id=$a_id AND Gavejo_id=$b_id) || (Siuntejo_id=$b_id AND Gavejo_id=$a_id) ORDER BY Kada asc");
				return $query->result();
		}
		
		public function kurti_zinute()
        {
            $this->db->insert('zinutes', $this);
        }
		
		public function yra_neperziuretu($yra_admin,$nid)
        {
				if($yra_admin)
				{
					$row = $this->db->query(
					"SELECT Ar_perskaityta FROM zinutes WHERE Gavejo_id in ($nid, 0) and Ar_perskaityta = 0 LIMIT 1")->row();
				}
				else
				{
					$row = $this->db->query(
					"SELECT Ar_perskaityta FROM zinutes WHERE Gavejo_id = $nid and Ar_perskaityta = 0 LIMIT 1")->row();
				
				}					
				if(isset($row->Ar_perskaityta) ){return true;}
				else return false;
        }
		
		/*public function yra_rases_kadanors($nid)
        {
				$row = $this->db->query(
				"SELECT COUNT(*) AS Nr FROM zinutes WHERE Siuntejo_id=$nid")->row();
								
				if($row->Nr==0){return false;}
				else return true;
        }*/
		
		public function adm_nustatyti_kad_perskaite($gavejas, $siuntejas)
        {
			//$statements = array ('Gavejo_id' $gavejas, ' OR Gavejo_id=0', 'Siuntejo_id' => $siuntejas);
			$this->db->set('Ar_perskaityta', true);
			$id = array($gavejas, 0);
			$this->db->where('Siuntejo_id', $siuntejas);
			$this->db->where_in('Gavejo_id', $id);			
			$this->db->update('zinutes');
		}
		
		public function nustatyti_kad_perskaite($gavejas)
        {
			$this->db->set('Ar_perskaityta', true);
			$this->db->where_in('Gavejo_id', $gavejas);			
			$this->db->update('zinutes');
		}
}


?>