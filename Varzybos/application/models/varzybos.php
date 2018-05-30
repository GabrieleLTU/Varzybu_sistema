<?php

class varzybos extends CI_Model {

        public $V_pavadinimas;
        public $V_ID;
        public $N_ID;
        public $V_pradzia;
        public $V_trukme;
        public $V_Keitimo_data;
        public $V_paruostos;

        public function get_all_varzybos()
        {
                $query = $this->db->get('varzybos');
                return $query->result();
        }
		
		public function get_one_by_id($v_id)
        {
                $query = $this->db->query("SELECT * FROM varzybos WHERE varzybos.V_ID=$v_id");
				return $query->result();
        }
		
		public function get_id_by_pavadinimas($V_pav)
        {
                $query = $this->db->query("SELECT V_ID FROM varzybos WHERE V_pavadinimas = '$V_pav'")->row();				
				if ($query) {
					return $query->V_ID; 
				}
        }
		public function get_pavadinima_by_id($V_ID)
        {
                $query = $this->db->query("SELECT V_pavadinimas FROM varzybos WHERE V_ID = '$V_ID'")->row();				
				if ($query) {
					return $query->V_pavadinimas; 
				}
        }
		
		public function get_ivykusias()
        {
                $query = $this->db->query("SELECT * FROM varzybos WHERE (varzybos.V_pradzia+varzybos.V_trukme) < CURRENT_TIMESTAMP() ORDER BY V_pradzia desc");
				return $query->result();
        }
		
		/*public function get_busimas()
        {
                $query = $this->db->query("SELECT * FROM varzybos WHERE varzybos.V_pradzia > CURRENT_TIMESTAMP()");
				return $query->result();
        }
		
		public function get_vykstancias()
        {
                $query = $this->db->query("SELECT * FROM varzybos 
				WHERE varzybos.V_pradzia < CURRENT_TIMESTAMP() AND (varzybos.V_pradzia+varzybos.V_trukme) > CURRENT_TIMESTAMP()");
				return $query->result();
        }*/
		
		public function get_busimas_su_naud()
        {
                $query = $this->db->query(
				"SELECT varzybos.V_ID, varzybos.V_pavadinimas, varzybos.V_pradzia, varzybos.V_trukme, 
				(DalyviuSk(varzybos.V_ID)) AS Naud
				FROM varzybos
                WHERE varzybos.V_pradzia > CURRENT_TIMESTAMP() AND varzybos.V_paruostos=TRUE ");
				return $query->result();
        }
		
		public function get_ivykusias_su_naud()
        {
                $query = $this->db->query(
				"SELECT varzybos.V_ID, varzybos.V_pavadinimas, varzybos.V_pradzia, varzybos.V_trukme, 
    			(DalyviuSk(varzybos.V_ID)) AS Naud
				FROM varzybos
                WHERE ADDTIME(varzybos.V_pradzia,varzybos.V_trukme) < CURRENT_TIMESTAMP()
					AND varzybos.V_paruostos=TRUE ORDER BY V_pradzia desc");
				return $query->result();
        }
		
		public function get_vykstancias_su_naud()
        {
                $query = $this->db->query(
				"SELECT varzybos.V_ID, varzybos.V_pavadinimas, varzybos.V_pradzia, varzybos.V_trukme, 
    			(DalyviuSk(varzybos.V_ID)) AS Naud
				FROM varzybos
                WHERE varzybos.V_pradzia < CURRENT_TIMESTAMP() AND ADDTIME(varzybos.V_pradzia,varzybos.V_trukme) > CURRENT_TIMESTAMP()
				AND varzybos.V_paruostos=TRUE");
				return $query->result();
        }
		
		public function get_liko_laiko_iki_pabaigos($V_ID)
        {
                $query = $this->db->query(
				"SELECT SEC_TO_TIME(TIMESTAMPDIFF(second,CURRENT_TIMESTAMP(),ADDTIME(V_pradzia, V_trukme))) AS liko
				FROM varzybos WHERE V_ID=$V_ID
				")->row();
				//return $query->result()->U_ID;
				
				if ($query) {
					return $query->liko; 
				}
        }
		
		public function get_neparuostas()
        {
                $query = $this->db->query(
				"SELECT * FROM varzybos WHERE varzybos.V_paruostos=false");
				return $query->result();
        }
		
		

        public function insert_varzybas()
        {
                
                $this->db->insert('varzybos', $this);
        }

        public function update_varzybas($v_id, $data)
        {
			$this->db->where('V_ID', $v_id);
			$this->db->update('varzybos', $data);
		}
		
		public function delete_varzybas($v_id)
        {
			if($this->db->query("SELECT count(*) as dalyvavo FROM dalyvavimai WHERE V_ID=$v_id")->row()->dalyvavo==0)
			{
			$query = $this->db->query(
			"DELETE FROM varzybos WHERE varzybos.V_ID = $v_id");
				return true;
			}
			else return false;
        }
		
		public function kurti_laiko_ivyki($v_id, $pradzia,$trukme)//2018-04-13 16:44:00.000000
        {  		
			$pavad="Reitingo_pridejimas_v_id".$v_id."_";
			$pradzia[10]=' ';
			$laikas=$this->db->query("select ADDTIME('$pradzia','$trukme') as laik")->row()->laik;
			$this->db->query("DROP EVENT IF EXISTS $pavad;");
			$this->db->query("
			
			CREATE EVENT `$pavad` ON SCHEDULE AT \"$laikas\"  
			DO BEGIN 
			SET @viso=0; 
			select count(*) INTO @viso 
			from (select ns.N_ID, ns.Naud_vardas, ns.G_id, ns.G_pavadinimas, SUM(ns.ispresta) as issprende, SUM(ns.taskai) as taskai 
					from sprendimai_varzybose2 as ns 
					WHERE ns.V_ID=$v_id 
					GROUP BY (case when ns.G_id is null then ns.N_ID else ns.G_id end) 
					ORDER BY issprende desc, taskai asc) as laiktable; 
			SET @rank=0; 
			CREATE TEMPORARY TABLE laikina AS select * , @rank:=@rank+1 AS vieta 
			from (select ns.N_ID, ns.Naud_vardas, ns.G_id, ns.G_pavadinimas, SUM(ns.ispresta) as issprende, SUM(ns.taskai) as taskai 
					from sprendimai_varzybose2 as ns 
					WHERE ns.V_ID=$v_id 
					GROUP BY (case when ns.G_id is null then ns.N_ID else ns.G_id end) 
					ORDER BY issprende desc, taskai asc) as tlentele; 
			select *, Reitingo_tasku_skyrimas2(N_ID,G_id, vieta, @viso) from laikina; END ");				
        } 
	
		public function get_varzybu_sar_teisems()
			{
				
				$query = $this->db->query(
				"SELECT V_pavadinimas as pavadinimas, V_ID as id FROM varzybos where varzybos.V_paruostos=false OR
				ADDTIME(varzybos.V_pradzia,varzybos.V_trukme) > CURRENT_TIMESTAMP()");
				return $query->result();
			}
			
			public function vykdyti_uzklausa($str)
        {
			$query = $this->db->query($str);
			return $query->result();
        }
}


?>