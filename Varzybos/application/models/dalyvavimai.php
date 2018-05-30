<?php

class dalyvavimai extends CI_Model {

        public $V_ID;
        public $N_ID;
        //public $Issprende_u;
        //public $Taskai_isviso;
        
		public function get_dalyvavo_varzybose($v_id, $n_id)
        {
                $query = $this->db->query("SELECT * FROM dalyvavimai WHERE V_ID=$v_id AND N_ID=$n_id");
				return $query->result();
        }
		
		public function exist_registracija ($N_ID, $V_ID, $G_ID)//SUSIJE SU KITA LENTELE GRUPES_NARIAI
		{		
			
			IF(is_null($G_ID))
			{				
				//tikrinama, ar nera registracijos to naudotojo tom varzyboms
				$row = $this->db->query(
				"SELECT COUNT(*) AS Nr FROM dalyvavimai WHERE V_ID=$V_ID AND N_ID=$N_ID")->row();
				
				//tikrinama, ar nera uzregistruota komanda, kuriai naudotojas priklauso
				$row2 = $this->db->query(
				"select count(*) as Nr, did.G_ID  
					from (select grupes_nariai.G_id as G_id, grupes_nariai.N_id as N_id 
						  from grupes_nariai WHERE N_id=$N_ID) as grid 
						  LEFT JOIN 
						  (select dalyvavimai.G_id as G_id 
						  from dalyvavimai WHERE V_id=$V_ID) 
				as did on grid.G_id=did.G_id WHERE did.G_id IS NOT NULL")->row();
				//echo "<script>console.log(\"Nr = ".$row->Nr."\")</script>";
				if($row->Nr==0 && $row2->Nr==0){ return false;}
				else 
				{
					if($row2->Nr!=0)
					{
						$row2a = $this->db->query("select count(*) as Nr from dalyvavimai where G_id=$row2->G_id and N_ID=$N_ID AND V_ID=$V_ID")->row();
						if($row2a->Nr==0)
						{
							$this->db->query("CALL Registracija_varzyboms($N_ID, $V_ID,  $row2->G_id)");
						}
						
						
					}
					//echo "<script type='text/javascript'>alert('return true;');</script>"; 
					return true;
				}
			}
			else
			{
				$row3 = $this->db->query("SELECT count(*) as Nr FROM dalyvavimai where G_id=$G_ID")->row();
				$row4 = $this->db->query("select count(*) as Nr from (select grupes_nariai.G_id as G_id, grupes_nariai.N_id as N_id from grupes_nariai WHERE G_id=$G_ID) as grid LEFT JOIN (select dalyvavimai.N_id as N_id from dalyvavimai WHERE V_id=$V_ID) as did on grid.N_id=did.N_id WHERE did.N_ID is not null")->row();
				
				if($row3->Nr==0 && $row4->Nr==0)
				{ return false;}
				else return true;
			}
		}
		
		public function execute_registracija ($N_ID, $V_ID, $G_ID)
		{
			
			//$m=$this->exist_registracija($N_ID, $V_ID, $G_ID);
			//echo "<script type='text/javascript'>alert('m=$m');</script>";
			//if(!($this->exist_registracija($N_ID, $V_ID, $G_ID)))					
			//{
				
				//echo "<script type='text/javascript'>alert('true');</script>";
				if(is_null($G_ID))
				{
					$this->db->query("CALL Registracija_varzyboms($N_ID, $V_ID, null)");
				}
				else
				{
					$this->db->query("CALL Registracija_varzyboms($N_ID, $V_ID, $G_ID)");
				}				
			
				return true;
			//}
			//else 
			//{
			//	echo "<script type='text/javascript'>alert('false');</script>"; 
			//	return false;
			//}
		}
		
		public function panaikinti_registracija ($N_ID, $V_ID)
		{	
			$row = $this->db->query(
				"select count(*) as Nr, did.G_ID  
					from (select grupes_nariai.G_id as G_id, grupes_nariai.N_id as N_id 
						  from grupes_nariai WHERE N_id=$N_ID) as grid 
						  LEFT JOIN 
						  (select dalyvavimai.G_id as G_id 
						  from dalyvavimai WHERE V_id=$V_ID) 
				as did on grid.G_id=did.G_id WHERE did.G_id IS NOT NULL")->row();
			echo "<script>console.log(\"Nr = ".$row->Nr."\")</script>";
			if($row->Nr>0)
			{
				//Trinama grupės registracija
				$row2 = $this->db->query("DELETE FROM dalyvavimai WHERE dalyvavimai.G_id = $row->G_id AND `dalyvavimai`.`V_ID` = $V_ID");
			
			}
			else 
			{
				//echo "<script>console.log(\"NAUD\")</script>";
				//Trinama naudotojo registracija
				$row2 = $this->db->query("DELETE FROM dalyvavimai WHERE dalyvavimai.N_ID = $N_ID AND `dalyvavimai`.`V_ID` = $V_ID");
			}
				
		}
		
		public function dalyviu_sarasas ($v_id)//SIEJASI SU Naudotojais
		{
			$query = $this->db->query(
			"SELECT 
				(case WHEN G_id IS NULL THEN 
					(SELECT naudotojai.Naud_vardas FROM naudotojai where naudotojai.N_ID=dalyvavimai.N_ID) 
					else (SELECT n_grupes.G_pavadinimas FROM n_grupes where n_grupes.G_id=dalyvavimai.G_id) END) as Naud_vardas,
				G_id, N_ID, (case when G_id is null then (SELECT naudotojai.Reitingo_taskai FROM naudotojai where naudotojai.N_ID=dalyvavimai.N_ID)  else null end) as Reitingo_taskai
			 FROM dalyvavimai 
			 WHERE V_ID=$v_id  
			 GROUP BY (case WHEN G_id IS NULL THEN N_ID else G_id END)
			  ORDER BY Reitingo_taskai desc");
			return $query->result();
		}
		
		public function dalyviu_sarasas_spausdinimui ($v_id)//Siejasi su lentele naudotojais
		{
			$query = $this->db->query(
			"SELECT (case when G_id is null then sarasas.Naud_vardas else (select G_pavadinimas FROM n_grupes where n_grupes.G_id=sarasas.G_id) end) as Naud_vardas, Vardas, Pavarde, E_pastas, Statusas 
			 FROM (SELECT G_id, Naud_vardas, Vardas, Pavarde, E_pastas, Statusas 
					FROM dalyvavimai 
					RIGHT JOIN (SELECT N_ID, Naud_vardas, Vardas, Pavarde, E_pastas, Statusas FROM naudotojai) as naud 
					ON dalyvavimai.N_ID=naud.N_ID 
					where v_id=$v_id) as sarasas");
			return $query->result();
		}
		
		public function kaip_uzregistruotas($N_ID, $V_ID)
        {
			$row = $this->db->query(
				"SELECT COUNT(*) AS Nr FROM dalyvavimai WHERE V_ID=$V_ID AND N_ID=$N_ID AND dalyvavimai.G_ID IS NULL")->row();
			if($row->Nr==0)
			{
				//echo "<script>console.log(\"".$row->Nr."\")</script>";
				//tikrinama, ar nera uzregistruota komanda, kuriai naudotojas priklauso
				$row2 = $this->db->query(
				"select did.G_ID from (select grupes_nariai.G_id as G_id, grupes_nariai.N_id as N_id from grupes_nariai WHERE N_id=$N_ID) as grid LEFT JOIN (select dalyvavimai.G_id as G_id from dalyvavimai WHERE V_id=$V_ID) as did on grid.G_id=did.G_id WHERE did.G_id IS NOT NULL")->row()->G_id;
				return "grupės \"".$this->db->query(
			"SELECT G_pavadinimas FROM n_grupes WHERE G_ID='$row2'")->row()->G_pavadinimas."\" narys(ė)";
			}
			else 
			{
				return "naudotojas ".$this->session->userdata['logged_in']['Naud_vardas']." ";
			}
		}
		
		/*public function update_with_add($n_id, $v_id)
        {
            $statements = array ('N_ID' => $n_id, 'V_ID' => $v_id);
			$this->db->set('Issprende_u', 'Issprende_u+1', FALSE);
			$this->db->where($statements);
			$this->db->update('dalyvavimai');
        }*/
		
		public function kazkada_dalyvavo_komanda($G_id)
        {
			$ats=$this->db->query("SELECT count(*) as sk FROM dalyvavimai WHERE G_ID=$G_id")->row();
			if($ats->sk>0)
			{
				return true;
			}
			else return false;
        }
				
		public function supaprastinta_turnyrine_lentele($v_id)
        {			
			$query = $this->db->query(
			"select  ns.N_ID, ns.Naud_vardas, ns.G_id, ns.G_pavadinimas, SUM(ns.ispresta) as issprende, SUM(ns.taskai) as taskai 
			 from sprendimai_varzybose2 as ns
			 
			 WHERE ns.V_ID=$v_id 
			 GROUP BY (case when ns.G_id is null then ns.N_ID else ns.G_id end)
			 ORDER BY issprende desc, taskai asc");
			return array(
				'turnyrine_lentele' => $query->result(),
				'dalyviu_sk' => $query->num_rows()
						);
		}
			
		public function hard_turnyrine_lentele($v_id)
        {
			/*$kokie_uzdaviniai=$this->db->query("
			SELECT
			  GROUP_CONCAT(DISTINCT
				CONCAT(
					' coalesce((SELECT sum(bandymai) 
								from sprendimai_varzybose2 
								where u_id=',U_ID,' and (case when G_id is null 
								then n_id=ns.N_ID else G_id=ns.G_id end)), 0) as ''_',U_ID,''''
				)  
			  ) as kas
			FROM uzdaviniai
			WHERE V_ID=$v_id")->row()->kas;*/
			
			$kokie=$this->db->query("SELECT U_ID FROM uzdaviniai WHERE V_ID=$v_id")->result();
			$kokie_uzdaviniai = " ";
			foreach($kokie as $vienas_u)
			{
				$kokie_uzdaviniai=$kokie_uzdaviniai."coalesce((SELECT sum(bandymai) 
								from sprendimai_varzybose2 
								where u_id=".$vienas_u->U_ID." and (case when G_id is null 
								then n_id=ns.N_ID else G_id=ns.G_id end)), 0) as _".$vienas_u->U_ID.", ";
								
				//echo "<script>console.log(\"viens u  = ".$kokie_uzdaviniai."\")</script>";
			}
			
			//echo "<script>console.log(\"k_u = ".$kokie_uzdaviniai."\")</script>";
			$query = $this->db->query(
			"select  ns.N_ID, ns.Naud_vardas, ns.G_id, ns.G_pavadinimas, 
			 $kokie_uzdaviniai SUM(ns.ispresta) as issprende, SUM(ns.taskai) as taskai 
			 from sprendimai_varzybose2 as ns
			 
			 WHERE ns.V_ID=$v_id 
			 GROUP BY (case when ns.G_id is null then ns.N_ID else ns.G_id end)
			 ORDER BY issprende desc, taskai asc");
			return $query->result();
		}
		
		////
		/* View "sprendimai_varzybose"
		select naud.Naud_vardas,sprendimai.N_ID, sprendimai.U_ID, count(*) as bandymai, sum(Issprestas) as ispresta, SUM(sprendimai.taskai) as taskai 
from sprendimai RIGHT JOIN (select Naud_vardas, N_ID FROM naudotojai) as naud on sprendimai.N_ID=naud.N_ID 
where sprendimai.N_ID IS NOT NULL and sprendimai.taskai is NOT NULL
group by N_ID,sprendimai.U_ID
		*/
		////
		
		public function hard_turnyrine_lentelepvz($v_id)
        {
            /*
			select N_ID, sprendimai.U_ID, count(*) as bandymai, sum(Issprestas) as ispresta, SUM(TIMESTAMPDIFF(second,sprendimai.Pridavimo_laikas, CURRENT_TIMESTAMP())) as taskai from sprendimai RIGHT JOIN (select V_ID, U_ID FROM uzdaviniai where V_ID=34) as varuzd on sprendimai.U_ID=varuzd.U_ID where N_ID IS NOT NULL group by N_ID,sprendimai.U_ID
			*/
			/*2
			create view User_Items_Extended as ( select User_Items.N_ID, case when U_ID = 4 then bandymai end as '4', case when U_ID = 5 then bandymai end as '5', case when U_ID = 23 then bandymai end as '23', case when U_ID = 33 then bandymai end as '33' from (select N_ID, sprendimai.U_ID, count(*) as bandymai, sum(Issprestas) as ispresta, SUM(TIMESTAMPDIFF(second,sprendimai.Pridavimo_laikas, CURRENT_TIMESTAMP())) as taskai from sprendimai RIGHT JOIN (select V_ID, U_ID FROM uzdaviniai where V_ID=34) as varuzd on sprendimai.U_ID=varuzd.U_ID where N_ID IS NOT NULL group by N_ID,sprendimai.U_ID) AS User_Items )
			*/
			/*2+
			create view User_Items_Extended as 
( select User_Items.N_ID,
 case when U_ID = 4 then (case when bandymai is null then 0 else bandymai end) end as '4', 
 case when U_ID = 5 then bandymai end as '5', 
 case when U_ID = 23 then bandymai end as '23', 
 case when U_ID = 33 then bandymai end as '33' 
 from (select N_ID, sprendimai.U_ID, count(*) as bandymai, sum(Issprestas) as ispresta, SUM(TIMESTAMPDIFF(second,sprendimai.Pridavimo_laikas, CURRENT_TIMESTAMP())) as taskai from sprendimai RIGHT JOIN (select V_ID, U_ID FROM uzdaviniai where V_ID=34) as varuzd on sprendimai.U_ID=varuzd.U_ID where N_ID IS NOT NULL group by N_ID,sprendimai.U_ID) AS User_Items )
			*/
			/*
			select ns.Naud_vardas, ns.N_ID,
 coalesce((SELECT bandymai from sumuoti_sprendimai where u_id=4 and n_id=ns.N_ID), 0) as '4',
 coalesce((SELECT bandymai from sumuoti_sprendimai where u_id=5 and n_id=ns.N_ID), 0) as '5',
 coalesce((SELECT bandymai from sumuoti_sprendimai where u_id=23 and n_id=ns.N_ID), 0) as '23',
 coalesce((SELECT bandymai from sumuoti_sprendimai where u_id=33 and n_id=ns.N_ID), 0) as '33'
 from sumuoti_sprendimai as ns
 GROUP BY ns.N_ID
			*/
			
			/* rodinys (sukurtas yra be sum):
			select naud.Naud_vardas,sprendimai.N_ID, sprendimai.U_ID, count(*) as bandymai, sum(Issprestas) as ispresta, SUM(TIMESTAMPDIFF(second,sprendimai.Pridavimo_laikas, CURRENT_TIMESTAMP())) as taskai from sprendimai RIGHT JOIN (select Naud_vardas, N_ID FROM naudotojai) as naud on sprendimai.N_ID=naud.N_ID where sprendimai.N_ID IS NOT NULL group by N_ID,sprendimai.U_ID
			*/
			/*galutine uzklausa:
			select  ns.N_ID, ns.Naud_vardas,
 coalesce((SELECT bandymai from sum_sprendimai where u_id=4 and n_id=ns.N_ID), 0) as '4',
 coalesce((SELECT bandymai from sum_sprendimai where u_id=5 and n_id=ns.N_ID), 0) as '5',
 coalesce((SELECT bandymai from sum_sprendimai where u_id=23 and n_id=ns.N_ID), 0) as '23',
 coalesce((SELECT bandymai from sum_sprendimai where u_id=33 and n_id=ns.N_ID), 0) as '33'
 from sum_sprendimai as ns
 GROUP BY ns.N_ID
			*/
        }
}


?>