<?php

class n_grupes extends CI_Model {

        public $G_ID;
        public $G_pavadinimas;
        public $Kurimo_data;
		
		
		public function naudotojo_grupiu_sarasas($N_ID)//SIEJASI SU LENTELE grupes_nariai
		{
			$query = $this->db->query(
			"SELECT n_grupes.G_ID, G_pavadinimas FROM n_grupes left join grupes_nariai on n_grupes.G_id=grupes_nariai.G_id WHERE grupes_nariai.N_id=$N_ID");
			return $query->result();
		}
		
		public function visu_grupiu_sarasas()
		{
			$query = $this->db->query(
			"SELECT n_grupes.G_ID, G_pavadinimas FROM n_grupes");
			return $query->result();
		}
		
		public function kurti_grupe ()//gražina grupės id
		{
			$this->db->insert('n_grupes', $this);
			
			$row = $this->db->query(
			"SELECT G_ID FROM n_grupes WHERE G_pavadinimas='$this->G_pavadinimas'")->row();
			return $row->G_ID;
		}
		
		public function gauti_grupes_pavadinima($G_ID)
		{			
			$row = $this->db->query(
			"SELECT G_pavadinimas FROM n_grupes WHERE G_ID='$G_ID'")->row();
			return $row->G_pavadinimas;
		}
		
		public function keisti_grupes_pavadinima($G_id, $data)
        {
			$this->db->where('G_id', $G_id);
			$this->db->update('n_grupes', $data);
			
        }
								
		public function delete_grupe($G_ID) //su kitu modeliu DALYVAVIMAI
        {			
			$this->load->model('dalyvavimai');
			if( $this->dalyvavimai->kazkada_dalyvavo_komanda($G_ID))
			{
				return false;
			}
			else 
			{
				$this->load->model('grupes_nariai');
				$this->grupes_nariai->delete_visus_narius($G_ID);
				$this->db->query("DELETE FROM n_grupes WHERE G_ID = $G_ID");
				return true;
			}
        }
		
}
?>