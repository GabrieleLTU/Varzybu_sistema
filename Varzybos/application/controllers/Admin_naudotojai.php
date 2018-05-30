<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Neregistruoti_naudotojai extends CI_Controller {

	public function index()
	{
		$aktyvus_meniu_punktas=array('a'=>'pagrindinis');
		$this->load->view('Head', $aktyvus_meniu_punktas);
		$this->load->view('Header');
		$this->load->view('Content');
		$this->load->view('Footer');
	}
	
	/*---VARŽYBOS---*/
	public function varzybos()
	{
		$aktyvus_meniu_punktas=array('a'=>'varzybos');
		$this->load->view('Head');
		$this->load->view('Header', $aktyvus_meniu_punktas);		
		
		$this->load->database();
		$this->load->model('varzybos');
		$data['busimos'] = $this->varzybos->get_busimas();		
		$data['vykstancios'] = $this->varzybos->get_vykstancias();		
		$data['ivykusios'] = $this->varzybos->get_ivykusias();		
		//$data['iki_datos_varzybos'] = $this-> varzybos ->get_all_until_datetime($laikas);
		$this->load->view('varzybos_page', $data);		
		$this->load->view('Footer');
	}
	public function v_kurimas()
	{
		$aktyvus_meniu_punktas=array('a'=>'varzybos');
		$this->load->view('Head');
		$this->load->view('Header', $aktyvus_meniu_punktas);
		$this->load->view('v_kurimas');
		$this->load->view('Footer');
	}
	
	public function insert_entry()
        {
                $this->title    = $_POST['title']; // please read the below note
                $this->content  = $_POST['content'];
                $this->date     = time();

                $this->db->insert('entries', $this);
        }
	public function v_tur_lentele($v_id)
	{
		$aktyvus_meniu_punktas=array('a'=>'varzybos');
		$this->load->view('Head');
		$this->load->view('Header', $aktyvus_meniu_punktas);
		$this->load->database();
		$this->load->model('naudotojai');
		$this->load->model('varzybos');
		$data['dalyviai'] = $this->naudotojai->get_dalyvavo_vid($v_id);		
		$data['v_pavad'] = $this->varzybos->get_one_by_id($v_id);		
		$this->load->view('turnyrine_lentele',$data);
		$this->load->view('Footer');
	}
	
	
	/*---UŽDAVINIAI---*/
	public function uzdaviniai()
	{
		$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
		$this->load->view('Head');
		$this->load->view('Header', $aktyvus_meniu_punktas);
		$this->load->database();
		$this->load->model('uzdaviniai');
		$data['uzdaviniai'] = $this->uzdaviniai->get_naudojamas();		
		$data['kuriami'] = $this->uzdaviniai->get_kuriami();		
		$this->load->view('uzdaviniai',$data);
		$this->load->view('Footer');
	}
	public function u_kurimas()
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		//--- taisyklės
		$config = array(
			array(
				'field' => 'pavadinimas',
				'label' => 'Uždavinio pavadinimas',
				'rules' => 'required'
				),
			array(
				'field' => 'salyga',
				'label' => 'Uždavinio sąlyga',
				'rules' => 'required'
				),
			array(
				'field' => 'atsakymas',
				'label' => 'Uždavinio atsakymas',
				'rules' => 'required'
				)
		);
		
		$this->form_validation->set_rules($config);

                if ($this->form_validation->run() == FALSE)
                {                        
					$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
					$this->load->view('Head');
					$this->load->view('Header', $aktyvus_meniu_punktas);
					$this->load->view('u_kurimas');
					$this->load->view('Footer');
                }
                else
                {
					$this->load->database();
					$this->load->model('uzdaviniai');
					$this->uzdaviniai->U_pavadinimas=$this->input->post('pavadinimas');
					$this->uzdaviniai->Salyga=$this->input->post('salyga');
					$this->uzdaviniai->Iliustracija=$this->input->post('salyga');//iliustracija
					$this->uzdaviniai->Atsakymas=$this->input->post('atsakymas');
					$this->uzdaviniai->N_ID=1;
					$this->uzdaviniai->V_ID=null;
					$this->uzdaviniai->U_Keitimo_data= date('Y-m-d H:i:s') ;
					$this->uzdaviniai->U_paruostas=$this->input->post('ar_paruostas');
					
					$this->uzdaviniai->U_pavadinimas=$this->input->post('atsakymas');
					$this->uzdaviniai->insert_uzdavinys();
					
                    $this->load->view('formsuccess');
                }
		
		/*$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
		$this->load->view('Head');
		$this->load->view('Header', $aktyvus_meniu_punktas);
		$this->load->view('u_kurimas');
		$this->load->view('Footer');*/
	}
	
	public function uzdavinys()
	{
		$aktyvus_meniu_punktas=array('a'=>'uzdaviniai');
		$this->load->view('Head');
		$this->load->view('Header', $aktyvus_meniu_punktas);
		$this->load->database();
		$this->load->model('uzdaviniai');
		$data['uzdavinys'] = $this->uzdaviniai->get_one_by_id(1);	
		$this->load->view('uzdavinys',$data);
		$this->load->view('Footer');
	}
	
	public function u_submit()
	{
		$Pavadinimas = $this->input->post('pavadinimas');	
		
	}
	
	/*---MENIU---*/
	public function apie()
	{
		$aktyvus_meniu_punktas=array('a'=>'apie');
		$this->load->view('Head');
		$this->load->view('Header', $aktyvus_meniu_punktas);
		$this->load->view('Footer');
	}
	public function prisijungimas()
	{
		$aktyvus_meniu_punktas=array('a'=>'apie');
		$this->load->view('Head');
		$this->load->view('Header', $aktyvus_meniu_punktas);
		$this->load->view('prisijungimas');
		$this->load->view('Footer');
	}
	public function registracija()
	{
		$aktyvus_meniu_punktas=array('a'=>'registracija');
		$this->load->view('Head');
		$this->load->view('Header', $aktyvus_meniu_punktas);
		$this->load->view('registracija');
		$this->load->view('Footer');
	}
	
	
}
