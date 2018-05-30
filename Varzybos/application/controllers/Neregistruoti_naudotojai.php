<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Neregistruoti_naudotojai extends CI_Controller {

	public function __construct()
	{	
		parent::__construct();
		$this->load->library('session');
		date_default_timezone_set('Europe/Vilnius');
		if (isset($this->session->userdata['logged_in']))
		{
			redirect(base_url().'index.php/Registruoti_naudotojai/pagrindinis', 'refresh');
		}				
	}	
	
	public function pagrindinis()
	{
		$aktyvus_meniu_punktas=array('a'=>'pagrindinis');
		$this->load->view('abiems/Head', $aktyvus_meniu_punktas);
		$this->load->view('neregistruotiems/Header');
		$this->load->view('Content');
		$this->load->view('abiems/Footer');
	}
	
	/*---VARŽYBOS---*/
	public function varzybos()
	{
		$aktyvus_meniu_punktas=array('a'=>'varzybos');
		$this->load->view('abiems/Head');
		$this->load->view('neregistruotiems/Header', $aktyvus_meniu_punktas);		
		
		$this->load->database();
		$this->load->model('varzybos');
		$data['busimos'] = $this->varzybos->get_busimas_su_naud();		
		$data['vykstancios'] = $this->varzybos->get_vykstancias_su_naud();		
		$data['ivykusios'] = $this->varzybos->get_ivykusias_su_naud();
		$this->load->view('neregistruotiems/varzybos_page', $data);		
		$this->load->view('abiems/Footer');
	}
		
	
	public function v_tur_lentele($v_id)
	{
		$aktyvus_meniu_punktas=array('a'=>'varzybos');
		$this->load->view('abiems/Head');
		$this->load->view('neregistruotiems/Header', $aktyvus_meniu_punktas);
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
		
		/*if($data['liko_laiko']>"00:00:00")
		{			
			$data['paskutinis_sprendimas']=$this->sprendimai->get_last_sprendima($v_id);			
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
		}*/
			
		
		$this->load->view('neregistruotiems/turnyrine_lentele',$data);
		$this->load->view('abiems/Footer');
	}
		
	/*---Naudotojai---*/
	public function registracija()
	{
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->database();
		$this->load->model('naudotojai');
		
		//--- taisyklės
		$config = array(
			array(
				'field' => 'naudotojo_vardas',
				'label' => 'Naudotojo vardas',
				'rules' => 'required|min_length[5]|max_length[50]|alpha_dash|is_unique[naudotojai.Naud_vardas]',//|xss_clean',
				array(
					'required'=> 'Šis laukas privalomas!',
					'min_length|max_length'=> 'Netinkamas naudotojo vardo ilgis!',
					'alpha_dash'=> 'Šis laukas privalomas!',
					'is_unique'=> 'Toks naudotojas jau egzistuoja :( ')
				),
			array(
				'field' => 'vardas',
				'label' => 'Vardas',
				'rules' => 'required|max_length[20]|callback_alphalt',//|xss_clean'
				),
			array(
				'field' => 'pavarde',
				'label' => 'Pavardė',
				'rules' => 'required|max_length[25]|callback_alphalt'
				),
				array(
				'field' => 'el_pastas',
				'label' => 'El. pašatas',
				'rules' => 'required|max_length[50]|valid_email|is_unique[naudotojai.E_pastas]'
				),
			array(
				'field' => 'slaptazodis',
				'label' => 'Slaptažodis',
				'rules' => 'trim|required|alpha_numeric|min_length[8]|max_length[50]'
				),
			array(
				'field' => 'slaptazodis2',
				'label' => 'Pakartotas slaptažodis',
				'rules' => 'trim|required|matches[slaptazodis]'
				),
			array(
				'field' => 'statusas',
				'label' => 'Statusas',
				'rules' => 'required|in_list[Mokinys/ė,Studentas/ė,Kita]'
				),
			array(
				'field' => 'mok',
				'label' => 'Statuso',
				'rules' => 'numeric'
				)
		);
		
		$this->form_validation->set_rules($config);

                if ($this->form_validation->run() == FALSE)
                {                        
					$aktyvus_meniu_punktas=array('a'=>'naudotojai');
					$this->load->view('abiems/Head');
					$this->load->view('neregistruotiems/Header', $aktyvus_meniu_punktas);
					$this->load->view('neregistruotiems/registracija');
					$this->load->view('abiems/Footer');
                }
                else
                {
					$this->load->database();
					$this->load->model('naudotojai');
					$this->naudotojai->Naud_vardas=$this->input->post('naudotojo_vardas');
					$this->naudotojai->Vardas=$this->input->post('vardas');
					$this->naudotojai->Pavarde=$this->input->post('pavarde');
					$this->naudotojai->E_pastas=$this->input->post('el_pastas');
					$this->naudotojai->Slaptazodis=$this->input->post('slaptazodis');
					$this->naudotojai->Statusas=$this->input->post('statusas');
					$this->naudotojai->Yra_administratorius=false;
					$this->naudotojai->Klase=str_replace(" ","",$this->input->post('mok'));
					$this->naudotojai->Grupe=str_replace(" ","",$this->input->post('stud'));
					
					$this->naudotojai->insert_naudotojas();
					
					$aktyvus_meniu_punktas=array('a'=>'pagrindinis');
					$this->load->view('abiems/Head');
					$this->load->view('neregistruotiems/Header', $aktyvus_meniu_punktas);
					$data['vieta'] = 'Neregistruoti_naudotojai/prisijungimas';
					$this->load->view('abiems/formsuccess', $data);
					$this->load->view('abiems/Footer');
                }
		
	}
	
	public function alphalt ($string)
	{		
		$pattern = '/^[a-zA-ZąčęėįšųūžĄČĘĖĮŠŲŪŽ]*$/';
		
			if(preg_match($pattern, $string))
				return true;
			else return false;
	}
	
		
	/*---UŽDAVINIAI---*/
	public function uzdaviniai()
	{
		$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
		$this->load->view('abiems/Head');
		$this->load->view('neregistruotiems/Header', $aktyvus_meniu_punktas);
		$this->load->database();
		$this->load->model('uzdaviniai');
		$data['uzdaviniai'] = $this->uzdaviniai->get_dalyvavo_vid();	
		$this->load->view('neregistruotiems/uzdaviniai',$data);
		$this->load->view('abiems/Footer');
	}
	
	public function uzdavinys($u_id)
	{
		$this->load->view('abiems/Head');
		$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
		$this->load->view('neregistruotiems/Header', $aktyvus_meniu_punktas);
		$this->load->database();
		$this->load->model('uzdaviniai');
		$data['uzdavinys'] = $this->uzdaviniai->get_one_by_id($u_id);	
		$this->load->view('neregistruotiems/uzdavinys',$data);
		$this->load->view('abiems/Footer');
	}
	
	/*---MENIU---*/
	public function DUK()
	{
		$this->load->view('abiems/Head');	
		$aktyvus_meniu_punktas=array('a'=>'DUK');
		$this->load->view('neregistruotiems/Header', $aktyvus_meniu_punktas);
		$this->load->view('abiems/apie');
		$this->load->view('abiems/Footer');
	}
	
	public function prijungimas($vieta)
	{
		$aktyvus_meniu_punktas=array('a'=>'prijungti');
		$this->load->view('abiems/Head');
		$this->load->view('neregistruotiems/Header', $aktyvus_meniu_punktas);
		$data['vieta'] = $vieta;
		$this->load->view('neregistruotiems/prisijungimas', $data);
		$this->load->view('abiems/Footer');
	}
	
	public function prisijungimas()
	{
		//session_start();
		// Load form helper library
		$this->load->helper('form');

		// Load form validation library
		$this->load->library('form_validation');

		// Load session library
		$this->load->library('session');

		// Load database
		$this->load->model('login_database');
		//$this->load->database();
		//$this->load->model('naudotojai');
		
		$this->form_validation->set_rules('Naudotojo_vardas', 'Naudotojo vardas', 'trim|required');
		$this->form_validation->set_rules('Slaptazodis', 'Slaptažodis', 'trim|required');
		//$this->form_validation->set_message('Naudotojo_vardas', 'Netinkamas naudotojo vardas arba slaptažodis');

		if ($this->form_validation->run() == FALSE) 
		{
			if(isset($this->session->userdata['logged_in']))
			{
				redirect(base_url().'index.php/Registruoti_naudotojai/pagrindinis', 'refresh');
			}
			else
			{
				//$this->load->view('login_form');
				$aktyvus_meniu_punktas=array('a'=>'prijungti');
				$this->load->view('abiems/Head');
				$this->load->view('neregistruotiems/Header', $aktyvus_meniu_punktas);
				$this->load->view('neregistruotiems/prisijungimas');
				$this->load->view('abiems/Footer');
				//
			}
		} 
		else 
		{
			$data = array(
				'Naud_vardas' => $this->input->post('Naudotojo_vardas'),
				'Slaptazodis' => $this->input->post('Slaptazodis')
				);
			
			$result = $this->login_database->login($data);
			if ($result == TRUE) 
			{
				$username = $this->input->post('Naudotojo_vardas');
				$result = $this->login_database->read_user_information($username);
				//redirect('/Registruoti_naudotojai/index', 'refresh');
				if ($result != false) 
				{
					//session_start();
					$session_data = array(
					'Naud_vardas' => $result[0]->Naud_vardas,
					'Naudotojo_id' => $result[0]->N_ID,
					'Yra_administratorius' => $result[0]->Yra_administratorius
					);
					
					// Add user data in session
					$this->session->set_userdata('logged_in', $session_data);
					//session_start($this->session);
					//$this->load->view('admin_page');
					//$this->load->view('neregistruotiems/prisijungimas');
					//echo "<script> signOut(); </script>";
					 redirect('/Registruoti_naudotojai/pagrindinis', 'refresh');
					 
					/*$aktyvus_meniu_punktas=array('a'=>'pagrindinis');
					$this->load->view('abiems/Head');
					$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
					$this->load->view('registruotiems/varzybos');
					$this->load->view('abiems/Footer');*/
				}
			} 
			else 
			{
				//$this->load->view('login_form', $data);
				$aktyvus_meniu_punktas=array('a'=>'prijungti', 'b'=>'error');
				$this->load->view('abiems/Head');
				$this->load->view('neregistruotiems/Header', $aktyvus_meniu_punktas);
				$this->load->view('neregistruotiems/prisijungimas');
				$this->load->view('abiems/Footer');
				//
			}
		}
	}
	
	public function prisijungimas_su_elPastu()
	{
		$epastas = $this->input->post('epastas');
		$this->load->helper('form');

		// Load form validation library
		$this->load->library('form_validation');

		// Load session library
		$this->load->library('session');

		// Load database
		$this->load->model('login_database');
		
		$this->form_validation->set_rules('epastas', ' ', 'trim|required|valid_email');
		$this->form_validation->set_message('epastas', 'Klaida prisijungiant Google paskyra.');

		if ($this->form_validation->run() == FALSE) 
		{
			if(isset($this->session->userdata['logged_in']))
			{
				redirect(base_url().'index.php/Registruoti_naudotojai/index', 'refresh');				
			}
			else
			{
				//$this->load->view('login_form');
				
				$aktyvus_meniu_punktas=array('a'=>'prijungti');
				$this->load->view('abiems/Head');
				//$this->load->view('neregistruotiems/Header', $aktyvus_meniu_punktas);
				$this->load->view('neregistruotiems/prisijungimas');
				$this->load->view('abiems/Footer');
				//
			}
		} 
		else 
		{
			$data = array(
				'E_pastas' => $epastas,
				);
			$result = $this->login_database->login2($data);
			if ($result == TRUE) 
			{
				$username = $this->input->post('Naudotojo_vardas');
				$result = $this->login_database->read_user_information2($epastas);
				//redirect('/Registruoti_naudotojai/index', 'refresh');
				if ($result != false) 
				{
					//session_start();
					$session_data = array(
					'Naud_vardas' => $result[0]->Naud_vardas,
					'Naudotojo_id' => $result[0]->N_ID,
					'Yra_administratorius' => $result[0]->Yra_administratorius
					);
					
					// Add user data in session
					$this->session->set_userdata('logged_in', $session_data);
					//session_start($this->session);
					//$this->load->view('admin_page');
					//$this->load->view('neregistruotiems/prisijungimas');
					//echo "<script> signOut(); </script>";
					redirect('/Registruoti_naudotojai/pagrindinis', 'refresh');
					 
					/*$aktyvus_meniu_punktas=array('a'=>'pagrindinis');
					$this->load->view('abiems/Head');
					$this->load->view('registruotiems/Header', $aktyvus_meniu_punktas);
					$this->load->view('registruotiems/varzybos');
					$this->load->view('abiems/Footer');*/
				}
			} 
			else 
			{
				//$this->form_validation->set_message('epastas', 'Klaida prisijungiant Google paskyra.');
				$aktyvus_meniu_punktas=array('a'=>'prijungti');//, 'b'=>'error');
				$this->load->view('abiems/Head');
				$this->load->view('neregistruotiems/Header', $aktyvus_meniu_punktas);
				$this->load->view('neregistruotiems/registracija');
				$this->load->view('abiems/Footer');
				//redirect('/Neregistruoti_naudotojai/registracija', 'refresh');
				echo "<script> add_data(googleUser); </script>";
				//
			}
		}
	}
			
	//---kita
	public function slaptKeitimo_zinute()
	{		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->database();
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
			redirect(base_url().'index.php/Neregistruoti_naudotojai/prisijungimas', 'refresh');
		}
		else if(!($this->naudotojai->exist_naudotojas($this->input->post('naudotojo_vardas'))))
		{			
			$message = "Toks naudotojas neegzistuoja!";
			echo "<script type='text/javascript'>alert('$message');</script>";
			
			redirect(base_url().'index.php/Neregistruoti_naudotojai/prisijungimas', 'refresh');
		}
		else
		{	
			$this->load->model('zinutes');
			$Naud_id=$this->naudotojai->gauti_naud_id($this->input->post('naudotojo_vardas'));
			
			$this->zinutes->Siuntejo_id = $Naud_id;
			$this->zinutes->Gavejo_id = 0;			
			$this->zinutes->Kada = date('Y-m-d H:i:s');
			$this->zinutes->Ar_perskaityta = false;
			foreach($this->naudotojai->get_naudotojo_duomenis ($Naud_id) as $naud)
			{
				$this->zinutes->Zinute = "Prašome pakeisti slaptažodį. Naudotojo el. paštas: ".$naud->E_pastas."
				<br> ___________________________________________ <br> Tai automatinis pranešimas";
			}
			
			
			$this->zinutes->kurti_zinute();
			//$_POST['zinute'] = " ";	
			//$this->zinutes($Naud_id);
			redirect(base_url().'/index.php/Neregistruoti_naudotojai/prisijungimas', 'refresh');
		}
	}

	function slaptazodzio_keitimas()
	{		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->database();
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
			redirect(base_url().'index.php/Neregistruoti_naudotojai/prisijungimas', 'refresh');
		}
		else if(!($this->naudotojai->exist_naudotojas($this->input->post('naudotojo_vardas'))))
		{			
			$message = "Toks naudotojas neegzistuoja!";
			echo "<script type='text/javascript'>alert('$message');</script>";
			
			redirect(base_url().'index.php/Neregistruoti_naudotojai/prisijungimas', 'refresh');
		}
		else
		{	
			$this->load->model('zinutes');
			$Naud=$this->naudotojai->gauti_naud_ep_id($this->input->post('naudotojo_vardas'));
			$nSlapt = $this->randomPassword();
			
			$to = $Naud['E_pastas'];
			$subject = "Slaptažodžio priminimas";
			$txt = "Jūsų naujas slaptažodis: ".$nSlapt.". 
			Prisijungę nepamirškite pasikeisti į naują slaptažodį. Tai galite padaryti profilio nustatymuose pasirinkę \"Keisti slaptažodį.\"";
			$headers = "From: Matematikos varžybų tinklalapis" . "\r\n";// .
			//"CC: somebodyelse@example.com";
			
				$data = array(
					'N_ID' => $Naud['N_ID'],
					'Slaptazodis' => MD5($nSlapt),
				);		
			$this->naudotojai->update_naudotojas($Naud['N_ID'], $data);
			
				mail($to,$subject,$txt,$headers);
			//$message = "Kam =".$to.", Kas =".$nSlapt;
			$message = "Slaptažodis pakeistas sėkmingai. Pasitikrinkite el. paštą.";
			echo "<script type='text/javascript'>alert('$message');</script>";
			
			redirect(base_url().'/index.php/Neregistruoti_naudotojai/prisijungimas', 'refresh');
		}
		
	}
	
	function randomPassword() 
	{
		$alphabet = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz1234567890';
		$slapt = array();
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$slapt[] = $alphabet[$n];
		}
		return implode($slapt); //turn the array into a string
	}
}
