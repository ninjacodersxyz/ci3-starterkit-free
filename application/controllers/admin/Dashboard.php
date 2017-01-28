<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use When\When;

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if( !$this->session->userdata('logged') )
			redirect('/login');
	}

	/**
	 * Pagina principal del Panel de control del sistema
	 * @return HTML
	 */
	public function index()
	{
		$this->load->model('dashboard_model');
		
		$days_before = new DateTime();
		$days_before->sub(new DateInterval('P4D'));

		$r = new When();
		$r->startDate($days_before)
		->freq("daily")
		->count(5)
		->generateOccurrences();
		$recurrence = $r->occurrences;

		/**
		 * Itera sobre los datos iniciales y la recurrencia de los ultimos 5 dias para ver si tiene contador ese dia, sino tiene se asigna un valor de 0 para que no este vacio
		 * @param array $data Datos a ser analizados
		 * @param varchar $key_name Llave identificadora
		 * @return array
		 */
		$check_date = function ($data,$key_name) use ($recurrence){
			$out = array();

			foreach ($data as &$value) {
				$value[$key_name] = new DateTime($value[$key_name]);
			};

			foreach ($recurrence as $k1 => $v1) {
				$exist = false;
				$ct = "0";
				
				foreach ($data as $k2 => $v2) {
					$exist = $v1->format('Y-m-d')==$v2[$key_name]->format('Y-m-d');
					if ($exist) {
						$ct = $exist ? $v2['ct'] : "0";
						break;
					}
				};

				$out[]= array('ct' => $ct, $key_name=> $v1->format('Y-m-d'). ' 00:01');
			};

			return $out;
		};

		$ingresos_correctos= $this->functions->msort($this->dashboard_model->get_ingresos_sistema(),'fecha_ingreso');
		$data['ingresos_correctos'] = json_encode($check_date($ingresos_correctos,'fecha_ingreso'));
		
		$ingresos_fallidos = $this->functions->msort($this->dashboard_model->get_ingresos_faliidos(),'date');
		$data['ingresos_fallidos'] = json_encode($check_date($ingresos_fallidos,'date'));

		$this->template->write('title', 'Listado de usuarios');
		$this->template->write_view('header', 'admin/partials/header', $data, TRUE);
		$this->template->write_view('sidebar', 'admin/partials/sidebar');
		$this->template->write_view('content', 'admin/dashboard/main');
		$this->template->render();
	}

	/**
	 * Obtener los mensajes de chat 
	 * @param  boolean $ret condicion que indica si se devuelve una cadena json o un array PHP
	 * @return json || array
	 */
	public function ajax_get_chat_messages($ret = FALSE)
	{
		$this->load->model('dashboard_model');
		$data = $this->dashboard_model->get_chat_messages();
		$uid = $this->session->userdata('id');
		$out = array();

		foreach ($data as $key=>$item) {
			$temp = array(
				'uid'=> $item['usuario_id'],
				'fecha' => $this->functions->parse_date( $item['fecha_registro'], $this->config->item('chat_date_format')),
				'mensaje' =>$item['mensaje'],
				'usuario' => trim(join(' ' ,array($item['nombres'],$item['ap_paterno'],$item['ap_materno']))),
				'email' => $item['email']
				);
			if ($item['usuario_id'] == $uid)
				$temp['owner'] = 1;
			$out[] = $temp;
		};

		if(!$ret)
			echo json_encode($out);
		else
			return $out;
	}

	/**
	 * Agrega mensaje al sistema de mensajes del sistema
	 * @param string $message POST: mensaje de chat
	 * @return string json
	 */
	public function ajax_add_chat_message()
	{
		$this->generic_model->add('mensajeria_sistema',array('usuario_id'=>$this->session->userdata('id'),'mensaje'=>$this->input->post_get('message')));
		$response = $this->ajax_get_chat_messages(true);
		echo json_encode($response);
	}

}

/* End of file dashboard.php */
/* Location: ./application/controllers/admin/dashboard.php */