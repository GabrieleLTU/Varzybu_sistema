<?php

class sprendimai extends CI_Model {

        public $N_ID;
        public $U_ID;
        public $Issprestas;
        public $Pateiktas_atsakymas;
        public $Pridavimo_laikas;
        public $taskai;

        
		public function get_u_sprendimus($n_id, $u_id)
        {
                $query = $this->db->query("SELECT * FROM sprendimai WHERE U_ID=$u_id AND N_ID=$n_id ORDER BY Pridavimo_laikas DESC");
				return $query->result();
        }
		
		public function get_visus_u_sprendimus($u_id)
        {
                $query = $this->db->query(
					"SELECT naud.Naud_vardas, sp.N_ID, sp.Issprestas, sp.Pateiktas_atsakymas, sp.Pridavimo_laikas 
					 FROM (SELECT * FROM sprendimai WHERE U_ID=$u_id ORDER BY Pridavimo_laikas DESC) as sp
					 LEFT JOIN (select N_ID, Naud_vardas from naudotojai) as naud 
					 ON sp.N_ID = naud.N_ID
					 order by Pridavimo_laikas DESC");
				return $query->result();
        }
		
		public function insert_sprendimas()
        {                
                $this->db->insert('sprendimai', $this);
        }
		
		public function ar_issprende ($N_ID, $U_ID)
		{			
			$row = $this->db->query(
			"SELECT max(Issprestas) AS Nr FROM sprendimai WHERE U_ID=$U_ID AND N_ID=$N_ID")->row();
			if($row->Nr==0)
			{return false;}
			else return true;
		}
		
		public function perskaiciuoti_ar_issprende ($U_ID)
		{			
			$row = $this->db->query(
			"UPDATE sprendimai 
				SET `Issprestas` = (case when Pateiktas_atsakymas=( select Atsakymas from uzdaviniai where U_ID = $U_ID) then true else false end) where `U_ID`=$U_ID");
		}
		
		public function pakeisti_taskus ($N_ID, $U_ID)
		{			
			$row = $this->db->query(
			"UPDATE sprendimai SET `taskai`=20 where N_ID=$N_ID AND `U_ID`=$U_ID AND taskai=0");
		}
		
		public function perskaiciuoti_taskus ($U_ID,$V_ID)
		{			
		 $V_pr_laikas = /*"2018-05-11 00:20:00";*/
		 $this->db->query(
			"SELECT V_pradzia FROM varzybos WHERE V_ID=$V_ID")->row()->V_pradzia;
			//tikrinti ir nustatyti, ar teisingai issprende
			$row = $this->db->query(
			"UPDATE sprendimai 
				SET `Issprestas` = (case when Pateiktas_atsakymas=( select Atsakymas from uzdaviniai where U_ID = $U_ID) then true else false end) where `U_ID`=$U_ID");
			//skirti taskus: jei neteisingai - 0, jei teisingai - pagal pridavimo laika
			$row2 = $this->db->query(
			"UPDATE sprendimai 
				SET `taskai`= (case when Issprestas=false then 0 else TIMESTAMPDIFF(MINUTE,'$V_pr_laikas',Pridavimo_laikas) end) 
				WHERE U_ID=$U_ID and taskai is not null");
			//skirti po 20 tasku, jei isprende teisingai
			//$row3 = $this->db->query(
			//"UPDATE sprendimai
			//	SET `taskai`=20 where `U_ID`=$U_ID AND taskai=0 ");//AND Pridavimo_laikas<(<teisingai priduoto uzdavinio pridavimo laika>)");
			
			//gauname sarasa sprendimu, kai priduotas atsakymas yra teisingas
			$row3 = $this->db->query(
			"SELECT * FROM SPRENDIMAI WHERE u_id = $U_ID and Issprestas = true");
			
			//kiekvienam saraso naudotojui uz pries tai priduota neteisinga uzdavinio atsakyma skiriama 20 tasku
			foreach($row3->result() as $participant)
			{
				$row4 = $this->db->query(
				"UPDATE sprendimai
					SET `taskai`=20 where `U_ID`=$U_ID AND N_id=$participant->N_ID AND Pridavimo_laikas<'$participant->Pridavimo_laikas' ");
			}
		}
		
		public function sprendimai_varzybose($v_id)
        {
			/*$kokie_uzdaviniai=$this->db->query("
			SELECT
			  GROUP_CONCAT(DISTINCT
				CONCAT(
					' coalesce((SELECT max(ispresta) from sprendimai_varzybose2 where u_id=',U_ID,' and (case when G_id is null then n_id=ns.N_ID else G_id=ns.G_id end)), 0) as ''_',U_ID,''''
				) 
			  ) as kas
			FROM uzdaviniai
			WHERE V_ID=$v_id")->row()->kas;*/
			$kokie=$this->db->query("SELECT U_ID FROM uzdaviniai WHERE V_ID=$v_id")->result();
			$kokie_uzdaviniai = " ";
			foreach($kokie as $vienas_u)
			{
				$kokie_uzdaviniai=$kokie_uzdaviniai."coalesce((SELECT max(ispresta) from sprendimai_varzybose2 where u_id=$vienas_u->U_ID and (case when G_id is null then n_id=ns.N_ID else G_id=ns.G_id end)), 0) as '_$vienas_u->U_ID', ";
			}
			
			$query = $this->db->query(
			"select  ns.N_ID, ns.Naud_vardas, ns.G_id, ns.G_pavadinimas, 
			 $kokie_uzdaviniai SUM(ns.ispresta) as issprende, SUM(ns.taskai) as taskai 
			 from sprendimai_varzybose2 as ns
			 
			 WHERE ns.V_ID=$v_id 
			 GROUP BY (case when ns.G_id is null then ns.N_ID else ns.G_id end)
			 ORDER BY issprende desc, taskai asc");
			return $query->result();
		}
		
		public function get_last_sprendima($V_ID)
		{
			$kokie=$this->db->query("SELECT U_ID FROM uzdaviniai WHERE V_ID=$V_ID")->result();
			$kokie_uzdaviniai = " ";
			foreach($kokie as $vienas_u)
			{
				$kokie_uzdaviniai=$kokie_uzdaviniai.$vienas_u->U_ID.", ";
			}
			
			//$message = "veik ir ".strlen($kokie_uzdaviniai)."; ".$kokie_uzdaviniai;
			//echo "<script type='text/javascript'>alert('$message');</script>";
			
			//print_r(sizeof($kokie_uzdaviniai));
			if(strlen($kokie_uzdaviniai)>1){
			$kokie_uzdaviniai=" and U_ID IN (".substr($kokie_uzdaviniai, 0, -2).") ";
			return 
			$this->db->query
			("SELECT * FROM `sprendimai` 
			  WHERE taskai is not null $kokie_uzdaviniai 
			  ORDER BY Pridavimo_laikas desc 
			  LIMIT 1")->row();
			}
			else return null;
		}
}

/*
select `naud`.`Naud_vardas` AS `Naud_vardas`,`db_varzybos`.`sprendimai`.`N_ID` AS `N_ID`,`db_varzybos`.`sprendimai`.`U_ID` AS `U_ID`,count(0) AS `bandymai`,sum(`db_varzybos`.`sprendimai`.`Issprestas`) AS `ispresta`,sum(`db_varzybos`.`sprendimai`.`taskai`) AS `taskai` from ((select `db_varzybos`.`naudotojai`.`Naud_vardas` AS `Naud_vardas`,`db_varzybos`.`naudotojai`.`N_ID` AS `N_ID` from `db_varzybos`.`naudotojai`) `naud` left join `db_varzybos`.`sprendimai` on((`db_varzybos`.`sprendimai`.`N_ID` = `naud`.`N_ID`))) where ((`db_varzybos`.`sprendimai`.`N_ID` is not null) and (`db_varzybos`.`sprendimai`.`taskai` is not null)) group by `db_varzybos`.`sprendimai`.`N_ID`,`db_varzybos`.`sprendimai`.`U_ID`

*/
?>