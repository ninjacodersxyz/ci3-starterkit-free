<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

	/**
	 * Obtiene los ultimos 5 dias de ingresos correctos al sistema
	 * y los agrupa por dia
	 * @return array
	 */
	public function get_ingresos_sistema()
	{
		$this->db
		->select('COUNT(1) AS ct, fecha_ingreso')
		->from('ingreso_sistema')
		->group_by('day(fecha_ingreso)')
		->order_by('fecha_ingreso','DESC')
		->limit(5);

		$query= $this->db->get();

		return $query->result_array();	
	}

	/**
	 * Obtiene los ultimos 5 dias de ingresos fallidos al sistema
	 * y los agrupa por dia
	 * @return array
	 */
	public function get_ingresos_faliidos()
	{
		$this->db
		->select('COUNT(1) AS ct, `date`')
		->from('ingreso_fallidos')
		->group_by('day(`date`)')
		->order_by('`date`','DESC')
		->limit(5);

		$query= $this->db->get();

		return $query->result_array();	
	}

	/**
	 * Obtiene los ultimos mensajes de chat (basados en la cantidad dada en el "custom_config")
	 * y los ordena en base a la fecha de ingreso del mensaje en la base de datos
	 * @return array
	 */
	public function get_chat_messages()
	{
		$this->db
		->select('ms.usuario_id, ms.fecha_registro, ms.mensaje, u.nombres, u.ap_paterno, u.ap_materno, u.email')
		->from('mensajeria_sistema ms')
		->join('usuarios u', 'ms.usuario_id = u.id', 'left')
		->order_by('ms.fecha_registro','DESC')
		->limit($this->config->item('chat_messages_qty'));

		$query= $this->db->get();

		return $query->result_array();
	}

}

/* End of file Dashboard_model.php */
/* Location: ./application/models/Dashboard_model.php */