<?php

class grupes_nariai extends CI_Model {

        public $G_ID;
        public $N_ID;
        public $Nuo_kada;

		public function priskirti_nari ()
		{
			$this->db->insert('grupes_nariai', $this);
		}
		
		public function grupes_nariu_skaicius ($G_ID)
		{
			 return $this->db->query("SELECT COUNT(*) as sk FROM grupes_nariai WHERE G_ID = '$G_ID'")->row()->sk;
		}
		
		public function visi_grupes_nariai($G_ID)
		{
                $query = $this->db->query(
				"SELECT naudotojai.Naud_vardas, naudotojai.N_ID FROM grupes_nariai LEFT JOIN naudotojai ON naudotojai.N_ID=grupes_nariai.N_ID
                WHERE grupes_nariai.G_ID=$G_ID");
				
				return $query->result();
		}
		
		public function exist_narys ()
		{			
			$row = $this->db->query(
			"SELECT COUNT(*) as Nr FROM grupes_nariai WHERE G_ID=$this->G_ID AND N_ID=$this->N_ID")->row();
			if($row->Nr == 0)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		public function delete_nari()
        {
			if(!IS_NULL($this->db->query("SELECT N_ID FROM grupes_nariai WHERE G_ID=$this->G_ID AND N_ID=$this->N_ID")->row()->N_ID) && ($this->db->query("SELECT count(*) as Nr FROM grupes_nariai WHERE G_ID=$this->G_ID")->row()->Nr)>1)
			{
			$query = $this->db->query(
			"DELETE FROM grupes_nariai WHERE G_ID=$this->G_ID AND N_ID=$this->N_ID");
				return true;
			}
			else return false;
        }
		
		public function delete_visus_narius($G_ID)
        {
			$query = $this->db->query(
			"DELETE FROM grupes_nariai WHERE G_ID=$G_ID");
        }
}
?>