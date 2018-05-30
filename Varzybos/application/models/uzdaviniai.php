<?php

class uzdaviniai extends CI_Model {

        public $U_pavadinimas;
        public $Salyga;
        public $Iliustracija;
        public $Atsakymas;
        public $U_ID;
        public $N_ID;
        public $V_ID;
        public $U_Keitimo_data;
        public $U_paruostas;

        		
				
		public function get_naudojamus()
        {
                $query = $this->db->query("SELECT * FROM uzdaviniai WHERE V_ID IS NOT NULL");
				return $query->result();
        }
		
		public function get_id_by_pavadinimas($U_pav)
        {
                $query = $this->db->query("SELECT U_ID FROM uzdaviniai WHERE U_pavadinimas = '$U_pav'")->row();
				//return $query->result()->U_ID;
				
				if ($query) {
					return $query->U_ID; 
				}
        }
		
		public function ar_naudojamas($u_id)
        {
			$query = $this->db->query("SELECT V_ID FROM uzdaviniai WHERE U_ID = $u_id");
			return $query->result();
			/*if( $r!=1)
			{return true;}
			else{ return false;}*/
		}
		
		public function get_kuriami()
        {
			$query = $this->db->query("SELECT * FROM uzdaviniai WHERE V_ID IS NULL");
			return $query->result();
        }
		
		public function get_one_by_id($u_id)
        {
			$query = $this->db->query("SELECT * FROM uzdaviniai WHERE U_ID=$u_id");
			return $query->result();
        }		
		
		public function priskirti_varzyboms($u_id, $v_id)
        {
			$statements = array ('U_ID' => $u_id, 'V_ID IS NULL');
			$this->db->set('V_ID', $v_id);
			$this->db->where($statements);
			$this->db->update('uzdaviniai');
		}		
		
		public function nebepriskirti_varzyboms($u_id, $v_id)
        {
			$statements = array ('U_ID' => $u_id, 'V_ID' => $v_id);
			$this->db->set('V_ID', NULL);
			$this->db->where($statements);
			$this->db->update('uzdaviniai');
		}
		
		public function nebepriskirti_visus_varzyboms($v_id)
        {
			$statements = array ('V_ID' => $v_id);
			$this->db->set('V_ID', NULL);
			$this->db->where($statements);
			$this->db->update('uzdaviniai');
		}
		
		public function get_dalyvavo_vid()
        {
                $query = $this->db->query(
				"SELECT uzdaviniai.U_ID,uzdaviniai.U_pavadinimas, 
						(SELECT COUNT(*) FROM sprendimai 
						WHERE sprendimai.Issprestas=true AND sprendimai.U_ID=uzdaviniai.U_ID) AS Issprende
				FROM uzdaviniai 
                WHERE uzdaviniai.V_ID IS NOT NULL AND (CASE WHEN (Select (varzybos.V_pradzia+varzybos.V_trukme) FROM varzybos WHERE varzybos.V_ID=uzdaviniai.V_ID) < CURRENT_TIMESTAMP() THEN TRUE ELSE FALSE END) IS TRUE
				GROUP BY uzdaviniai.U_ID");
				return $query->result();
        }
		/*
		SELECT uzdaviniai.U_ID,uzdaviniai.U_pavadinimas, (SELECT COUNT(*) FROM sprendimai 
  WHERE sprendimai.Issprestas=true AND sprendimai.U_ID=uzdaviniai.U_ID) AS Issprende
				FROM uzdaviniai 
				LEFT JOIN sprendimai ON uzdaviniai.U_ID=sprendimai.U_ID
                WHERE uzdaviniai.V_ID IS NOT NULL AND (CASE WHEN (Select (varzybos.V_pradzia+varzybos.V_trukme) FROM varzybos WHERE varzybos.V_ID=uzdaviniai.V_ID) < CURRENT_TIMESTAMP() THEN TRUE ELSE FALSE END) IS TRUE
				GROUP BY uzdaviniai.U_ID
		*/
		
		public function get_paruosti_varzyboms()
        {
                $query = $this->db->query("SELECT U_ID, U_pavadinimas FROM uzdaviniai WHERE V_ID IS NULL AND 	U_paruostas=1");
				return $query->result();
        }
		
		public function get_naudojami_vid_varzybose($v_id)
        {
                $query = $this->db->query("SELECT U_ID, U_pavadinimas FROM uzdaviniai WHERE V_ID = $v_id ORDER BY U_ID asc");
				return $query->result();
        }
		
		public function get_naudojami_varzybose_vid_nid($v_id, $n_id)
        {
                $query = $this->db->query("
				SELECT v_u.U_ID, U_pavadinimas, ispresta 
				FROM 
					(SELECT U_ID, U_pavadinimas FROM uzdaviniai  WHERE V_ID =$v_id ORDER BY U_ID asc) as v_u
				LEFT JOIN 
					(SELECT U_ID, ispresta from sprendimai_varzybose2 WHERE N_ID = $n_id) as d_s
				ON v_u.U_ID = d_s.U_ID
				ORDER BY U_ID asc");
				return $query->result();
        }

        public function insert_uzdavinys()
        {
                
                $this->db->insert('uzdaviniai', $this);
        }

        public function update_uzdavinys($u_id, $data)
        {
			$this->db->where('U_ID', $u_id);
			$this->db->update('uzdaviniai', $data);
			
        }
		
		public function delete_uzdavinys($u_id)
        {
			if(IS_NULL($this->db->query("SELECT V_ID FROM uzdaviniai WHERE U_ID=$u_id")->row()->V_ID))
			{
			$query = $this->db->query(
			"DELETE FROM uzdaviniai WHERE uzdaviniai.U_ID = $u_id");
				return true;
			}
			else return false;
        }
		
		public function get_uzdaviniu_sar_teisems()
        {
			$query = $this->db->query(
			"SELECT U_pavadinimas as pavadinimas, U_ID as id FROM uzdaviniai");
			return $query->result();
        }
		
		public function get_uzdavinius_pg_teises()
        {
			$query = $this->db->query(
			"SELECT U_pavadinimas as pavadinimas, U_ID as id FROM uzdaviniai");
			return $query->result();
        }
		
		public function vykdyti_uzklausa($str)
        {
			$query = $this->db->query($str);
			return $query->result();
        }

}


?>