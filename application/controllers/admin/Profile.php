<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

	/**
	 * Formulario para la edicion del perfil del usuario registrado en el sistema
	 * @return HTML
	 */
	public function index()
	{
		$sess = $this->session->all_userdata();
		
		$sexo = $this->functions->enum_select('usuarios','sexo');
		$carnet_expedido = $this->functions->array_to_dropdown($this->generic_model->get('carnet_expedido','*',NULL,NULL,NULL,TRUE),'id','expedido');

		$datos = $this->generic_model->get_one('usuarios','*',array('id'=>$sess['id']));

		if(!count($datos))
			redirect('admin/permisos');
		
		$this->load->library('form');

		$this->form
		->open(current_url())
		->html('<div class="row"><div class="col-md-6">')
		->text('nombres','Nombres','trim|required', $datos->nombres)
		->text('ap_paterno','Apellido paterno','trim|required',$datos->ap_paterno)
		->text('ap_materno','Apellido materno','trim',$datos->ap_materno)
		->text('ci','Carnet de identidad','trim|numeric|required|callback_duplicate_check['.$datos->ci.'.ci.usuarios]',$datos->ci)
		->select('carnet_expedido_id', $carnet_expedido, 'Carnet expedido en',$datos->carnet_expedido_id,'required|trim')
		->text('usuario','Usuario','trim|required|callback_duplicate_check['.$datos->usuario.'.usuario.usuarios]',$datos->usuario)
		->password('password','Password','min_length[6]')
		->iupload('imagen', 'Fotografia','',array('upload_path'=>'./assets/uploads/usuarios/','file_name'=>'usuario_ci'))
		->html('</div><div class="col-md-6">')
		->text('fecha_nac','Fecha de nacimiento','trim|required',$this->functions->parse_date($datos->fecha_nac),'class="date-picker"')
		->text('email','Email','trim|required|valid_email',$datos->email)
		->text('celular','Celular','trim|numeric',$datos->celular)
		->text('telefono','Telefono','trim|numeric',$datos->telefono)
		->text('zona','Zona','trim',$datos->zona)
		->textarea('direccion','Direccion','trim',$datos->direccion,'rows="5"')
		->select('sexo', $sexo, 'Sexo',$datos->sexo)
		->html('</div></div>')
		->submit('Guardar','guardar','class="btn btn-primary btn-large"');

		$data['form'] = $this->form->get();
		$data['errors'] = $this->form->errors;

		$data['titulo_formulario'] = 'Editar perfil';
		
		if ($this->form->valid) {
			$post_vars = $this->form->get_post();

			$valores= array(
				'nombres' =>  $post_vars['nombres'],
				'ap_paterno' =>  $post_vars['ap_paterno'],
				'ap_materno' =>  $post_vars['ap_materno'],
				'ci' =>  $post_vars['ci'],
				'carnet_expedido_id' =>  $post_vars['carnet_expedido_id'][0],
				'usuario' =>  $post_vars['usuario'],
				'fecha_nac' =>  $post_vars['fecha_nac'],
				'email' =>  $post_vars['email'],
				'celular' =>  $post_vars['celular'],
				'telefono' =>  $post_vars['telefono'],
				'zona' =>  $post_vars['zona'],
				'direccion' =>  $post_vars['direccion'],
				'sexo' =>  $post_vars['sexo'][0],
				);
			if ($post_vars['password']) 
				$valores['password']=password_hash($post_vars['password'],PASSWORD_DEFAULT);
			if ($post_vars['imagen']) 
				$valores['imagen']=$post_vars['imagen']['file_name'];

			$this->generic_model->edit('usuarios',$valores,'id',$sess['id']);
			$data['edited'] = true;
		}

		$this->template->write('title', 'Editar perfil');
		$this->template->write_view('header', 'admin/partials/header', $data, TRUE);
		$this->template->write_view('sidebar', 'admin/partials/sidebar');
		$this->template->write_view('content', 'admin/profile/main');
		$this->template->write('scripts', "$('.date-picker').datepicker(". $this->config->item('datepicker_base_options').");");
		$this->template->render();
	}

	/**
	 * Verifica la duplicidad del valor en base de datos, ademas verifica que no se pueda modificar de la base de datos el usuario 'admin'
	 * @param  string $value  valor a ser verificado
	 * @param  string $params parametros separados por coma
	 * @return boolean
	 */
	public function duplicate_check($value,$params)
	{
		$temp = explode('.',$params);
		if ($temp[0] != 'admin') {
			return $this->functions->duplicate_check($value,$params);
		}else if($value !=$temp[0]){
			$this->form_validation->set_message('duplicate_check', "No se puede modificar el nombre de usuario <strong>admin</strong>");
			return FALSE;
		}else{
			return TRUE;
		}
	}

}

/* End of file profile.php */
/* Location: ./application/controllers/admin/profile.php */