<?php

class n_teises extends CI_Model {

        public $T_ID;
		public $Kas_suteike_id;
        public $Kam_suteike_id;
        public $Koks_objektas;
        public $Objekto_id;
        public $Leisti_kurti;
        public $Galioja_nuo;
		
		public function gauti_esamu_sarasa()
		{
			$query = $this->db->query(
			//"select T_ID, Kas_suteike_id, Kas_suteike_vardas, Kam_suteike_id, Kam_suteike_vardas, `Koks_objektas`, `Objekto_id`, `Leisti_kurti`, `Galioja_nuo` from (SELECT * FROM n_teises left join (select Naud_vardas as Kam_suteike_vardas, N_ID as Kam_id FROM naudotojai) as naud ON n_teises.Kam_suteike_id = naud.Kam_id) as pgr left join (select Naud_vardas as Kas_suteike_vardas, N_ID as Kas_id FROM naudotojai) as laik on laik.Kas_id= pgr.Kas_suteike_id");
			"select T_ID, Kas_suteike_id, Kas_suteike_vardas, Kam_suteike_id, Kam_suteike_vardas, `Koks_objektas`, `Objekto_id`, (case when Objekto_id is null or Objekto_id = 0 then null else (case when Koks_objektas = \"uždavinį\" then (select U_pavadinimas from uzdaviniai where U_ID = Objekto_id) else (select V_pavadinimas from varzybos where V_ID = Objekto_id) end) end)`Objekto_pavadinimas`, `Leisti_kurti`, `Galioja_nuo` 
			from (SELECT * 
				FROM n_teises 
				left join (select Naud_vardas as Kam_suteike_vardas, N_ID as Kam_id FROM naudotojai) as naud ON n_teises.Kam_suteike_id = naud.Kam_id) as pgr  
				left join (select Naud_vardas as Kas_suteike_vardas, N_ID as Kas_id FROM naudotojai) as laik on laik.Kas_id= pgr.Kas_suteike_id");
			return $query->result();
		}

		public function kurti_nauja()
        {
			$this->db->insert('n_teises', $this);
        }
		
		public function naikinti_teise($T_ID)
        {
			$query = $this->db->query(
			"DELETE FROM n_teises WHERE T_ID=$T_ID");
			
        }
		
		public function keisti_teise($T_ID, $data)
        {
			$this->db->where('T_ID', $T_ID);
			$this->db->update('n_teises', $data);
			
        }
		
		public function ar_turi_kokia_teise($n_id, $objektas)
		{
			$objektas = (substr ( $objektas , 0 , -3))."%";
			//echo "<script type='text/javascript'>alert('$objektas');</script>";
			$row = $this->db->query(
			"select count(*) as Nr from n_teises where Kam_suteike_id=$n_id and Koks_objektas like '$objektas' ")->row();
			
			return ($row->Nr > 0 ? true : false);
		}
		
		public function ar_gali_kurti_nauja($n_id, $objektas)//SIEJASI SU DALYVAVIMAI LENTELE
		{
			$objektas = (substr ( $objektas , 0 , -3))."%";
			$row = $this->db->query(
			"select max(Leisti_kurti) as sukurti from n_teises where Kam_suteike_id=$n_id and Koks_objektas like '$objektas' ")->row();
			
			return ($row->sukurti==1 ? true : false);
		}
		
		
		
		public function kokiems_objektams_teise($n_id, $objektas)
		{
			$objektas = (substr ( $objektas , 0 , -3))."%";
			$row = $this->db->query(
			"SELECT count(*) as Nr FROM `n_teises` where Kam_suteike_id=$n_id and Koks_objektas like '$objektas' and Objekto_id = 0 ")->row();
			if($row->Nr ==1 )
			{
				if($objektas[0]=='u')
				{
					return "SELECT U_ID, U_pavadinimas, U_paruostas FROM uzdaviniai";
				}
				else if($objektas[0]=='v')
				{
					return "SELECT * FROM varzybos WHERE varzybos.V_paruostos=false";
				}
			}
			else
			{
				if($objektas[0]=='u')
				{
					$row2 = $this->db->query ("SELECT
					  GROUP_CONCAT(' U_ID = ', a.Objekto_id ,' OR') as kas
					FROM (SELECT `Objekto_id`, `Kam_suteike_id` FROM n_teises WHERE `Kam_suteike_id`=$n_id AND `Koks_objektas` LIKE '$objektas') as a")->row();
					$str = str_replace(',', '', $row2->kas);
					$str=$str.' N_ID = '.$n_id;
					return "SELECT U_ID, U_pavadinimas, U_paruostas FROM uzdaviniai WHERE $str";
				}
				else if($objektas[0]=='v')
				{
					$row3 = $this->db->query ("SELECT
					  GROUP_CONCAT(' V_ID = ', a.Objekto_id ,' OR') as kas
					FROM (SELECT `Objekto_id`, `Kam_suteike_id` FROM n_teises WHERE `Kam_suteike_id`=$n_id AND `Koks_objektas` LIKE '$objektas') as a")->row();
					$str = str_replace(',', '', $row3->kas);
					$str=$str.' N_ID = '.$n_id;
					return "SELECT * FROM varzybos WHERE ($str) and varzybos.V_paruostos=false";
				}
				
			}
			
			return false;
		}
		
		public function teise_uzdaviniui($n_id, $u_id)
		{
			$row = $this->db->query(
			"select count(*) as Nr from n_teises where Kam_suteike_id=$n_id and Koks_objektas like 'uzdav%' and (Objekto_id = $u_id OR Objekto_id = 0) ")->row();
			$row2 = $this->db->query(
			"select count(*) as Nr from uzdaviniai where N_ID=$n_id and U_ID=$u_id")->row();
			
			return (($row->Nr+$row2->Nr)>0 ? true : false);
		}
		
		public function teise_varzyboms($n_id, $v_id)
		{
			$row = $this->db->query(
			"select count(*) as Nr from n_teises where Kam_suteike_id=$n_id and Koks_objektas like 'var%' and (Objekto_id = $v_id OR Objekto_id = 0) ")->row();
			$row2 = $this->db->query(
			"select count(*) as Nr from varzybos where N_ID=$n_id and V_ID=$v_id")->row();
			
			return (($row->Nr+$row2->Nr)>0 ? true : false);
		}
}
?>