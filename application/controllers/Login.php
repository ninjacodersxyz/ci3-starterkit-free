<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Pagina principal del ingreso al sistema
	 * @return HTML
	 */
	public function index()
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation
		->set_rules('usuario', 'Usuario', 'required')
		->set_rules('password', 'Password', 'required');

		$data['title'] = "Ingreso al sistema";

		$failed = $this->generic_model->get_one('ingreso_fallidos','*',array('ip'=>$this->input->ip_address()));
		$now =new DateTime();
		if ($failed) {
			$date = new DateTime($failed->date);
			$temp = 'PT'.$this->config->item('blocking_time').'M';
			$date = $date->add(new DateInterval($temp));
			if($failed->count ==$this->config->item('blocking_tries') && ($date >= $now)){
				$interval =  $now->diff($date);
				$data['blocked'] = $interval->format('%i');
			}
		}

		if ($this->form_validation->run()) {
			$post_values = $this->input->post(null,TRUE);
			$query = $this->generic_model->get_one('usuarios','id,usuario,password,nombres,ap_paterno,ap_materno', array('usuario' =>$post_values['usuario'],'estado'=>1),1);

			if ($query && password_verify($post_values['password'] ,$query->password)) {

				$roles = $this->generic_model->get('usuarios_roles','rol_id',array('usuario_id'=>$query->id));
				$roles = (count($roles)) ?  array_values($this->functions->array_to_dropdown($roles,'rol_id',NULL,false)) : array();

				$sesion= array('id'=>$query->id,'usuario'=>$query->usuario,'logged'=> 1,'nombre'=>$query->nombres.' '.$query->ap_paterno.' '.$query->ap_materno,'roles'=>$roles);
				$this->session->set_userdata($sesion);
				$this->generic_model->add('ingreso_sistema',array('usuario_id'=>$query->id, 'fecha_ingreso'=> date("Y-m-d H:i:s")));
				redirect('admin/dashboard');
			}else{
				if ($failed) {
					$date = new DateTime($failed->date);
					$date = $date->add(new DateInterval('PT'.$this->config->item('blocking_time').'M'));
					$interval =  $now->diff($date);
					if ($now >= $date) {
						$this->generic_model->edit('ingreso_fallidos',array('count'=>1,'date'=>$now->format('Y-m-d H:i:s')),'ip',$failed->ip);
					}elseif ($failed->count <$this->config->item('blocking_tries') && ($date >= $now)) {
						$failed->count++;
						$this->generic_model->edit('ingreso_fallidos',array('ip'=>$failed->ip,'count'=>$failed->count,'date'=>$now->format('Y-m-d H:i:s')),'ip',$this->input->ip_address());
						if ($failed->count == 3) {
							$data['blocked'] = $interval->format('%i');
						}
					}else{
						$data['blocked'] = $interval->format('%i');
					}
				}else{
					$this->generic_model->add('ingreso_fallidos',array('ip'=>$this->input->ip_address(),'count'=>1, 'date'=>$now->format('Y-m-d H:i:s') ));
				}
				$data['mensaje']='Su usuario no existe o su contraseña es erronea!';
			}
		}

		$this->template->set_template('simple');
		$this->template->write('title', 'Ingreso al sistema');
		$this->template->write_view('content', 'admin/login/login', $data, TRUE);
		$this->template->write('scripts', "centerOnMid('.new-well', window);");
		$this->template->render();
	}

	/**
	 * Pagina de solicitud de reestablecimiento de contrasenas 
	 * @return HTML
	 */
	public function forget_password()
	{

		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = 'Reestablezca su contraseña';

		$this->form_validation->set_rules('email', 'Correo', 'required|valid_email');

		if ($this->form_validation->run()) {
			$post_values = $this->input->post(null,TRUE);
			$email = $post_values['email'];
			$query= $this->generic_model->get_one('usuarios','id,email',array('email'=>$email));

			if (count($query)) {

				$this->generic_model->delete('usuarios_reiniciar','user_id',$query->id);
				$token = bin2hex(openssl_random_pseudo_bytes(16));
				$this->generic_model->add('usuarios_reiniciar',array('user_id'=>$query->id,'token'=>$token,'ip'=>$this->input->ip_address()));
				$subject = 'Ingrese en el siguiente enlace para reestablecer su contraseña: <a href="'.base_url().'login/reset_password/'.$token.'">REESTABLECER</a>';
				$send_email = $this->functions->send_email(array('email'=>'webmaster@example.com','name'=>'Webmaster'), $query->email,'Reestablecer contraseña',$subject,array('mailtype'=>'html'));

				if ($send_email == true) $data['message'] = TRUE;
				else $data['error'] = TRUE;

			}else{
				$data['message'] = TRUE;
			}
		}

		$this->template->set_template('simple');
		$this->template->write('title', 'Recuperar contraseña');
		$this->template->write_view('content', 'admin/login/forget_password', $data, TRUE);
		$this->template->write('scripts', "centerOnMid('.new-well', window);");
		$this->template->render();
	}


	/**
	 * Pagina para reiniciar la contraseña del usuario
	 * @param  string $token token unico para reestablecer la contraseña
	 * @return HTML
	 */
	public function reset_password($token)
	{
		$this->load->library('form');

		$this->form
		->open(current_url())
		->password('password','Contraseña','trim|required|min_length[6]')
		->password('confirm','Confirmar contraseña','trim|matches[password]')
		->submit('Guardar','guardar','class="btn btn-primary btn-lg btn-block"');

		$data['form'] = $this->form->get();
		$data['errors'] = $this->form->errors;

		$data['title'] = 'Ingrese una nueva contraseña';

		$token_data = $this->generic_model->get_one('usuarios_reiniciar','*',array('token'=>$token));

		if (count($token_data)) {
			$now =new DateTime();
			$date = new DateTime($token_data->created_at);
			$date = $date->add(new DateInterval('PT'.$this->config->item('reset_password_token_time').'M'));
			if($date < $now || $token_data->used =='1'){
				$data['invalid'] = true;
			}
		}else{
			$data['invalid'] = true;
		}

		if ($this->form->valid) {
			$post_vars = $this->form->get_post();

			$new_password = password_hash($post_vars['password'],PASSWORD_DEFAULT);

			$this->generic_model->edit('usuarios_reiniciar',array('used'=>1),'token',$token);
			$this->generic_model->edit('usuarios',array('password'=>$new_password),'id',$token_data->user_id);

			$data['reset'] = true;
		}

		$this->template->set_template('simple');
		$this->template->write('title', 'Recuperar contraseña');
		$this->template->write_view('content', 'admin/login/reset_password', $data, TRUE);
		$this->template->write('scripts', "centerOnMid('.new-well', window);");
		$this->template->render();

	}

	/**
	 * Destruccion de la sesion y salida del sistema
	 * @return void
	 */
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login','refresh');
	}

}

/* End of file login.php */
/* Location: ./application/controllers/admin/login.php */