<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Functions
{

	public function __construct()
	{
		$this->ci =&get_instance();
		$this->ci->load->helper('text');
	}

	/**
	 * Corta texto html sin perder las etiquetas iniciales
	 * @param  string $string 
	 * @param  int $limit  
	 * @param  string $break  
	 * @param  string $pad    
	 * @return string         
	 */
	function truncate($string, $limit, $break = ".", $pad = "...") 
	{ 
		if (strlen($string) <= $limit)
			return $string;
		if (false !== ($breakpoint = strpos($string, $break, $limit)))
		{
			if ($breakpoint < strlen($string) - 1)
				$string = substr($string, 0, $breakpoint) . $pad;
			return $string;
		}
	}

	/**
	 * Convierte un texto formateado a uno sin formatear
	 * @param  string $texto 
	 * @return string 
	 */
	public function text_to_alias($texto){
		return convert_accented_characters(str_replace(' ', '-', trim(strtolower($texto))));
	}

	/**
	 * Convierte una matriz multidimensional en una simple
	 * @param  array  $array  matriz de entrada
	 * @param  string $field1 
	 * @param  string $field2 
	 * @param  boolean $simple_init  si es TRUE se pone un valor de matriz vacio
	 * @return array          
	 */
	public function array_to_dropdown($array=array(),$field1=NULL,$field2=NULL, $simple_init='Seleccione una opcion')
	{
		$dropdown = array();
		if($simple_init)
			$dropdown[' ']= $simple_init;
		foreach($array as $element) {
			if (!$field2) {
				$dropdown[$element[$field1]] = $element[$field1];
			}else{
				$dropdown[$element[$field1]] = $element[$field2];
			}
		}
		return $dropdown;
	}

	public function check_permissions($id)
	{
		$user =$this->ci->session->userdata();

		if($user['usuario'] == 'admin') return;

		$permissions = $this->ci->generic_model->get_one('permisos','*', array('id'=>$id));

		if(!count($permissions)) redirect('generic/invalid_permissions');

		$roles = $user['roles'];

		if (!count(array_intersect($roles,explode(',',$permissions->roles)))) redirect('generic/invalid_permissions');
	}

	/**
	 * Get and enum field from the database and returns as array
	 * Convierte un campo enum en una matriz para array
	 * @param  string $table database table
	 * @param  string $field  field name from the database
	 * @return array
	 */
	public function enum_select($table, $field)
	{
		$query = "SHOW COLUMNS FROM $table LIKE '$field'";
		$row = $this->ci->db->query("SHOW COLUMNS FROM $table LIKE '$field'")->row()->Type; 
		preg_match_all( "/'(.*?)'/" , $row, $enum_array);
		foreach ($enum_array[1] as $value)
			$out[$value] = $value; 
		return $out;
	}

	/**
	 * Convierte una fecha en formato latin
	 * @param  date $date
	 * @return date
	 */
	public function parse_date($date,$format = '%Y-%m-%d')
	{
		return strftime($format,strtotime($date));
	}

	/**
	 * Adiciona o sustrae determinado tiempo a una fecha inicial
	 * @param  int $count  Cuenta de fechas a modificar
	 * @param  date  $fecha_inicial Fecha de donde partir la manipulacion
	 * @param  string  $type  Tipo de operacion
	 * @return date
	 */
	public function date_manipulation($count=1,$fecha_inicial=NULL,$type='add')
	{
		$date = new DateTime($fecha_inicial);
		if ($type=='add') {
			$date->add(new DateInterval('P'.$count.'D'));
		} else {
			$date->sub(new DateInterval('P'.$count.'D'));
		}		
		return $date->format('Y-m-d');
	}


	/**
	 * Verifica la duplicidad de un campo pero objetando el valor de la fila en si
	 * @param  string $value  Valor del campo
	 * @param  string $params valores separados por punto (valor original, campo en la base de datos, tabla de la base de datos)
	 * @return boolean
	 */
	public function duplicate_check($value,$params)
	{
		$temp = explode('.',$params);
		$original = $temp[0];
		$field = $temp[1];
		$table = $temp[2];
		if ($value != $original) {
			$query = $this->ci->generic_model->get_one($table,$field, array("$field != "=>$original,$field=>$value));
			if ($query) {
				$this->ci->form_validation->set_message('duplicate_check', "El valor <strong>$value</strong> del campo <strong>".ucfirst($field)."</strong> ya se encuentra en la base de datos");
				return FALSE;
			}else{
				return TRUE;
			}
			return FALSE;
		}else{
			return TRUE;
		}
	}

	/**
	 * Send email based on the Codeigniter email library
	 * @param  string $from    email 
	 * @param  string $to      email separated with commas
	 * @param  string $subject subject for the mail
	 * @param  string||html_string $message body for the mail
	 * @param  array $params  extended params
	 * @return boolean          server response status
	 */
	public function send_email($from,$to,$subject,$message=NULL,$params= NULL)
	{
		$self_ci = $this->ci;
		$self_ci->load->library('email');

		if ($self_ci->config->item('use_mailgun')) {
			$config['protocol'] = 'smtp';
			$config['smtp_host'] = $self_ci->config->item('mailgun_smtp_host');
			$config['smtp_user'] = $self_ci->config->item('mailgun_smtp_user');
			$config['smtp_pass'] = $self_ci->config->item('mailgun_smtp_pass');
			$config['smtp_port'] = $self_ci->config->item('mailgun_smtp_port');
			$self_ci->email->initialize($config);
		}

		if (count($params)) {
			foreach ($params as $key => $value) {
				$config[$key] = $value;
			}
		}
		
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;

		$self_ci->email->initialize($config);

		$self_ci->email->from($from['email'] , $from['name']);
		$self_ci->email->to($to);

		$self_ci->email->subject($subject);

		$self_ci->email->message($message);

		return $self_ci->email->send();
	}


	/**
	* Sort a 2 dimensional array based on 1 or more indexes.
	* https://blog.jachim.be/2009/09/php-msort-multidimensional-array-sort/comment-page-1/
	* 
	* msort() can be used to sort a rowset like array on one or more 'headers' (keys in the 2th array).
	* 
	* @param array        $array      The array to sort.
	* @param string|array $key        The index(es) to sort the array on.
	* @param int          $sort_flags The optional parameter to modify the sorting 
	*                                 behavior. This parameter does not work when 
	*                                 supplying an array in the $key parameter. 
	*                                 http://php.net/manual/en/function.sort.php#refsect1-function.sort-parameters
	* 
	* @return array The sorted array.
	*/
	public function msort($array, $key, $sort_flags = SORT_REGULAR) {
		if (is_array($array) && count($array) > 0) {
			if (!empty($key)) {
				$mapping = array();
				foreach ($array as $k => $v) {
					$sort_key = '';
					if (!is_array($key)) {
						$sort_key = $v[$key];
					} else {
						foreach ($key as $key_key) {
							$sort_key .= $v[$key_key];
						}
						$sort_flags = SORT_STRING;
					}
					$mapping[$k] = $sort_key;
				}
				asort($mapping, $sort_flags);
				$sorted = array();
				foreach ($mapping as $k => $v) {
					$sorted[] = $array[$k];
				}
				return $sorted;
			}
		}
		return $array;
	}

}
/* End of file funciones.php */
/* Location: ./application/libraries/funciones.php */