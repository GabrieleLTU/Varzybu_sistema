<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registruoti_naudotojai extends CI_Controller {

	public function __construct()
	{	
		parent::__construct();
		//session_start();
		$this->load->library('session');
		if (isset($this->session->userdata['logged_in']))
		{
			date_default_timezone_set('Europe/Vilnius');
			$this->load->database();
			$this->load->helper('url');
			$this->load->model('zinutes');
			$data = array(
			'naud_vardas' => $this->session->userdata['logged_in']['Naud_vardas'],
			'yrazinute' => $this->zinutes->yra_neperziuretu($this->session->userdata['logged_in']['Yra_administratorius'], $this->session->userdata['logged_in']['Naudotojo_id']),
			'yra_admin' => $this->session->userdata['logged_in']['Yra_administratorius']
						);		
			$this->load->view('abiems/Head', $data);				
			//$this->load->view('registruotiems/Header', $data);
		}
		else
		{
			redirect(base_url().'index.php/Neregistruoti_naudotojai/pagrindinis', 'refresh');
		}
					
	}
	
	public function pagrindinis()
	{		
		$aktyvus_meniu_punktas=array('a'=>'pagrindinis');		
		$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);	
		$this->load->view('Content');
		$this->load->view('abiems/Footer');
	}
	
	/*---VARŽYBOS---*/
	public function varzybos()
	{
		
		$aktyvus_meniu_punktas=array('a'=>'varzybos');		
		$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);		
				
		//$this->load->database();
		$this->load->model('varzybos');
		$this->load->model('n_teises');
		
		$data['busimos'] = $this->varzybos->get_busimas_su_naud();		
		$data['vykstancios'] = $this->varzybos->get_vykstancias_su_naud();		
		$data['ivykusios'] = $this->varzybos->get_ivykusias_su_naud();
		
		if($this->session->userdata['logged_in']['Yra_administratorius'])
		{
			$data['neparuostos'] = $this->varzybos->get_neparuostas();
			$data['rodyti']= true;
			$data['kurti_nauja']= true;
		}
		else if($this->n_teises->ar_turi_kokia_teise($this->session->userdata['logged_in']['Naudotojo_id'],"varžybos")){
			
			$data['rodyti'] = true;
			$data['kurti_nauja'] = $this->n_teises->ar_gali_kurti_nauja($this->session->userdata['logged_in']['Naudotojo_id'],"varžybos");
			$gauta_uzklausa = $this->n_teises->kokiems_objektams_teise($this->session->userdata['logged_in']['Naudotojo_id'],"varžybos");
			//$data['kuriami'] = $this->varzybos->vykdyti_uzklausa($gauta_uzklausa);
			$data['neparuostos'] = $this->varzybos->vykdyti_uzklausa($gauta_uzklausa);
		}
		else
		{
			$data['rodyti']= false;
		}
		$this->load->view('registruotiems/varzybos', $data);		
		$this->load->view('abiems/Footer');
	}
	
	public function v_kurimas()
	{
		$this->load->library('form_validation');
				$this->load->database();
				$this->load->model('n_teises');
		if($this->session->userdata['logged_in']['Yra_administratorius']|| $this->n_teises->ar_gali_kurti_nauja($this->session->userdata['logged_in']['Naudotojo_id'],"varžybos"))
			{
				//$this->load->helper(array('form', 'url'));
				//$this->load->library('form_validation');
				//$this->load->database();
				$this->load->model('uzdaviniai');
				$this->load->model('varzybos');
				//--- taisyklės
				$config = array(
					array(
						'field' => 'pavadinimas',
						'label' => 'Varžybų pavadinimas',
						'rules' => 'required|trim|min_length[1]|max_length[50]|is_unique[varzybos.V_pavadinimas]'
						),
					array(
						'field' => 'tr_h',
						'label' => 'Varžybų pavadinimas',
						'rules' => 'trim|numeric'
						),
					array(
						'field' => 'tr_min',
						'label' => 'Varžybų pavadinimas',
						'rules' => 'trim|numeric'
						)
				);
				
				$this->form_validation->set_rules($config);

						if ($this->form_validation->run() == FALSE)
						{                        
							$aktyvus_meniu_punktas=array('a'=>'varzybos');
							$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
							//$this->load->view('abiems/Head');
							$data['vieta'] = 'Registruoti_naudotojai/varzybos';
							$data['pasirinkimui'] =$this->uzdaviniai->get_paruosti_varzyboms();
							$this->load->view('registruotiems/v_kurimas', $data);
							$this->load->view('abiems/Footer');
						}
						else
						{
							$this->load->database();
							$this->load->model('varzybos');
							$this->varzybos->V_pavadinimas=$this->input->post('pavadinimas');
							$this->varzybos->N_ID= $this->session->userdata['logged_in']['Naudotojo_id'];
							$this->varzybos->V_pradzia=$this->input->post('pr_data');
							$this->varzybos->V_trukme=$this->input->post('tr_h').":".$this->input->post('tr_min').":00";
							$this->varzybos->V_Keitimo_data=date('Y-m-d H:i:s');
							$this->varzybos->V_paruostos=false;
							
							$this->varzybos->insert_varzybas();
							//$this->v_keitimas('28');//$this->varzybos->get_id_by_pavadinimas($this->varzybos->V_pavadinimas));
							$id=$this->varzybos->get_id_by_pavadinimas($this->varzybos->V_pavadinimas);
							redirect(base_url().'index.php/Registruoti_naudotojai/v_keitimas/'.$id, 'refresh');
							
						}
			}
			else
			{;
			}
	}

	public function v_keitimas($V_ID)
	{
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->database();
		$this->load->model('n_teises');
		
		if($this->session->userdata['logged_in']['Yra_administratorius']|| $this->n_teises->teise_varzyboms($this->session->userdata['logged_in']['Naudotojo_id'],$V_ID))
		{
			$this->load->model('varzybos');
			$this->load->model('uzdaviniai');
			
			//--- taisyklės
			$config = array(
				array(
							'field' => 'pavadinimas',
							'label' => 'Varžybų pavadinimas',
							'rules' => 'required|trim|min_length[1]|max_length[50]'
							),
				array(
							'field' => 'tr_h',
							'label' => 'Varžybų trukmė (valandos)',
							'rules' => 'trim|numeric|less_than[800]|greater_than_equal_to[0]'
							),
				array(
							'field' => 'tr_min',
							'label' => 'Varžybų trukmė (minutės)',
							'rules' => 'trim|numeric|less_than[60]|greater_than_equal_to[0]'
							)
			);
			
			$this->form_validation->set_rules($config);

                if ($this->form_validation->run() == FALSE)
                {                        
					$aktyvus_meniu_punktas=array('a'=>'varzybos');
					$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
					$data['duomenys']=$this->varzybos->get_one_by_id($V_ID);
					$data['uzdaviniai']=$this->uzdaviniai->get_naudojami_vid_varzybose($V_ID);
					$data['pasirinkimui'] =$this->uzdaviniai->get_paruosti_varzyboms();
					$this->load->view('registruotiems/v_keitimas', $data);
					$this->load->view('abiems/Footer');
                }
                else
                {
					$data = array(
						'V_pavadinimas'=>$this->input->post('pavadinimas'),
						'N_ID'=> $this->session->userdata['logged_in']['Naudotojo_id'],
						'V_pradzia'=>$this->input->post('pr_data'),
						'V_trukme'=>$this->input->post('tr_h').":".$this->input->post('tr_min').":00",
						'V_Keitimo_data'=>date('Y-m-d H:i:s'),
						'V_paruostos'=>$this->input->post('ar_paruostas')
						);
						
					if($data['V_paruostos'])
					{						
						$liko_laiko=$this->varzybos->get_liko_laiko_iki_pabaigos($V_ID);
						
						
						if ($liko_laiko<"00:00:00")
						{ 		
							$new_time = date("Y-m-d H:i", strtotime('+30 minutes'));
							$config2 = array(
								array(
								'field' => 'pr_data',
								'label' => 'Varžybų pradžios data',
								'rules' => 'required|trim|greater_than_equal_to['.$new_time.']'
								)
							);
							$this->form_validation->set_rules($config2);
							$this->form_validation->run();
							
							$aktyvus_meniu_punktas=array('a'=>'varzybos');
							$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
							$data['duomenys']=$this->varzybos->get_one_by_id($V_ID);
							$data['uzdaviniai']=$this->uzdaviniai->get_naudojami_vid_varzybose($V_ID);
							$data['pasirinkimui'] =$this->uzdaviniai->get_paruosti_varzyboms();
							$this->load->view('registruotiems/v_keitimas', $data);
							$this->load->view('abiems/Footer');
						}
						else
						{
							$this->varzybos->update_varzybas($V_ID, $data);
							$this->varzybos->kurti_laiko_ivyki($V_ID, $data['V_pradzia'], $data['V_trukme']);
							$this->varzybos();
						}
					}
					else
					{
						$this->varzybos->update_varzybas($V_ID, $data);
						$this->varzybos();
					}
                }
		}
		else
		{
			//echo "<script type='text/javascript'>alert('Jūs neturite teisės redaguoti pasirinktų varžybų.');</script>";
			//$aktyvus_meniu_punktas=array('a'=>'varzybos',
			//							 'InfoModalMessage'=>'Jūs neturite teisės redaguoti pasirinktų varžybų.',
			//							 'goTo'=>'index.php/Registruoti_naudotojai/varzybos');
			//$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
			redirect(base_url().'index.php/Registruoti_naudotojai/varzybos', 'refresh');
		}
	}
		
	public function v_trinimas ($V_ID)
	{
		$this->load->database();
		$this->load->model('n_teises');
		if($this->session->userdata['logged_in']['Yra_administratorius']|| $this->n_teises->teise_varzyboms($this->session->userdata['logged_in']['Naudotojo_id'],$V_ID))
		{
			$this->load->model('varzybos');
			$this->load->model('uzdaviniai');
			
			if($this->varzybos->delete_varzybas($V_ID))
			{
				$this->uzdaviniai->nebepriskirti_visus_varzyboms($V_ID);
			$message = "Varžybos buvo sėkmingai ištrintos.";
			echo "<script type='text/javascript'>alert('$message');</script>";
			redirect(base_url().'index.php/Registruoti_naudotojai/varzybos', 'refresh');
			}
			else
			{
				$message = "Varžybos nebuvo ištrintos, nes yra jose dalyvaujančių(užsiregistravusių) naudotojų.";
			echo "<script type='text/javascript'>alert('$message');</script>";
			redirect(base_url().'index.php/Registruoti_naudotojai/v_keitimas/'.$V_ID, 'refresh');
			
		}
		}
		else
		{
			redirect(base_url().'index.php/Registruoti_naudotojai/varzybos', 'refresh');
		}
	}
	
	public function v_vykdymas($v_id)
	{
		//$this->load->database();
		$this->load->model('dalyvavimai');
		$this->load->model('varzybos');
		$data['liko_laiko']=$this->varzybos->get_liko_laiko_iki_pabaigos($v_id);
	IF($this->dalyvavimai->exist_registracija($this->session->userdata['logged_in']['Naudotojo_id'],$v_id,null) || $data['liko_laiko']<0)
		{
			
		$aktyvus_meniu_punktas=array('a'=>'varzybos');
		$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
		
		$this->load->model('n_teises');
		$data['rodyti']=$this->n_teises->teise_varzyboms($this->session->userdata['logged_in']['Naudotojo_id'],$v_id);
		
		$this->load->model('uzdaviniai');
		$this->load->model('sprendimai');		
		$data['varzybos']=$this->varzybos->get_one_by_id($v_id);	
		$data['uzdaviniai']=$this->uzdaviniai->get_naudojami_vid_varzybose($v_id);			
		//$data['t_lentele']=$this->dalyvavimai->simple_turnyrine_lentele($v_id);			
		$data['ht_lentele']=$this->dalyvavimai->hard_turnyrine_lentele($v_id);		
		$data['sprendimai_lentelei']=$this->sprendimai->sprendimai_varzybose($v_id);
		if($data['liko_laiko']>"00:00:00")
		{			
			$data['paskutinis_sprendimas']=$this->sprendimai->get_last_sprendima($v_id);			
			if(sizeof($data['paskutinis_sprendimas'])>0)
			{
				$i=1;
				foreach($data['uzdaviniai'] as $obj) 
				{  if ($data['paskutinis_sprendimas']->U_ID == $obj->U_ID) 
					{  $data['U_nr'] = $i; break; } 
					$i++;
				}
				foreach($data['ht_lentele'] as $obj) 
				{  if ($data['paskutinis_sprendimas']->N_ID == $obj->N_ID) 
					{  $data['N_vardas'] = is_null($obj->G_pavadinimas)?$obj->Naud_vardas : $obj->G_pavadinimas ; break; }
				}
			}
		}
		$this->load->view('registruotiems/v_vykdymas',$data);
		$this->load->view('abiems/Footer');
		}
		else 
		{
			redirect(base_url().'index.php/Registruoti_naudotojai/registruoti_varzyboms/'.$v_id, 'refresh');
		}
	}
	
	public function v_apzvalga($v_id)
	{
		$this->load->database();
		$this->load->model('varzybos');
		$data['liko_laiko']=$this->varzybos->get_liko_laiko_iki_pabaigos($v_id);
		//if($this->session->userdata['logged_in']['Yra_administratorius'])
		//	{
		
		$this->load->model('dalyvavimai');
		$aktyvus_meniu_punktas=array('a'=>'varzybos');
		$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);			
		$this->load->model('uzdaviniai');
		$this->load->model('sprendimai');
		$this->load->model('dalyvavimai');
		$this->load->model('n_teises');		
		$data['varzybos']=$this->varzybos->get_one_by_id($v_id);	
		$data['uzdaviniai']=$this->uzdaviniai->get_naudojami_vid_varzybose($v_id);
		//$data['t_lentele']=$this->dalyvavimai->simple_turnyrine_lentele($v_id);	
		$data['rodyti']=$this->n_teises->teise_varzyboms($this->session->userdata['logged_in']['Naudotojo_id'],$v_id);		
		$data['ht_lentele']=$this->dalyvavimai->hard_turnyrine_lentele($v_id);		
		$data['sprendimai_lentelei']=$this->sprendimai->sprendimai_varzybose($v_id);
						
		if($data['liko_laiko']>"00:00:00")
		{	
			$data['paskutinis_sprendimas']=$this->sprendimai->get_last_sprendima($v_id);			
			
			//$message = sizeof($data['paskutinis_sprendimas']);
			//echo "<script type='text/javascript'>alert('$message');</script>";
			
			if(sizeof($data['paskutinis_sprendimas'])>0)
			{
				$i=1;
				foreach($data['uzdaviniai'] as $obj) 
				{  if ($data['paskutinis_sprendimas']->U_ID == $obj->U_ID) 
					{  $data['U_nr'] = $i; break; } 
					$i++;
				}
				foreach($data['ht_lentele'] as $obj) 
				{  if ($data['paskutinis_sprendimas']->N_ID == $obj->N_ID) 
					{  $data['N_vardas'] = is_null($obj->G_pavadinimas)?$obj->Naud_vardas : $obj->G_pavadinimas ; break; }
				}
			}
		}
		
		
		$this->load->view('registruotiems/v_vykdymas',$data);
		$this->load->view('abiems/Footer');
		//	}
	}
	
	public function v_tur_lentele($v_id)
	{
		$aktyvus_meniu_punktas=array('a'=>'varzybos');
		$this->load->view('abiems/Head');
		$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
		$this->load->database();
		$this->load->model('sprendimai');
		$this->load->model('varzybos');
		$this->load->model('dalyvavimai');
		$this->load->model('uzdaviniai');
		//$data['dalyviai'] = $this->naudotojai->varzybu_dalyviu_sarasas($v_id);		
		$data['v_pavad'] = $this->varzybos->get_one_by_id($v_id);			
		$data['liko_laiko']=$this->varzybos->get_liko_laiko_iki_pabaigos($v_id);
		
		$data['uzdaviniai']=$this->uzdaviniai->get_naudojami_vid_varzybose($v_id);			
		$data['ht_lentele']=$this->dalyvavimai->hard_turnyrine_lentele($v_id);		
		$data['sprendimai_lentelei']=$this->sprendimai->sprendimai_varzybose($v_id);
		
		if($data['liko_laiko']>"00:00:00")
		{			
			$data['paskutinis_sprendimas']=$this->sprendimai->get_last_sprendima($v_id);			
			if(sizeof($data['paskutinis_sprendimas'])>0)
			{
				$i=1;
				foreach($data['uzdaviniai'] as $obj) 
				{  if ($data['paskutinis_sprendimas']->U_ID == $obj->U_ID) 
					{  $data['U_nr'] = $i; break; } 
					$i++;
				}
				foreach($data['ht_lentele'] as $obj) 
				{  if ($data['paskutinis_sprendimas']->N_ID == $obj->N_ID) 
					{  $data['N_vardas'] = is_null($obj->G_pavadinimas)?$obj->Naud_vardas : $obj->G_pavadinimas ; break; }
				}
			}
		}
			
		
		$this->load->view('registruotiems/turnyrine_lentele',$data);
		$this->load->view('abiems/Footer');
	}
	
	public function reitingo_tasku_skyrimas_po_varzybu($v_id)
	{
		$this->load->database();
		$this->load->model('dalyvavimai');
		$this->load->model('naudotojai');		
		
		
		$rezultatai = $this->dalyvavimai->supaprastinta_turnyrine_lentele($v_id);
		$vieta=1;
		foreach($rezultatai['turnyrine_lentele'] as $dalyvis)
		{
			$taskai = round((($rezultatai['dalyviu_sk']-$vieta)/SQRT($vieta)*(1-1/(6*$vieta))/2),2);
			$this->naudotojai->update_naudotojo_taskus($dalyvis->N_ID, $taskai);
			$vieta++;
		}
		//$dalyviu_sk = $rezultatai
	}
	
	public function registruoti_varzyboms($v_id)
	{
		//CALL Registracija_varzyboms(20, 1)
		$this->load->database();
		$this->load->model('dalyvavimai');
		
		if(!$this->dalyvavimai->exist_registracija($this->session->userdata['logged_in']['Naudotojo_id'],$v_id, null))//$N_ID, $V_ID, $G_ID
		{
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			$config = array(
				array(
					'field' => 'statusas',
					'label' => 'Dalyvavimo statusas',
					'rules' => 'required|trim|callback_notMatchMinus'
				)
				);
					
				$this->form_validation->set_rules($config);
				
				if ($this->form_validation->run() == FALSE)
				{                        
					$this->load->helper(array('form', 'url'));
					$this->load->library('form_validation');
					
					$this->load->model('varzybos');
					$this->load->model('n_grupes');
					
					$aktyvus_meniu_punktas=array('a'=>'varzybos');		
					$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);	
					$data['varzybos']=$this->varzybos->get_one_by_id($v_id);
					$data['n_vardas']=$this->session->userdata['logged_in']['Naud_vardas'];
					$data['n_grupes']=$this->n_grupes->naudotojo_grupiu_sarasas($this->session->userdata['logged_in']['Naudotojo_id']);
					$this->load->view('registruotiems/registracija_varzyboms', $data);		
					$this->load->view('abiems/Footer');	
				}
				else
				{
					if($this->input->post('statusas')=='Naudotojas')
					{
						 
						if($this->dalyvavimai->execute_registracija(
						$this->session->userdata['logged_in']['Naudotojo_id'], $v_id,null))
						{
						$message = "Jūs sėkmingai užregistruota(s) varžyboms.";
						echo "<script type='text/javascript'>alert('$message');</script>";
						}
						else
						{
							$message = "Jūs ar komanda, kuriai priklausote, jau yra užregistruota(s) varžyboms anksčiau.";
							echo "<script type='text/javascript'>alert('$message');</script>";
						}
			
					}
					else
					{
						//pasirinko dalyvauti kaip grupe
						
						if($this->dalyvavimai->execute_registracija(
						$this->session->userdata['logged_in']['Naudotojo_id'], $v_id,$this->input->post('statusas')))
						{
							$message = "Jūsų komanda sėkmingai užregistruota varžyboms.";
							echo "<script type='text/javascript'>alert('$message');</script>";
						}
						else
						{
							$message = "...Jūs ar komanda, kuriai priklausote, jau yra užregistruota(s) varžyboms anksčiau.";
							echo "<script type='text/javascript'>alert('$message');</script>";
						}
						
					}
					
					redirect(base_url().'index.php/Registruoti_naudotojai/varzybos/', 'refresh');
			}
		}
		else 
		{
			
			$aktyvus_meniu_punktas=array('a'=>'varzybos');		
			$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);		
				
			$this->load->database();
			$this->load->model('varzybos');
			$this->load->model('n_teises');
			
			$data['busimos'] = $this->varzybos->get_busimas_su_naud();		
			$data['vykstancios'] = $this->varzybos->get_vykstancias_su_naud();		
			$data['ivykusios'] = $this->varzybos->get_ivykusias_su_naud();
			$data['openModal']="Jūs varžyboms esate užregistruotas(a) <strong>kaip ".$this->dalyvavimai->kaip_uzregistruotas($this->session->userdata['logged_in']['Naudotojo_id'], $v_id)."</strong>.";
			
			if($this->n_teises->ar_turi_kokia_teise($this->session->userdata['logged_in']['Naudotojo_id'],"varžybos")){
				
				$data['rodyti'] = true;
				$data['kurti_nauja'] = $this->n_teises->ar_gali_kurti_nauja($this->session->userdata['logged_in']['Naudotojo_id'],"varžybos");
				$message = $this->n_teises->kokiems_objektams_teise($this->session->userdata['logged_in']['Naudotojo_id'],"varžybos");
				//$data['kuriami'] = $this->varzybos->vykdyti_uzklausa($message);
				$data['neparuostos'] = $this->varzybos->vykdyti_uzklausa($message);
			}
			else
			{
				$data['rodyti']= false;
			}	
			///
			
			/*if($this->session->userdata['logged_in']['Yra_administratorius'])
				{
					$data['neparuostos'] = $this->varzybos->get_neparuostas();
				}*/
			$this->load->view('registruotiems/varzybos', $data);		
			$this->load->view('abiems/Footer');
			
			//$message = "Jūs esate užregistruotas(a) ".$this->dalyvavimai->kaip_uzregistruotas($this->session->userdata['logged_in']['Naudotojo_id'], $v_id)." varžyboms anksčiau.";
			//echo "<script type='text/javascript'>alert('$message');</script>";
			//redirect(base_url().'index.php/Registruoti_naudotojai/varzybos/', 'refresh');
		}
	}
	
	public function panaikinti_registracija($v_id)
	{
		//CALL Registracija_varzyboms(20, 1)
		$this->load->database();
		$this->load->model('dalyvavimai');
		
		if($this->dalyvavimai->exist_registracija($this->session->userdata['logged_in']['Naudotojo_id'],$v_id, null))//$N_ID, $V_ID, $G_ID
		{
			$this->dalyvavimai->panaikinti_registracija($this->session->userdata['logged_in']['Naudotojo_id'],$v_id);
			$message = "Jūsų registracija panaikinta.";
			echo "<script type='text/javascript'>alert('$message');</script>";
			redirect(base_url().'index.php/Registruoti_naudotojai/varzybos', 'refresh');			
		}
		else 
		{			
			$message = "Klaida panaikinant registraciją. Bandykite dar kartą.";
			echo "<script type='text/javascript'>alert('$message');</script>";
			redirect(base_url().'index.php/Registruoti_naudotojai/varzybos', 'refresh');
		}
		
	}
	
	public function notMatchMinus ($str)
	{
    if ($str == '-')
    {
        $this->form_validation->set_message('notMatchMinus', 'Netinkamas dalyvavimo statusas: pasirinkite jį iš sąrašo.');
        return FALSE;
    }
    else
    {
        return TRUE;
    }
	}
	
	/*---UŽDAVINIAI---*/
	public function uzdaviniai()
	{
		$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
		
		$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
		$this->load->database();
		$this->load->model('uzdaviniai');
		$this->load->model('n_teises');
		$data['uzdaviniai'] = $this->uzdaviniai->get_dalyvavo_vid();
		
		
		if($this->session->userdata['logged_in']['Yra_administratorius'])
		{
			$data['kuriami'] = $this->uzdaviniai->get_kuriami();
			$data['rodyti']= true;
			$data['kurti_nauja']= true;
		}
		else if($this->n_teises->ar_turi_kokia_teise($this->session->userdata['logged_in']['Naudotojo_id'],"uzdavinys"))
		{
			
			$data['rodyti'] = true;
			$data['kurti_nauja'] = $this->n_teises->ar_gali_kurti_nauja($this->session->userdata['logged_in']['Naudotojo_id'],"uzdavinys");
			$message = $this->n_teises->kokiems_objektams_teise($this->session->userdata['logged_in']['Naudotojo_id'],"uzdavinys");
			$data['kuriami'] = $this->uzdaviniai->vykdyti_uzklausa($message);
		}
		else
		{
			$data['rodyti']= false;
		}	
		$this->load->view('registruotiems/uzdaviniai',$data);
		$this->load->view('abiems/Footer');
	}
	
	public function u_kurimas()
	{
		$this->load->database();
		$this->load->model('n_teises');
		if($this->session->userdata['logged_in']['Yra_administratorius'] || $this->n_teises->ar_gali_kurti_nauja($this->session->userdata['logged_in']['Naudotojo_id'],"uzdavinys"))
		{
			//$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->load->model('uzdaviniai');
			//--- taisyklės
			$config = array(
				array(
					'field' => 'pavadinimas',
					'label' => 'Uždavinio pavadinimas',
					'rules' => 'required|trim|min_length[1]|max_length[30]|is_unique[uzdaviniai.U_pavadinimas]'
					),
				array(
					'field' => 'salyga',
					'label' => 'Uždavinio sąlyga',
					'rules' => 'trim|required|max_length[700]'
					),
				array(
					'field' => 'atsakymas',
					'label' => 'Uždavinio atsakymas',
					'rules' => 'trim|required|max_length[100]'
					)
			);
			
			
			$this->form_validation->set_rules($config);

					if ($this->form_validation->run() == FALSE)
					{                        
						$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
						//$this->load->view('abiems/Head');
						$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
						$this->load->view('registruotiems/u_kurimas');
						$this->load->view('abiems/Footer');
					}
					else
					{
						$this->load->database();
						$this->load->model('uzdaviniai');
						$this->uzdaviniai->U_pavadinimas=$this->input->post('pavadinimas');
						$this->uzdaviniai->Salyga=$this->input->post('salyga');
						$this->uzdaviniai->Iliustracija=false;
						$this->uzdaviniai->Atsakymas=str_replace(".",",",str_replace(" ","",$this->input->post('atsakymas')));
						$this->uzdaviniai->N_ID=$this->session->userdata['logged_in']['Naudotojo_id'];
						$this->uzdaviniai->V_ID=null;
						$this->uzdaviniai->U_Keitimo_data= date('Y-m-d H:i:s');
						$this->uzdaviniai->U_paruostas=$this->input->post('ar_paruostas');
						
						////////////////////////
						if(@$_FILES['userfile']['error'] == '0')
						{
							
							//uzdavinio iliustracijos duomenys:
							$config_image = array();
							$config_image['upload_path'] = './images/uzdaviniu_iliustracijos/';
							$config_image['allowed_types'] = 'jpg|png|jpeg';
							$config_image['file_name'] = $this->uzdaviniai->U_ID;
							//$config_image['max_size'] = '1024';
							//$config_image['max_width'] = '1024';
							//$config_image['max_height'] = '768';
							$config_image['overwrite'] = TRUE;
							
							$this->load->library('upload',$config_image);		
						
							if(!$this->upload->do_upload())
							{
								$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
								//$this->load->view('abiems/Head');
								$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
								$this->load->view('registruotiems/u_kurimas');
								$this->load->view('abiems/Footer');
														
							}
							else{
								$this->uzdaviniai->Iliustracija= TRUE;
								$this->uzdaviniai->insert_uzdavinys();
								$res = $this->upload->data();

							$file_path     = $this->upload->data('file_path');
							$file         = $this->upload->data('full_path');
							$file_ext     = $this->upload->data('file_ext');
							
							$final_file_name = $this->uzdaviniai->get_id_by_pavadinimas($this->uzdaviniai->U_pavadinimas).$file_ext;
							rename($file, $file_path . $final_file_name);


								$this->uzdavinys($this->uzdaviniai->get_id_by_pavadinimas($this->uzdaviniai->U_pavadinimas));
							}
						}
						else
						{
							$this->uzdaviniai->insert_uzdavinys();
							$this->uzdavinys($this->uzdaviniai->get_id_by_pavadinimas($this->uzdaviniai->U_pavadinimas));
							echo '<script language="javascript">';
								echo 'alert("Naujas uždavinys išsaugotas.")';
								echo '</script>';
								
						}

					}
			
		}
		else
		{
			redirect(base_url().'index.php/Registruoti_naudotojai/uzdaviniai', 'refresh');
		}
	}
	
	public function u_keitimas($U_ID)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->database();
		$this->load->model('n_teises');
		if($this->session->userdata['logged_in']['Yra_administratorius'] || $this->n_teises->teise_uzdaviniui($this->session->userdata['logged_in']['Naudotojo_id'],$U_ID))
		{
		
		$this->load->model('uzdaviniai');
		
		//--- taisyklės
		$config = array(
			array(
				'field' => 'pavadinimas',
				'label' => 'Uždavinio pavadinimas',
				'rules' => 'required|trim|min_length[1]|max_length[30]'
				),
			array(
				'field' => 'salyga',
				'label' => 'Uždavinio sąlyga',
				'rules' => 'trim|required|max_length[700]'
				),
			array(
				'field' => 'atsakymas',
				'label' => 'Uždavinio atsakymas',
				'rules' => 'trim|required'
				)
		);
		
		$this->form_validation->set_rules($config);

                if ($this->form_validation->run() == FALSE)
                {                        
					$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
					$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
					$data['duomenys']=$this->uzdaviniai->get_one_by_id($U_ID);
					$this->load->view('registruotiems/u_keitimas', $data);
					$this->load->view('abiems/Footer');
                }
                else
                {
					$data['duomenys']=$this->uzdaviniai->get_one_by_id($U_ID);
					$V_ID=$data['duomenys'][0]->V_ID;
					$data = array(
						'U_pavadinimas'=>$this->input->post('pavadinimas'),
						'Salyga'=>$this->input->post('salyga'),
						'Iliustracija'=>FALSE,
						'Atsakymas'=>str_replace(".",",",str_replace(" ","",$this->input->post('atsakymas'))),
						'N_ID'=>$this->session->userdata['logged_in']['Naudotojo_id'],
						'U_Keitimo_data'=> date('Y-m-d H:i:s'),
						'U_paruostas'=>$this->input->post('ar_paruostas')
						);
						
						
					if(@$_FILES['userfile']['error'] == '0')
					{
						
						//uzdavinio iliustracijos duomenys:
						$config_image = array();
						$config_image['upload_path'] = './images/uzdaviniu_iliustracijos/';
						$config_image['allowed_types'] = 'jpg|png|jpeg|gif';
						$config_image['file_name'] = $U_ID;
						$config_image['overwrite'] = TRUE;
						//$config_image['max_size'] = '1024';
						//$config_image['max_width'] = '1024';
						//$config_image['max_height'] = '768';
						
						$this->load->library('upload',$config_image);		
					
						if(!$this->upload->do_upload())
						{
							$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
							$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
							//$data['duomenys']=$this->uzdaviniai->get_one_by_id($U_ID);
							$this->load->view('registruotiems/u_keitimas', $data);
							$this->load->view('abiems/Footer');
							
							echo'<pre>';
							print_r( $this->upload->display_errors());
							echo'</pre>';
							echo '<script language="javascript">';
							echo 'alert("Klaida įkeliant paveikslėlį.")';
							echo '</script>';							
						}
						else
						{
							$data['Iliustracija']= TRUE;
							$this->uzdaviniai->update_uzdavinys($U_ID, $data);
							if($data['U_paruostas'] && !is_null($V_ID)){
							$this->load->model('sprendimai');
							$this->sprendimai->perskaiciuoti_taskus ($U_ID, $V_ID);}
							
							redirect(base_url().'index.php/Registruoti_naudotojai/uzdavinys/'.$U_ID, 'refresh');
						}
					}
					else
					{						
						if (isset($_POST['yra_pav'])) 
						{
							$data['Iliustracija']= TRUE;
						}
						else 
						{
							$files = glob("./images/uzdaviniu_iliustracijos/$U_ID".'*');
							if(sizeof($files)>0)
							{
								foreach($files as $kk){$fullpath = $kk;}
								unlink($fullpath);
							}
						}
						
						$this->uzdaviniai->update_uzdavinys($U_ID, $data);
						if($data['U_paruostas'] && !is_null($V_ID)){
							$this->load->model('sprendimai');
							$this->sprendimai->perskaiciuoti_taskus ($U_ID, $V_ID);}
						
						echo '<script language="javascript">';
							echo 'alert("Uždavinio duomenys sėkmingai pakeisti.")';
							echo '</script>';
							
						redirect(base_url().'index.php/Registruoti_naudotojai/uzdavinys/'.$U_ID, 'refresh');
					}
					
					
                }
		
		}
		else
		{
			redirect(base_url().'index.php/Registruoti_naudotojai/uzdaviniai', 'refresh');
		}
	}
		
	public function u_trinimas ($U_ID)
	{
		$this->load->database();		
		$this->load->model('n_teises');
		if($this->session->userdata['logged_in']['Yra_administratorius'] || $this->n_teises->teise_uzdaviniui($this->session->userdata['logged_in']['Naudotojo_id'],$U_ID))
		{
			$this->load->model('uzdaviniai');
			$files = glob("./images/uzdaviniu_iliustracijos/$U_ID".'*');			
			if(sizeof($files)>0)
							{
								foreach($files as $kk){$fullpath = $kk;}
								unlink($fullpath);
							}
			$this->uzdaviniai->delete_uzdavinys($U_ID);
			$message = "Uždavinys buvo sėkmingai ištrintas.";
			echo "<script type='text/javascript'>alert('$message');</script>";
			redirect(base_url().'index.php/Registruoti_naudotojai/uzdaviniai', 'refresh');
		}
		else
		{
			redirect(base_url().'index.php/Registruoti_naudotojai/uzdaviniai', 'refresh');
		}
	}	
	
	public function u_priskyrimas_varzyboms ($v_id, $u_id)
	{
		$this->load->database();
		$this->load->model('n_teises');
		if($this->session->userdata['logged_in']['Yra_administratorius'] || $this->n_teises->ar_gali_kurti_nauja($this->session->userdata['logged_in']['Naudotojo_id'],"uzdavinys"))
		{
			$this->load->model('uzdaviniai');
			
			
			//echo "<script type='text/javascript'>alert('$this->uzdaviniai->ar_naudojamas($u_id)');</script>";
			// tikrinimas 
			//if($this->uzdaviniai->ar_naudojamas($u_id))
			//{
				
			//}
			//else{
				$this->uzdaviniai->priskirti_varzyboms($u_id, $v_id);//priskyrimas
			//}

			//$this->v_keitimas($v_id);
			redirect(base_url().'index.php/Registruoti_naudotojai/v_keitimas/'.$v_id, 'refresh');
		}
		else
		{
			redirect(base_url().'index.php/Registruoti_naudotojai/uzdaviniai', 'refresh');
		}
	}
	
	public function u_nebepriskyrimas_varzyboms ($v_id, $u_id)
	{
		$this->load->database();
		$this->load->model('n_teises');
		if($this->session->userdata['logged_in']['Yra_administratorius'] || $this->n_teises->ar_gali_kurti_nauja($this->session->userdata['logged_in']['Naudotojo_id'],"uzdavinys"))
		{
			$this->load->model('uzdaviniai');		
			//echo "<script type='text/javascript'>alert('$this->uzdaviniai->ar_naudojamas($u_id)');</script>";
			// tikrinimas 
			//if($this->uzdaviniai->ar_naudojamas($u_id))
			//{
				
			//}
			//else{
				$this->uzdaviniai->nebepriskirti_varzyboms($u_id, $v_id);//priskyrimas
			//}
		
			//$this->v_keitimas($v_id);
			redirect(base_url().'index.php/Registruoti_naudotojai/v_keitimas/'.$v_id, 'refresh');
		}
		else
		{
			redirect(base_url().'index.php/Registruoti_naudotojai/uzdaviniai', 'refresh');
		}
	}
		
	public function uzdavinys($U_ID)
	{
		
		//$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$dblaikas= date('Y-m-d H:i:s');
		$this->load->database();
		$this->load->model('uzdaviniai');
		$this->load->model('sprendimai');
		$data['uzdavinys'] = $this->uzdaviniai->get_one_by_id($U_ID);
		//$info = getimagesize($path);
		//$extension = image_type_to_extension($info[2]);
		$data['ar_admin']= $this->session->userdata['logged_in']['Yra_administratorius'];
		
		//--- taisyklės
		$config = array(
			array(
				'field' => 'atsakymas',
				'label' => 'Pateiktas atsakymas',
				'rules' => 'trim|max_length[100]|required',
					array(
					'required' => 'Jūs nepateikėte atsakymo.'
					)
				)
		);		
		
		$this->form_validation->set_rules($config);

                if ($this->form_validation->run() == FALSE)
                {    
					$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
					$this->load->model('n_teises');
					if($this->session->userdata['logged_in']['Yra_administratorius'] || $this->n_teises->teise_uzdaviniui($this->session->userdata['logged_in']['Naudotojo_id'],$U_ID))
					{
						$data['rodyti']= true;
						$data['sprendimai'] =$this->sprendimai->get_visus_u_sprendimus($U_ID);				
					}
					else 
					{
						$data['rodyti']=false;
						$data['sprendimai'] = $this->sprendimai->get_u_sprendimus($this->session->userdata['logged_in']['Naudotojo_id'], $U_ID);
					}
					$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);					
					$this->load->view('registruotiems/uzdavinys',$data);
					$this->load->view('abiems/Footer');
                }
                else
                {
					$this->load->model('n_teises');
					if($this->session->userdata['logged_in']['Yra_administratorius'] || $this->n_teises->teise_uzdaviniui($this->session->userdata['logged_in']['Naudotojo_id'],$U_ID))
					{
						$data['rodyti']= true;
					
					}
					else 
					{
						$data['rodyti']=false;
					}
					
					if($this->sprendimai->ar_issprende ($this->session->userdata['logged_in']['Naudotojo_id'], $U_ID))
					{
						$data['sprendimai'] = $this->sprendimai->get_u_sprendimus($this->session->userdata['logged_in']['Naudotojo_id'], $U_ID);
						$data['ats']="jau_isprestas";
						$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
						$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);					
						$this->load->view('registruotiems/uzdavinys',$data);
						$this->load->view('abiems/Footer');
					}
					else{
						$this->sprendimai->N_ID=$this->session->userdata['logged_in']['Naudotojo_id'];
						$this->sprendimai->U_ID=$U_ID;
						$this->sprendimai->Pateiktas_atsakymas=str_replace(".",",",str_replace(" ","",$this->input->post('atsakymas')));
						$this->sprendimai->Pridavimo_laikas=$dblaikas;
						$this->sprendimai->taskai=null;
						foreach($data['uzdavinys']as $u)
						if($this->sprendimai->Pateiktas_atsakymas==$u->Atsakymas)
						{	
							$this->sprendimai->Issprestas=true;
							$data['ats']="teisingas";
						}
						else
						{$this->sprendimai->Issprestas=false;
							$data['ats']="neteisingas";}
						
						$this->sprendimai->insert_sprendimas();
						
						$data['sprendimai'] = $this->sprendimai->get_u_sprendimus($this->session->userdata['logged_in']['Naudotojo_id'], $U_ID);
						$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
						$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
						$this->load->view('registruotiems/uzdavinys',$data);
						$this->load->view('abiems/Footer');
						echo '<script language="javascript">';
						
						{echo 'alert("true '.$this->sprendimai->Pateiktas_atsakymas.' ir '.$row->Atsakymas.'")';}
								echo '</script>';
					}
				}
	}
	
	public function varzybu_uzdavinys($V_ID,$U_ID)
	{
		
		//$this->load->helper(array('form', 'url'));
		$dblaikas= date('Y-m-d H:i:s');
		$this->load->library('form_validation');
		//$this->load->database();
		$this->load->model('varzybos');
		$this->load->model('n_teises');
		$data['liko_laiko']=$this->varzybos->get_liko_laiko_iki_pabaigos($V_ID);
		//$data['v_pavad']=$this->varzybos->get_pavadinima_by_id($V_ID);
		//$message = "Toks naudotojas neegzistuoja!".$data['liko_laiko'];
		//		echo "<script type='text/javascript'>alert('$message');</script>";
		if($data['liko_laiko']>"00:00:00")
		{				
			//--- taisyklės
			$config = array(
				array(
					'field' => 'atsakymas',
					'label' => 'Pateiktas atsakymas',
					'rules' => 'trim|max_length[100]|required',
						array(
						'required' => 'Jūs nepateikėte atsakymo.'
						)
					)
			);		
			
			$this->form_validation->set_rules($config);

			if ($this->form_validation->run() == FALSE)
			{    
				$this->load->model('uzdaviniai');
				$this->load->model('sprendimai');
				$data['varzybos'] = $this->varzybos->get_one_by_id($V_ID);
				//$data['v_pavad']=$data['varzybos'][0]->V_pavadinimas;
				$data['uzdavinys'] = $this->uzdaviniai->get_one_by_id($U_ID);
				//$data['uzdaviniai'] = $this->uzdaviniai->get_naudojami_vid_varzybose($V_ID);
				$data['uzdaviniai'] = $this->uzdaviniai->get_naudojami_varzybose_vid_nid($V_ID,$this->session->userdata['logged_in']['Naudotojo_id']);
				//$data['ar_admin']= $this->session->userdata['logged_in']['Yra_administratorius'];			
				//$data['sprendimai'] = $this->sprendimai->get_u_sprendimus($this->session->userdata['logged_in']['Naudotojo_id'], $U_ID);
				
				if($this->session->userdata['logged_in']['Yra_administratorius'] || $this->n_teises->teise_uzdaviniui($this->session->userdata['logged_in']['Naudotojo_id'],$U_ID))
					{
						$data['rodyti']= true;
						$data['sprendimai'] =$this->sprendimai->get_visus_u_sprendimus($U_ID);
					}
					else 
					{
						$data['rodyti']=false;
						$data['sprendimai'] = $this->sprendimai->get_u_sprendimus($this->session->userdata['logged_in']['Naudotojo_id'], $U_ID);
					}
				
				
				$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
				$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);					
				$this->load->view('registruotiems/uzdavinys_varzybose',$data);
				$this->load->view('abiems/Footer');
			}
			else
			{
				$this->load->model('sprendimai');
				$data['rodyti']=false;
				if($this->sprendimai->ar_issprende ($this->session->userdata['logged_in']['Naudotojo_id'], $U_ID))
				{//papildomai ar toks pat nebuvo priduotas;
					$this->load->model('uzdaviniai');
					$this->load->model('sprendimai');
					$data['varzybos'] = $this->varzybos->get_one_by_id($V_ID);
					$data['uzdavinys'] = $this->uzdaviniai->get_one_by_id($U_ID);
					$data['uzdaviniai'] =$this->uzdaviniai->get_naudojami_varzybose_vid_nid($V_ID,$this->session->userdata['logged_in']['Naudotojo_id']);//$this->uzdaviniai->get_naudojami_vid_varzybose($V_ID);
					$data['ar_admin']= $this->session->userdata['logged_in']['Yra_administratorius'];
					$data['sprendimai'] = $this->sprendimai->get_u_sprendimus($this->session->userdata['logged_in']['Naudotojo_id'], $U_ID);
					$data['ats']="jau_priduotas";
					$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
					$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);					
					$this->load->view('registruotiems/uzdavinys_varzybose',$data);
					$this->load->view('abiems/Footer');
				}
				else
				{
					$this->sprendimai->Pridavimo_laikas=$dblaikas;
					$this->sprendimai->N_ID=$this->session->userdata['logged_in']['Naudotojo_id'];
					$this->sprendimai->U_ID=$U_ID;
					$this->sprendimai->Pateiktas_atsakymas=str_replace(".",",",str_replace(" ","",$this->input->post('atsakymas')));	
					

					$this->load->model('uzdaviniai');
					$data['uzdavinys'] = $this->uzdaviniai->get_one_by_id($U_ID);
					$data['varzybos'] = $this->varzybos->get_one_by_id($V_ID);
					
					$this->sprendimai->taskai=(strtotime($dblaikas)-strtotime($data['varzybos'][0]->V_pradzia))/60;
					
					foreach($data['uzdavinys']as $u)
					if($this->sprendimai->Pateiktas_atsakymas==$u->Atsakymas)
					{	
						$this->sprendimai->Issprestas=true;
						$this->sprendimai->taskai=(strtotime($dblaikas)-strtotime($data['varzybos'][0]->V_pradzia))/60;
						$this->sprendimai->pakeisti_taskus ($this->session->userdata['logged_in']['Naudotojo_id'] , $U_ID);
						$data['ats']="teisingas";
						$this->load->model('dalyvavimai');
						//$this->dalyvavimai->update_with_add($this->session->userdata['logged_in']['Naudotojo_id'],$V_ID);
					
					}
					else
					{
						$this->sprendimai->Issprestas=false;
						$this->sprendimai->taskai=0;
						$data['ats']="neteisingas";
					}
					
					$this->sprendimai->insert_sprendimas(); 
					
					$data['ar_admin']= $this->session->userdata['logged_in']['Yra_administratorius'];						
					$data['uzdaviniai'] = $this->uzdaviniai->get_naudojami_varzybose_vid_nid($V_ID,$this->session->userdata['logged_in']['Naudotojo_id']);//$this->uzdaviniai->get_naudojami_vid_varzybose($V_ID);
					$data['sprendimai'] = $this->sprendimai->get_u_sprendimus($this->session->userdata['logged_in']['Naudotojo_id'], $U_ID);
					
					$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
					$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
					$this->load->view('registruotiems/uzdavinys_varzybose',$data);
					$this->load->view('abiems/Footer');
					echo '<script language="javascript">';
					
					{echo 'alert("true '.$this->sprendimai->Pateiktas_atsakymas.' ir '.$row->Atsakymas.'")';}
							echo '</script>';
				}
				
			}
		}
		else 
		{ 
			//--- taisyklės
			$config = array(
				array(
					'field' => 'atsakymas',
					'label' => 'Pateiktas atsakymas',
					'rules' => 'trim|max_length[100]|required',
						array(
						'required' => 'Jūs nepateikėte atsakymo.'
						)
					)
			);		
			
			$this->form_validation->set_rules($config);

			if ($this->form_validation->run() == FALSE)
			{    
				$this->load->model('uzdaviniai');
				$this->load->model('sprendimai');
				$data['varzybos'] = $this->varzybos->get_one_by_id($V_ID);
				$data['uzdavinys'] = $this->uzdaviniai->get_one_by_id($U_ID);
				$data['uzdaviniai'] = $this->uzdaviniai->get_naudojami_varzybose_vid_nid($V_ID,$this->session->userdata['logged_in']['Naudotojo_id']);//= $this->uzdaviniai->get_naudojami_vid_varzybose($V_ID);
				if($this->session->userdata['logged_in']['Yra_administratorius'] || $this->n_teises->teise_uzdaviniui($this->session->userdata['logged_in']['Naudotojo_id'],$U_ID))
					{
						$data['rodyti']= true;
						$data['sprendimai'] =$this->sprendimai->get_visus_u_sprendimus($U_ID);
					}
					else 
					{
						$data['rodyti']=false;
						$data['sprendimai'] = $this->sprendimai->get_u_sprendimus($this->session->userdata['logged_in']['Naudotojo_id'], $U_ID);
					}
				
				
				$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
				$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);					
				$this->load->view('registruotiems/uzdavinys_varzybose',$data);
				$this->load->view('abiems/Footer');
			}
			else
			{
				$this->load->model('sprendimai');
				$data['rodyti']=false;
				if($this->sprendimai->ar_issprende ($this->session->userdata['logged_in']['Naudotojo_id'], $U_ID))
				{//papildomai ar toks pat nebuvo priduotas;
					$this->load->model('uzdaviniai');
					$this->load->model('sprendimai');
					$data['varzybos'] = $this->varzybos->get_one_by_id($V_ID);
					$data['uzdavinys'] = $this->uzdaviniai->get_one_by_id($U_ID);
					$data['uzdaviniai'] = $this->uzdaviniai->get_naudojami_varzybose_vid_nid($V_ID,$this->session->userdata['logged_in']['Naudotojo_id']);//= $this->uzdaviniai->get_naudojami_vid_varzybose($V_ID);
					$data['ar_admin']= $this->session->userdata['logged_in']['Yra_administratorius'];
					$data['sprendimai'] = $this->sprendimai->get_u_sprendimus($this->session->userdata['logged_in']['Naudotojo_id'], $U_ID);
					$data['ats']="jau_priduotas";
					$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
					$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);					
					$this->load->view('registruotiems/uzdavinys_varzybose',$data);
					$this->load->view('abiems/Footer');
				}
				else
				{
					$this->sprendimai->Pridavimo_laikas=$dblaikas;
					$this->sprendimai->N_ID=$this->session->userdata['logged_in']['Naudotojo_id'];
					$this->sprendimai->U_ID=$U_ID;
					$this->sprendimai->Pateiktas_atsakymas=str_replace(".",",",str_replace(" ","",$this->input->post('atsakymas')));	
					

					$this->load->model('uzdaviniai');
					$data['uzdavinys'] = $this->uzdaviniai->get_one_by_id($U_ID);
					$data['varzybos'] = $this->varzybos->get_one_by_id($V_ID);
					
					$this->sprendimai->taskai=null;
					
					foreach($data['uzdavinys']as $u)
					if($this->sprendimai->Pateiktas_atsakymas==$u->Atsakymas)
					{	
						$this->sprendimai->Issprestas=true;
						$data['ats']="teisingas";
						$this->load->model('dalyvavimai');
						//$this->dalyvavimai->update_with_add($this->session->userdata['logged_in']['Naudotojo_id'],$V_ID);					
					}
					else
					{
						$this->sprendimai->Issprestas=false;
						$data['ats']="neteisingas";
					}
					
					$this->sprendimai->insert_sprendimas(); 
					
					$data['ar_admin']= $this->session->userdata['logged_in']['Yra_administratorius'];						
					$data['uzdaviniai']= $this->uzdaviniai->get_naudojami_varzybose_vid_nid($V_ID,$this->session->userdata['logged_in']['Naudotojo_id']);// = $this->uzdaviniai->get_naudojami_vid_varzybose($V_ID);
					$data['sprendimai'] = $this->sprendimai->get_u_sprendimus($this->session->userdata['logged_in']['Naudotojo_id'], $U_ID);
					
					$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
					$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
					$this->load->view('registruotiems/uzdavinys_varzybose',$data);
					$this->load->view('abiems/Footer');
					echo '<script language="javascript">';
					
					{echo 'alert("true '.$this->sprendimai->Pateiktas_atsakymas.' ir '.$row->Atsakymas.'")';}
							echo '</script>';
				}
				
			}
		//redirect(base_url().'index.php/Registruoti_naudotojai/uzdavinys/'.$U_ID, 'refresh');			
		}
	}
	
	public function u_sprendimas($U_ID)
	{
		$Pavadinimas = $this->input->post('pavadinimas');
		/*public $N_ID;
        public $U_ID;
        public $Issprestas;
        public $;
        public $;

		$this->load->database();
		$this->load->model('sprendimai');
		$this->load->model('uzdaviniai');		
		$this->sprendimai->N_ID=$this->session->userdata['logged_in']['Naudotojo_id'];
		$this->sprendimai->U_ID=??;
		$this->sprendimai->Issprestas=??;		
		$this->sprendimai->Pateiktas_atsakymas=$this->input->post('pavadinimas');
		$this->sprendimai->Pridavimo_laikas= date('Y-m-d H:i:s');*/
		
	}
	
	public function u_sprendimas_per_varzybas()
	{
		$Pavadinimas = $this->input->post('pavadinimas');	
		
	}
		
	/*---Naudotojai---*/
	
	public function naudotoju_sarasas ()
	{
		$aktyvus_meniu_punktas=array('a'=>'naudotojai');
		$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
		$this->load->database();
		$this->load->model('naudotojai');
		$data['naudotojai']=$this->naudotojai->naudotoju_sarasas_be_admin();
		$this->load->view('registruotiems/naudotoju_sarasas', $data);
		$this->load->view('abiems/Footer');
	}
	
	public function varzybu_dalyviu_sarasas ($V_ID)
	{
		$aktyvus_meniu_punktas=array('a'=>'naudotojai');
		$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
		$this->load->database();
		$this->load->model('dalyvavimai');
		$data['naudotojai']=$this->dalyvavimai->dalyviu_sarasas($V_ID);
		$data['V_id']=$V_ID;
		$this->load->view('registruotiems/naudotoju_sarasas', $data);
		$this->load->view('abiems/Footer');
	}
	
	public function uzdaviniu_issprendeju_sarasas ($U_ID)
	{
		$aktyvus_meniu_punktas=array('a'=>'naudotojai');
		$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
		$this->load->database();
		$this->load->model('naudotojai');
		$data['naudotojai']=$this->naudotojai->uzdavini_issprendusiu_sarasas($U_ID);
		$data['pavadinimas']="Uždavinį išsprendusių naudotojų sąrašas";
		$this->load->view('registruotiems/naudotoju_sarasas', $data);
		$this->load->view('abiems/Footer');
	}
	
	public function naudotojo_profilis()
	{
		$aktyvus_meniu_punktas=array('a'=>'naudvardas');
		$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
		$this->load->database();
		$this->load->model('naudotojai');
		$data['n_duomenys']=$this->naudotojai->get_naudotojo_duomenis($this->session->userdata['logged_in']['Naudotojo_id']);
		$data['n_statistika']=$this->naudotojai->gauti_naud_statistika($this->session->userdata['logged_in']['Naudotojo_id']);
		$this->load->view('registruotiems/naudotojo_profilis', $data);
		$this->load->view('abiems/Footer');
	}
	
	public function naudotojo_statistika()
	{
		$aktyvus_meniu_punktas=array('a'=>'naudvardas');
		$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
		$this->load->database();
		$this->load->model('naudotojai');
		$data['n_statistika']=$this->naudotojai->gauti_naud_statistika($this->session->userdata['logged_in']['Naudotojo_id']);
		$this->load->view('registruotiems/naudotojo_statistika', $data);
		$this->load->view('abiems/Footer');
	}
	
	public function kito_naudotojo_profilis($N_ID)
	{
		$aktyvus_meniu_punktas=array('a'=>'naudvardas');
		$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
		$this->load->database();
		$this->load->model('naudotojai');
		$data['n_duomenys']=$this->naudotojai->get_naudotojo_duomenis($N_ID);
		$data['kito']="taip";
		$this->load->view('registruotiems/naudotojo_profilis', $data);
		$this->load->view('abiems/Footer');
	}
	
	public function keisti_role($N_ID)
	{
		$this->load->helper(array('form', 'url'));
		//$this->load->library('form_validation');
		$this->load->database();
		$this->load->model('naudotojai');
		
		if($this->session->userdata['logged_in']['Yra_administratorius'])
		{
			$dbrole=$this->naudotojai->gauti_role($N_ID);
			$data = array(
					'Yra_administratorius'=>!$dbrole
						);
		}
		$this->naudotojai->update_naudotojas($N_ID,$data);
		redirect(base_url().'index.php/Registruoti_naudotojai/kito_naudotojo_profilis/'.$N_ID, 'refresh');
	}
	
	public function keisti_slaptazodi($keicia_savo=null)
	{	
		if(!$keicia_savo)
		{//keicia kito naudotojo
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			//$this->load->database();
			$this->load->model('naudotojai');
			//--- taisyklės
			$config = array(
				array(
					'field' => 'naudotojo_vardas',
					'label' => 'Naudotojo vardas',
					'rules' => 'required|trim|max_length[25]'
					),
				array(
					'field' => 'slaptazodis',
					'label' => 'Slaptažodis',
					'rules' => 'required|trim|required|alpha_numeric|min_length[6]|max_length[50]'
					)
			);
			$this->form_validation->set_rules($config);

			if ($this->form_validation->run() == FALSE)
			{                        						
				redirect($_SERVER['HTTP_REFERER']);
			}
			else if(!($this->naudotojai->exist_naudotojas($this->input->post('naudotojo_vardas'))))
			{			
				$message = "Toks naudotojas neegzistuoja!";
				echo "<script type='text/javascript'>alert('$message');</script>";
				
				redirect($_SERVER['HTTP_REFERER']);
			}
			else
			{	
				$Naud_id=$this->naudotojai->gauti_naud_id($this->input->post('naudotojo_vardas'));
				$data = array(
					'N_ID' => $Naud_id,
					'Slaptazodis' => MD5($this->input->post('slaptazodis')),
				);		
				$this->naudotojai->update_naudotojas($Naud_id, $data);
				
				$message = "Slaptažodis pakeistas sėkmingai!";
				echo "<script type='text/javascript'>alert('$message');</script>";
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
		else //keicia savo slaptazodi
		{
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			//$this->load->database();
			$this->load->model('naudotojai');
			//--- taisyklės
			$config = array(
				array(
					'field' => 'slaptazodis',
					'label' => 'Slaptažodis',
					'rules' => 'required|trim|required|alpha_numeric|min_length[6]|max_length[50]'
					),
				array(
					'field' => 'slaptazodis2',
					'label' => 'Pakartotas slaptažodis',
					'rules' => 'trim|required|matches[slaptazodis]'
					),
				array(
					'field' => 'senslaptazodis',
					'label' => 'Esamas slaptažodis',
					'rules' => 'required|trim|required|alpha_numeric|min_length[6]|max_length[50]'
					)
			);
			$this->form_validation->set_rules($config);

			if ($this->form_validation->run() == FALSE)
			{                        						
				redirect(base_url().'index.php/Registruoti_naudotojai/naudotojo_profilis', 'refresh');
			}
			else if(!$this->naudotojai->tikrinti_sl_ir_id_naudotojas($this->session->userdata['logged_in']['Naudotojo_id'],
							$this->input->post('senslaptazodis')))
			{			
				$message = "Neteisingas esantis slaptažodis!";
				echo "<script type='text/javascript'>alert('$message');</script>";
				//$data['klaida'] = "Neteisingas esantis slaptažodis!";
				redirect(base_url().'index.php/Registruoti_naudotojai/naudotojo_profilis', 'refresh');
			}
			else
			{	
				$data = array(
					'N_ID' => $this->session->userdata['logged_in']['Naudotojo_id'],
					'Slaptazodis' => MD5($this->input->post('slaptazodis')),
				);		
				$this->naudotojai->update_naudotojas($this->session->userdata['logged_in']['Naudotojo_id'], $data);
				
				$message = "Slaptažodis pakeistas sėkmingai!";
				echo "<script type='text/javascript'>alert('$message');</script>";
				redirect(base_url().'index.php/Registruoti_naudotojai/naudotojo_profilis', 'refresh');
			}
		}		
	}
	public function keisti_profili()
	{		
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->database();
		$this->load->model('naudotojai');
		
		//--- taisyklės
		$config = array(
			array(
				'field' => 'vardas',
				'label' => 'Vardas',
				'rules' => 'required|max_length[20]|callback_alphalt',
				),
			array(
				'field' => 'pavarde',
				'label' => 'Pavardė',
				'rules' => 'required|max_length[25]|callback_alphalt'
				),
				array(
				'field' => 'el_pastas',
				'label' => 'El. pašto adresas',
				'rules' => 'required|max_length[50]|valid_email'
				),
			array(
				'field' => 'statusas',
				'label' => 'Statusas',
				'rules' => 'required|in_list[Mokinys/ė,Studentas/ė,Kita]'
				),
			array(
				'field' => 'mok',
				'label' => 'Klasė',
				'rules' => 'numeric'
				),
			array(
				'field' => 'slaptazodis',
				'label' => 'Slaptažodis',
				'rules' => 'trim|required|alpha_numeric|min_length[6]|max_length[50]'//|callback_passwordCorect'
				)
		);
		
		$this->form_validation->set_rules($config);

                if ($this->form_validation->run() == FALSE)
                {                        
					$aktyvus_meniu_punktas=array('a'=>'naudvardas');
					$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
					$this->load->database();
					$this->load->model('naudotojai');
					$data['duomenys']=$this->naudotojai->get_naudotojo_duomenis($this->session->userdata['logged_in']['Naudotojo_id']);
					$this->load->view('registruotiems/keisti_profili', $data);
					$this->load->view('abiems/Footer');
                }
                else
                {					
					$data2 = array(
						'Vardas'=>$this->input->post('vardas'),
						'Pavarde'=>$this->input->post('pavarde'),
						'E_pastas'=>$this->input->post('el_pastas'),
						'Statusas'=>$this->input->post('statusas'),
						'Klase'=>$this->input->post('mok'),
						'Grupe'=>$this->input->post('stud')
					);
					/*$this->naudotojai->update_naudotojas(
							$this->session->userdata['logged_in']['Naudotojo_id'],
							$data2);*/
					if($this->naudotojai->tikrinti_sl_ir_id_naudotojas(
							$this->session->userdata['logged_in']['Naudotojo_id'],
							$this->input->post('slaptazodis')))
						{
							$this->naudotojai->update_naudotojas(
							$this->session->userdata['logged_in']['Naudotojo_id'],
							$data2);							
							$this->naudotojo_profilis();
							$message = "Jūsų pateikti nauji profilio duomenys buvo sėkmingai išsaugoti.";
							echo "<script type='text/javascript'>alert('$message');</script>";
						}
					else 
					{
						$aktyvus_meniu_punktas=array('a'=>'naudvardas', 'b'=>'error');
						$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
						$this->load->database();
						$this->load->model('naudotojai');
						$data['duomenys']=$this->naudotojai->get_naudotojo_duomenis($this->session->userdata['logged_in']['Naudotojo_id']);
						$this->load->view('registruotiems/keisti_profili', $data);
						$this->load->view('abiems/Footer');
						
					}
                }
		
	}
	
	public function alphalt ($string)
	{
		
		$pattern = '/^[a-zA-ZąčęėįšųūžĄČĘĖĮŠŲŪŽ]*$/';
		
			if(preg_match($pattern, $string))
				return true;
			else return false;
	}
	
	public function passwordCorect ($string)
	{
			$this->load->database();
			$this->load->model('naudotojai');
			if($this->naudotojai->tikrinti_sl_ir_id_naudotojas($this->session->userdata['logged_in']['Naudotojo_id'], $string))
				return true;
			else return false;
	}
	
	public function atjungti()
	{
		$this->session->sess_destroy();
		redirect(base_url().'index.php/Neregistruoti_naudotojai/pagrindinis', 'refresh');
	}

	/*---Naudotojų grupės---*/
	
	public function mano_grupiu_sarasas ()
	{
		if($this->session->userdata['logged_in']['Yra_administratorius'])
		{
		redirect(base_url().'index.php/Registruoti_naudotojai/visu_grupiu_sarasas', 'refresh');
		}
		$aktyvus_meniu_punktas=array('a'=>'naudvardas');
		$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
		$this->load->model('n_grupes');
		$data['grupes']=$this->n_grupes->naudotojo_grupiu_sarasas($this->session->userdata['logged_in']['Naudotojo_id']);
		$this->load->view('registruotiems/grupiu_sarasas', $data);
		$this->load->view('abiems/Footer');
	}
	
	public function visu_grupiu_sarasas ()
	{
		$aktyvus_meniu_punktas=array('a'=>'naudvardas');
		$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
		$this->load->model('n_grupes');
		$data['grupes']=$this->n_grupes->visu_grupiu_sarasas();
		$this->load->view('registruotiems/grupiu_sarasas', $data);
		$this->load->view('abiems/Footer');
	}
	
	public function kurti_grupe()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->model('n_grupes');
		//--- taisyklės
		$config = array(
			array(
				'field' => 'pavadinimas',
				'label' => 'Grupės pavadinimas',
				'rules' => 'required|trim|min_length[1]|max_length[20]|is_unique[n_grupes.G_pavadinimas]'
				)
		);
		
		$this->form_validation->set_rules($config);

				if ($this->form_validation->run() == FALSE)
				{                        
					$aktyvus_meniu_punktas=array('a'=>'naudvardas');
					$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
					$data['grupes']=$this->n_grupes->naudotojo_grupiu_sarasas($this->session->userdata['logged_in']['Naudotojo_id']);
					$this->load->view('registruotiems/grupiu_sarasas', $data);
					$this->load->view('abiems/Footer');
				}
				else
				{							
					$this->n_grupes->G_pavadinimas=$this->input->post('pavadinimas');
					$this->n_grupes->Kurimo_data=date('Y-m-d H:i:s');
					
					$this->load->model('grupes_nariai');
					$this->grupes_nariai->G_ID=$this->n_grupes->kurti_grupe();
					$this->grupes_nariai->N_ID=$this->session->userdata['logged_in']['Naudotojo_id'];
					$this->grupes_nariai->Nuo_kada=date('Y-m-d H:i:s');
					$this->grupes_nariai->priskirti_nari();
					$message = "Nauja grupė sėkmingai sukurta.";
					echo "<script type='text/javascript'>alert('$message');</script>";
					redirect(base_url().'index.php/Registruoti_naudotojai/mano_grupiu_sarasas/', 'refresh');
					
			}
	
	}
	
	public function keisti_grupes_pavadinima($G_id)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->database();
		$this->load->model('n_grupes');
		//--- taisyklės
		$config = array(
			array(
				'field' => 'naujas_pavadinimas',
				'label' => 'Grupės pavadinimas',
				'rules' => 'required|trim|min_length[1]|max_length[20]|is_unique[n_grupes.G_pavadinimas]'
				)
		);
		
		$this->form_validation->set_rules($config);

				if ($this->form_validation->run() == FALSE)
				{                        
					$message =  form_error('naujas_pavadinimas');
					$message =  substr ( $message , 3 , -4 );
					echo "<script type='text/javascript'>alert('$message');</script>";
					$aktyvus_meniu_punktas=array('a'=>'naudvardas');
					redirect(base_url().'index.php/Registruoti_naudotojai/grupes_profilis/'.$G_id, 'refresh');
					
				}
				else
				{	
					$data = array(
						'G_pavadinimas'=>$this->input->post('naujas_pavadinimas'));						
					$this->n_grupes->keisti_grupes_pavadinima($G_id, $data);
					redirect(base_url().'index.php/Registruoti_naudotojai/grupes_profilis/'.$G_id, 'refresh');
					
			}
	
	}
	
	public function grupes_profilis($G_ID)
	{
		$aktyvus_meniu_punktas=array('a'=>'naudvardas');
		$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
		$this->load->database();
		$this->load->model('grupes_nariai');
		$this->load->model('n_grupes');
		$this->grupes_nariai->G_ID=$G_ID;
		$this->grupes_nariai->N_ID=$this->session->userdata['logged_in']['Naudotojo_id'];
		$data['yra_narys']=$this->grupes_nariai->exist_narys();
		$data['nariai']=$this->grupes_nariai->visi_grupes_nariai($G_ID);
		$data['gr_pavadinimas']=$this->n_grupes->gauti_grupes_pavadinima($G_ID);
		$this->load->view('registruotiems/grupe', $data);
		$this->load->view('abiems/Footer');
		
	}
	
	public function prideti_nari($G_ID)
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->database();
		$this->load->model('n_grupes');
		$this->load->model('naudotojai');
		//--- taisyklės
		$config = array(
			array(
				'field' => 'naudotojo_vardas',
				'label' => 'Naudotojo vardas',
				'rules' => 'required|trim|max_length[25]'
				)
		);
		
		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() == FALSE)
		{                        
			$aktyvus_meniu_punktas=array('a'=>'naudvardas');
			$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
			$data['grupes']=$this->n_grupes->naudotojo_grupiu_sarasas($this->session->userdata['logged_in']['Naudotojo_id']);
			$this->load->view('registruotiems/grupiu_sarasas', $data);
			$this->load->view('abiems/Footer');
		}
		else
		{							
			$naud_v = $this->input->post('naudotojo_vardas');
			if($this->naudotojai->exist_naudotojas_ne_admin($naud_v))
			{
				$this->load->model('grupes_nariai');							
				$this->grupes_nariai->G_ID=$G_ID;
				$this->grupes_nariai->N_ID=$this->naudotojai->gauti_naud_id($naud_v);
				$this->grupes_nariai->Nuo_kada=date('Y-m-d H:i:s');
				if($this->grupes_nariai->exist_narys())//if(gal jau yra grupes narys)
				{
					$message = "Nurodytas naudotojas jau yra grupės narys.";
				}
				else
				{
					$this->grupes_nariai->priskirti_nari();						
					$message = "Naujas narys pridėtas sėkmingai.";
				}
				
					echo "<script type='text/javascript'>alert('$message');</script>";
					redirect(base_url().'index.php/Registruoti_naudotojai/grupes_profilis/'.$G_ID, 'refresh');
				
			}
			else
			{
				$message = "Toks naudotojas neegzistuoja arba negali būti pridėtas!";
					echo "<script type='text/javascript'>alert('$message');</script>";
				redirect(base_url().'index.php/Registruoti_naudotojai/grupes_profilis/'.$G_ID, 'refresh');
				
			}
			
			
		}
	
	}
	
	public function trinti_nari($G_ID, $N_ID)
	{		
		$this->load->database();
		$this->load->model('grupes_nariai');
		$this->grupes_nariai->G_ID=$G_ID;
		$this->grupes_nariai->N_ID=$N_ID;
		if($this->grupes_nariai->delete_nari())
		{	$message = "Narys buvo pašalintas.";}
		else {$message = "Narys nebuvo arba negali būti pašalintas.";}
		echo "<script type='text/javascript'>alert('$message');</script>";
		redirect(base_url().'index.php/Registruoti_naudotojai/grupes_profilis/'.$G_ID, 'refresh');
	}
	
	public function trinti_grupe($G_ID)
	{
		$this->load->database();
		$this->load->model('n_grupes');
		
		if($this->n_grupes->delete_grupe($G_ID))
		{
			$message = "Pasirinkta grupė ištrinta.";
			echo "<script type='text/javascript'>alert('$message');</script>";
			redirect(base_url().'index.php/Registruoti_naudotojai/mano_grupiu_sarasas', 'refresh');
		}
		else
		{
			$message = "Grupė negali būti ištrinta, nes su ja dalyvavote varžybose!";
			echo "<script type='text/javascript'>alert('$message');</script>";
			redirect(base_url().'index.php/Registruoti_naudotojai/mano_grupiu_sarasas', 'refresh');
		}
	}
	
	/*---Naudotojų žinutės---*/
	
	public function zinutes($Naud_id=null)
	{		
		if(is_null($Naud_id))
		{
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			//$this->load->database();
			$this->load->model('zinutes');
			
			if($this->session->userdata['logged_in']['Yra_administratorius'])
			{
				$data['kontaktai']=$this->zinutes->adm_susirasineja_su($this->session->userdata['logged_in']['Naudotojo_id']);
			}
			else
			{
				$data['su_kuo']= "Administratorius";
				$data['susirasinejimas']=$this->zinutes->get_susirasinejima($this->session->userdata['logged_in']['Naudotojo_id'], 0);
				$this->zinutes->nustatyti_kad_perskaite($this->session->userdata['logged_in']['Naudotojo_id']);			
			}
			$aktyvus_meniu_punktas=array('a'=>'naudvardas');
			$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
			$this->load->view('registruotiems/zinutes',$data);
			$this->load->view('abiems/Footer');
		}
		else
		{
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			$this->load->database();
			$this->load->model('zinutes');
			$this->load->model('naudotojai');
			$data['su_kuo']=$this->naudotojai->gauti_naud_varda ($Naud_id);
			$data['susirasinejimas']=$this->zinutes->adm_get_susirasinejima($this->session->userdata['logged_in']['Naudotojo_id'], $Naud_id);
			$this->zinutes->adm_nustatyti_kad_perskaite($this->session->userdata['logged_in']['Naudotojo_id'], $Naud_id);			
			$data['kontaktai']=$this->zinutes->adm_susirasineja_su($this->session->userdata['logged_in']['Naudotojo_id']);
			$aktyvus_meniu_punktas=array('a'=>'naudvardas');
			$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
			$this->load->view('registruotiems/zinutes',$data);
			$this->load->view('abiems/Footer');
		}
	}
	
	public function siusti_zinute($Naud_id=null)
	{
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->database();
		$this->load->model('zinutes');
		//--- taisyklės
		$config = array(
			array(
				'field' => 'zinute',
				'label' => 'Žinutė',
				'rules' => 'required|trim|max_length[300]'
				)
		);
		
		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() == FALSE)
		{                        
			$this->zinutes($Naud_id);
		}
		else
		{					
			//kas siuncia? turi buti 0 - jei adminas ne adminui
			if(!$this->session->userdata['logged_in']['Yra_administratorius'])
			{
				$this->zinutes->Siuntejo_id = $this->session->userdata['logged_in']['Naudotojo_id'];
				$this->zinutes->Gavejo_id = 0;
			}
			else
			{
				$this->load->model('naudotojai');
				if($this->naudotojai->gauti_role($Naud_id))//ar gavejas adminas?
				{
					$this->zinutes->Siuntejo_id = $this->session->userdata['logged_in']['Naudotojo_id'];
					$this->zinutes->Gavejo_id = $Naud_id;
				}
				else
				{
					$this->zinutes->Siuntejo_id = 0;
					$this->zinutes->Gavejo_id = $Naud_id;
				}
			}
						
			$this->zinutes->Kada = date('Y-m-d H:i:s');
			$this->zinutes->Ar_perskaityta = false;
			$this->zinutes->Zinute = $this->input->post('zinute');
			
			$this->zinutes->kurti_zinute();
			//$_POST['zinute'] = " ";	
			//$this->zinutes($Naud_id);
			if(is_null($Naud_id))
			{ redirect(base_url().'index.php/Registruoti_naudotojai/zinutes', 'refresh');}
			else
			{ redirect(base_url().'index.php/Registruoti_naudotojai/zinutes/'.$Naud_id, 'refresh');}
		
	}
	}
	
	public function nauja_zinute()
	{		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		//$this->load->database();
		$this->load->model('naudotojai');
		//--- taisyklės
		$config = array(
			array(
				'field' => 'n_vard',
				'label' => 'Naudotojo vardas',
				'rules' => 'required|trim|max_length[25]'
				),
			array(
				'field' => 'zinute',
				'label' => 'Žinutė',
				'rules' => 'required|trim|max_length[300]'
				)
		);
		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() == FALSE)
		{                        						
			redirect(base_url().'index.php/Registruoti_naudotojai/zinutes', 'refresh');
		}
		else if(!($this->naudotojai->exist_naudotojas($this->input->post('n_vard'))))
		{			
			$message = "Toks naudotojas neegzistuoja!";
			echo "<script type='text/javascript'>alert('$message');</script>";
			
			redirect(base_url().'index.php/Registruoti_naudotojai/zinutes', 'refresh');
		}
		else
		{	
				$Naud_id=$this->naudotojai->gauti_naud_id($this->input->post('n_vard'));
				
				if($this->naudotojai->gauti_role($Naud_id))//ar gavejas adminas?
				{
					$this->zinutes->Siuntejo_id = $this->session->userdata['logged_in']['Naudotojo_id'];
					$this->zinutes->Gavejo_id = $Naud_id;
				}
				else
				{
					$this->zinutes->Siuntejo_id = 0;
					$this->zinutes->Gavejo_id = $Naud_id;
				}
						
			$this->zinutes->Kada = date('Y-m-d H:i:s');
			$this->zinutes->Ar_perskaityta = false;
			$this->zinutes->Zinute = $this->input->post('zinute');
			
			$this->zinutes->kurti_zinute();
			//$_POST['zinute'] = " ";	
			//$this->zinutes($Naud_id);
			redirect(base_url().'index.php/Registruoti_naudotojai/zinutes/'.$Naud_id, 'refresh');
		}
	}
	
	/*---Naudotojų teisės---*/
	
	public function naudotoju_teises()
	{
		if($this->session->userdata['logged_in']['Yra_administratorius'])
		{
			$this->load->database();
			$this->load->model('n_teises');
			$data['visos_t']=$this->n_teises->gauti_esamu_sarasa();
			
			$this->load->model('uzdaviniai');
			$this->load->model('varzybos');
			$data['u_sarasas'] = $this->uzdaviniai->get_uzdaviniu_sar_teisems();
			$data['v_sarasas'] = $this->varzybos->get_varzybu_sar_teisems();
			
			$aktyvus_meniu_punktas=array('a'=>'naudotojai');
			$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
			$this->load->view('registruotiems/naudotoju_teises',$data);
			$this->load->view('abiems/Footer');
		}
		else
		{
			redirect(base_url().'index.php/Registruoti_naudotojai/naudotojai', 'refresh');
		}
	}
	
	public function teisiu_kurimas()
	{
		if($this->session->userdata['logged_in']['Yra_administratorius'])
		{
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			//$this->load->database();
			$this->load->model('n_teises');
			$this->load->model('naudotojai');
			$this->load->model('uzdaviniai');
			$this->load->model('varzybos');
			$data['u_sarasas'] = $this->uzdaviniai->get_uzdaviniu_sar_teisems();
			$data['v_sarasas'] = $this->varzybos->get_varzybu_sar_teisems();
			//--- taisyklės
			$config = array(
				array(
					'field' => 'kam_vardas',
					'label' => 'Naudotojo vardas',
					'rules' => 'required|trim|min_length[1]|max_length[20]'
					),
				array(
					'field' => 'objektas',
					'label' => 'Kokį objektą redaguoti',
					'rules' => 'required|in_list[u_1,u_v,v_1,v_v,u_n,v_n]'
					),
				array(
					'field' => 'u_id',
					'label' => 'Kokį objektą redaguoti',
					'rules' => 'required'
					),
				array(
					'field' => 'u_id',
					'label' => 'Kokį objektą redaguoti',
					'rules' => 'required'
					)	
			);
			
			$this->form_validation->set_rules($config);

			if ($this->form_validation->run() == FALSE)
			{                        
				$data['visos_t']=$this->n_teises->gauti_esamu_sarasa();
				$aktyvus_meniu_punktas=array('a'=>'naudotojai');
				$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
				$this->load->view('registruotiems/naudotoju_teises',$data);
				$this->load->view('abiems/Footer');
			}
			else
			{							
				$naud_v = $this->input->post('kam_vardas');
					if($this->naudotojai->exist_naudotojas_ne_admin ($naud_v))
					{
						$this->n_teises->Kas_suteike_id = $this->session->userdata['logged_in']['Naudotojo_id'];
						$this->n_teises->Kam_suteike_id = $this->naudotojai->gauti_naud_id ($naud_v);
						$obj = substr($this->input->post('objektas'), 0,1);
						
						
						//leista redaguoti tik nurodyta elementa
						if(substr($this->input->post('objektas'), -1,1)=='1')
						{
							$this->n_teises->Koks_objektas = ($obj=='v' ? "varžybas" : "uždavinį");
							$this->n_teises->Objekto_id =$this->input->post(($obj=='v' ? 'v_id' : 'u_id'));
						}
						//leista redaguoti visus to tipo objektus
						else if(substr($this->input->post('objektas'), -1,1)=='v')
						{
							$this->n_teises->Objekto_id = 0;
							$this->n_teises->Koks_objektas = ($obj=='v' ? "varžybas" : "uždavinius");
						}
						else //liko n - tik naudotojo sukurtus
						{
							$this->n_teises->Objekto_id = null;
							$this->n_teises->Koks_objektas = ($obj=='v' ? "varžybas" : "uždavinius");
						}
						
						$this->n_teises->Leisti_kurti = $this->input->post('leisti_kurti');
						$this->n_teises->Galioja_nuo = date('Y-m-d H:i:s');
						
						
						$this->n_teises->kurti_nauja();
						redirect(base_url().'index.php/Registruoti_naudotojai/naudotoju_teises', 'refresh');
					}
					else
					{
						$message = "Toks naudotojas neegzistuoja!";
						echo "<script type='text/javascript'>alert('$message');</script>";
						redirect(base_url().'index.php/Registruoti_naudotojai/grupes_profilis/'.$G_ID, 'refresh');
						
					}
				
			}
		}
		else
		{
			redirect(base_url().'index.php/Registruoti_naudotojai/naudotojai', 'refresh');
		}
	}
	
	public function teisiu_keitmas(){}
	
	public function teisiu_naikinimas($T_ID)
	{
		
		$this->load->database();
		$this->load->model('n_teises');
		$this->n_teises->naikinti_teise($T_ID);
		$data['visos_t']=$this->n_teises->gauti_esamu_sarasa();
		$data['message']="istrinta";
		
		$aktyvus_meniu_punktas=array('a'=>'naudotojai');
		$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
		$this->load->view('registruotiems/naudotoju_teises',$data);
		$this->load->view('abiems/Footer');
	}
	
	/*---MENIU---*/
	public function DUK()
	{
		$aktyvus_meniu_punktas=array('a'=>'DUK');
		$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
		$this->load->view('abiems/apie');
		$this->load->view('abiems/Footer');
	}
	
	///Failo atsiuntimas
	function siustiCSV($V_ID=null)
	{
		
		if(!IS_NULL($V_ID))
		{//siunciamas uzsiregistravusiu naudotoju sarasas
			$this->load->helper('download');
			$this->load->model('varzybos');
			$filename = $this->varzybos->get_pavadinima_by_id($V_ID).' _dalyviai.csv';
			$CSV_data = chr(239) . chr(187) . chr(191) .$CSV_data;
				
			$this->load->model('dalyvavimai');
			
			$spausdinimui['n_vardas']=!is_null($this->input->post('n_vardas'));
			$spausdinimui['v_p']=!is_null($this->input->post('v_p'));
			$spausdinimui['e_pastas']=!is_null($this->input->post('e_pastas'));
			$spausdinimui['statusas']=!is_null($this->input->post('statusas'));
			//IF($this->input->post('n_vardas')){}
				
			//$message = $spausdinimui['n_vardas']."Toks naudotojas neegzistuoja!";
			//			echo "<script type='text/javascript'>alert('$message');</script>";
			
			$dalyviai = $this->dalyvavimai->dalyviu_sarasas_spausdinimui($V_ID);
			$data = "Nr.";
			if($spausdinimui['n_vardas']) $data=$data.";Naudotojo vardas";
			if($spausdinimui['v_p']) $data=$data."; Vardas, pavardė";				
			if($spausdinimui['e_pastas']) $data=$data."; El. pašto adresas";				
			if($spausdinimui['statusas']) $data=$data."; Statusas";
			$data=$data."\n";
			$i=1;
			FOREACH($dalyviai as $dalyvis)
			{	
				$data=$data.$i;
				if($spausdinimui['n_vardas']) $data=$data.';'.$dalyvis->Naud_vardas;
				if($spausdinimui['v_p']) $data=$data.';'.$dalyvis->Vardas.' '.$dalyvis->Pavarde;				
				if($spausdinimui['e_pastas']) $data=$data.';'.$dalyvis->E_pastas;				
				if($spausdinimui['statusas']) $data=$data.';'.$dalyvis->Statusas;
				$data=$data."\n";
				$i++;
			}
			force_download($filename, "\xEF\xBB\xBF" . $data);
		}
        redirect(base_url().'index.php/Registruoti_naudotojai/naudotoju_sarasas', 'refresh');
	}
	
}

?>





















