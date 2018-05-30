<?php

class naudotojai extends CI_Model {

        public $Naud_vardas;
		public $Vardas;
		public $Pavarde;
		public $E_pastas;
		public $Slaptazodis;
		public $Statusas;
		public $N_ID;
		public $Yra_administratorius;
		public $Klase;
		public $Grupe;
		public $Reitingo_taskai;

  
		
		public function varzybu_dalyviu_sarasas($V_ID)//SIEJASI SU DALYVAVIMAI LENTELE
		{
			$query = $this->db->query(
			"SELECT naudotojai.N_ID, Naud_vardas, Vardas, Pavarde, E_pastas, Statusas, Reitingo_taskai 
			 FROM naudotojai 
			 LEFT JOIN dalyvavimai 
			 ON naudotojai.N_ID=dalyvavimai.N_ID 
			 WHERE V_ID=$V_ID 
			 ORDER BY Reitingo_taskai desc");
			
			//"SELECT naudotojai.N_ID, Naud_vardas, Taskai_isviso, Reitingo_taskai FROM naudotojai LEFT JOIN dalyvavimai ON naudotojai.N_ID=dalyvavimai.N_ID WHERE V_ID=$V_ID");
			return $query->result();
		}
		
		public function uzdavini_issprendusiu_sarasas($U_ID)//SIEJASI SU DALYVAVIMAI LENTELE
		{
			$query = $this->db->query(
			"SELECT naudotojai.N_ID, Naud_vardas, Vardas, Pavarde, E_pastas, Statusas, Reitingo_taskai 
			 FROM naudotojai 
			 LEFT JOIN sprendimai 
			 ON naudotojai.N_ID=sprendimai.N_ID 
			 WHERE sprendimai.Issprestas=1 AND U_ID=$U_ID  
			 ORDER BY Pridavimo_laikas desc");
			
			//"SELECT naudotojai.N_ID, Naud_vardas, Taskai_isviso, Reitingo_taskai FROM naudotojai LEFT JOIN dalyvavimai ON naudotojai.N_ID=dalyvavimai.N_ID WHERE V_ID=$V_ID");
			return $query->result();
		}
		
		public function naudotoju_sarasas()
		{
			$query = $this->db->query(
			"SELECT N_ID, Naud_vardas, Reitingo_taskai  
			FROM naudotojai ORDER BY Reitingo_taskai desc");
			return $query->result();
		}
		
		public function naudotoju_sarasas_be_admin()
		{
			$query = $this->db->query(
			"SELECT N_ID, Naud_vardas, Reitingo_taskai  
			FROM naudotojai WHERE Yra_administratorius=0 ORDER BY Reitingo_taskai desc");
			return $query->result();
		}
		
		public function get_naudotojo_duomenis ($N_ID)
		{
			$query = $this->db->query(
			"SELECT * FROM naudotojai WHERE N_ID=$N_ID");
			return $query->result();			
		}
		
		public function get_naudotojo_duomenis_e_pastu ($ePastas)
		{
			$query = $this->db->query(
			"SELECT * FROM naudotojai WHERE E_pastas=$ePastas")->row();
			return $query->result()->row();			
		}
		
		public function insert_naudotojas()
        {
			$this->Slaptazodis=MD5($this->Slaptazodis);
			$this->db->insert('naudotojai', $this);
        }
		
		public function update_naudotojas($n_id, $data)
        {  		
			$this->db->where('N_ID', $n_id);
			$this->db->update('naudotojai', $data);			
        } 
		
		public function update_naudotojo_taskus($n_id, $taskai)
        {  		
			$this->db->query("
			UPDATE naudotojai 
			SET Reitingo_taskai = 
				(case when Reitingo_taskai is null 
					  then $taskai 
					  else (Reitingo_taskai + $taskai) end)
			WHERE N_ID=$n_id");				
        }
		
		/*public function kurti_laiko_ivyki($v_id, $data)//2018-04-13 16:44:00.000000
        {  		
			$pavad="Reitingo pridejimas v_id".$v_id;
			$this->db->query("
			DROP EVENT $pavad;
			
			delimiter | 

			CREATE EVENT `$pavad` ON SCHEDULE AT '$data' ON COMPLETION NOT PRESERVE ENABLE 
			DO BEGIN 
			SET @viso=0; 
			select count(*) INTO @viso 
			from (select ns.N_ID, ns.Naud_vardas, ns.G_id, ns.G_pavadinimas, SUM(ns.ispresta) as issprende, SUM(ns.taskai) as taskai 
				  from sprendimai_varzybose2 as ns 
				  WHERE ns.V_ID=$v_id 
				  GROUP BY (case when ns.G_id is null then ns.N_ID else ns.G_id end) 
				  ORDER BY issprende desc, taskai asc) as laiktable; 
			SET @rank=0; 
			CREATE TEMPORARY TABLE laikina 
				AS select * , @rank:=@rank+1 AS vieta 
				from (select ns.N_ID, ns.Naud_vardas, ns.G_id, ns.G_pavadinimas, SUM(ns.ispresta) as issprende, SUM(ns.taskai) as taskai 
						from sprendimai_varzybose2 as ns 
						WHERE ns.V_ID=$v_id 
						GROUP BY (case when ns.G_id is null then ns.N_ID else ns.G_id end) 
						ORDER BY issprende desc, taskai asc) as tlentele; 
						
				select *, Reitingo_tasku_skyrimas2(N_ID, G_id, vieta, @viso) from laikina; END | 

delimiter ;");				
        } */
		
		public function gauti_role($n_id)
        {  		
			$role = $this->db->query(
			"SELECT Yra_administratorius FROM naudotojai WHERE N_ID=$n_id")->row();
			return $role->Yra_administratorius;			
        }
		
		public function keisti_role($n_id, $data)
        {  		
			$this->db->where('N_ID', $n_id);
			$this->db->update('naudotojai', $data);				
        } 
		
		public function tikrinti_sl_ir_id_naudotojas($N_ID, $slapt)
        {
			//$this->load->database();
			//$condition = "(N_ID =" . "'" . $N_ID . "' AND " . "Slaptazodis =" . "'" . MD5($slapt) . "'";
			//$this->db->select('*');
			//$this->db->from('naudotojai');
			//$this->db->where($condition);
			//$this->db->limit(1);
			//$query = $this->db->get();
			
			$slapt=MD5($slapt);
			$query = $this->db->query("SELECT * FROM naudotojai WHERE N_ID = $N_ID AND Slaptazodis = '$slapt'");
			
			if ($query->num_rows() == 1) 
				{return true;} 
			else  {return false;}
        } 
		
		public function exist_naudotojas ($N_vardas)
		{			
			$row = $this->db->query(
			"SELECT COUNT(*) as Nr FROM naudotojai WHERE Naud_vardas='$N_vardas'")->row();
			if($row->Nr == 0)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		public function exist_naudotojas_ne_admin ($N_vardas)
		{			
			$row = $this->db->query(
			"SELECT COUNT(*) as Nr FROM naudotojai WHERE Naud_vardas='$N_vardas' and Yra_administratorius=0")->row();
			if($row->Nr == 0)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		public function gauti_naud_id ($N_vardas)//gražina naudotojo id
		{
			$row = $this->db->query(
			"SELECT N_ID FROM naudotojai WHERE Naud_vardas='$N_vardas'")->row();
			return $row->N_ID;
		}
		
		public function gauti_naud_ep_id ($N_vardas)//gražina naudotojo elpastą
		{
			$row = $this->db->query(
			"SELECT E_pastas, N_ID FROM naudotojai WHERE Naud_vardas='$N_vardas'")->row();
			//return $row->E_pastas;
			return array(
				'E_pastas' => $row->E_pastas,
				'N_ID' => $row->N_ID
				);
		}
		
		public function gauti_naud_varda ($N_ID)//gražina naudotojo varda
		{
			$row = $this->db->query(
			"SELECT Naud_vardas FROM naudotojai WHERE N_ID='$N_ID'")->row();
			return $row->Naud_vardas;
		}
		
		public function gauti_naud_statistika ($N_ID)
		{
			$row1 = $this->db->query(
			"SELECT count(*) as isviso, count(G_id) as kaip_grupe 
			 FROM dalyvavimai 
			 where N_id=$N_ID 
			 OR G_id in (SELECT GROUP_CONCAT(DISTINCT CONCAT('',G_id,'')) as kas
						FROM grupes_nariai
						WHERE N_id=$N_ID)")->row();
						
			$row2 = $this->db->query(
			"select count(*) as sprende_u, sum(case when Issprestas = 1 then 1 else 0 end) as teisingai_issprende, sum(pridave_kartu)  as isviso_sprendimu
			from (SELECT U_ID, MAX(Issprestas) as Issprestas, count(U_ID) as pridave_kartu 
					FROM sprendimai 
					where N_ID=$N_ID 
					GROUP BY U_ID) as sum_sprendimai")->row();
						
			return array(
				'dalyvavo_var' => $row1->isviso,
				'dalyvavo_var_kaip_grupe' => $row1->kaip_grupe,
				'sprende_u' => $row2->sprende_u,				
				'teisingai_issprende' => $row2->teisingai_issprende,
				'sprendimu_pridve' => $row2->isviso_sprendimu
						);
		}

}


?>