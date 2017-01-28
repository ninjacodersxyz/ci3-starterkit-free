<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if( !$this->session->userdata('logged') )
			redirect('/login');
	}

	/**
	 * Listado de usuarios registrados en el sistema
	 * @return HTML
	 */
	public function index()
	{
		$this->load->library('table');

		$usuarios = $this->generic_model->get('usuarios','nombres, ap_paterno, ap_materno, ci, usuario, email, id');

		$data['new']=($this->session->flashdata('new'))? TRUE : FALSE;
		$data['edit']=($this->session->flashdata('edit'))? TRUE : FALSE;

		$this->table->set_template($this->config->item('table_template'));
		$this->table->set_heading('Nombres', 'Apellido paterno', 'Apellido materno', 'Carnet de identidad', 'Usuario', 'Email','Operaciones');
		$data['tabla'] = $this->table->generate($usuarios);

		$this->template->write('title', 'Listado de usuarios');
		$this->template->write_view('header', 'admin/partials/header', $data, TRUE);
		$this->template->write_view('sidebar', 'admin/partials/sidebar');
		$this->template->write_view('content', 'admin/usuarios/usuarios_list');
		$this->template->render();
	}

	/**
	 * Pagina para adicionar usuarios al sistema
	 * @return HTML
	 */
	public function add()
	{
		$sexo = $this->functions->enum_select('usuarios','sexo');
		$carnet_expedido = $this->functions->array_to_dropdown($this->generic_model->get('carnet_expedido','*',NULL,NULL,NULL,TRUE),'id','expedido');
		
		$this->load->library('form');

		$this->form
		->open(current_url())
		->html('<div class="row"><div class="col-md-6">')
		->text('nombres','Nombres','trim|required')
		->text('ap_paterno','Apellido paterno','trim|required')
		->text('ap_materno','Apellido materno','trim')
		->text('ci','Carnet de identidad','trim|numeric|required|is_unique[usuarios.ci]')
		->select('carnet_expedido_id', $carnet_expedido, 'Carnet expedido en',NULL,'required|trim')
		->text('usuario','Usuario','trim|required|is_unique[usuarios.usuario]')
		->password('password','Password','trim|required|min_length[6]')
		->iupload('imagen', 'Fotografia','',array('upload_path'=>'./assets/uploads/usuarios/','file_name'=>'usuario_ci'))
		->html('</div><div class="col-md-6">')
		->text('fecha_nac','Fecha de nacimiento','trim|required',NULL,array('class'=>'date-picker','readonly'=>'readonly'))
		->text('email','Email','trim|required|valid_email')
		->text('celular','Celular','trim|numeric')
		->text('telefono','Telefono','trim|numeric')
		->text('zona','Zona','trim')
		->textarea('direccion','Direccion','trim',NULL,'rows="5"')
		->select('sexo', $sexo, 'Sexo')
		->html('</div></div>')
		->submit('Guardar','guardar','class="btn btn-primary btn-large"');

		$data['form'] = $this->form->get();
		$data['errors'] = $this->form->errors;

		$data['titulo_formulario'] = 'Agregar usuario';
		
		if ($this->form->valid) {
			$post_vars = $this->form->get_post();

			$valores= array(
				'nombres' =>  $post_vars['nombres'],
				'ap_paterno' =>  $post_vars['ap_paterno'],
				'ap_materno' =>  $post_vars['ap_materno'],
				'ci' =>  $post_vars['ci'],
				'carnet_expedido_id' =>  $post_vars['carnet_expedido_id'][0],
				'usuario' =>  $post_vars['usuario'],
				'password' =>  password_hash($post_vars['password'],PASSWORD_DEFAULT),
				'imagen' =>  $post_vars['imagen']['file_name'],
				'fecha_nac' => $post_vars['fecha_nac'],
				'email' =>  $post_vars['email'],
				'celular' =>  $post_vars['celular'],
				'telefono' =>  $post_vars['telefono'],
				'zona' =>  $post_vars['zona'],
				'direccion' =>  $post_vars['direccion'],
				'sexo' =>  $post_vars['sexo'][0],
				'estado'=>1
				);
			$this->generic_model->add('usuarios',$valores);
			$this->session->set_flashdata('new',TRUE);
			redirect('admin/usuarios');
		}

		$this->template->write('title', 'Administrar usuario');
		$this->template->write_view('header', 'admin/partials/header', $data, TRUE);
		$this->template->write_view('sidebar', 'admin/partials/sidebar');
		$this->template->write_view('content', 'admin/partials/base_form');
		$this->template->write('scripts', "$('.date-picker').datepicker(". $this->config->item('datepicker_base_options').");");
		$this->template->render();
	}

	/**
	 * Pagina de edicion del usuario
	 * @param  string $id identificador unico del rol
	 * @return HTML
	 */
	public function edit($id)
	{
		$sexo = $this->functions->enum_select('usuarios','sexo');
		$carnet_expedido = $this->functions->array_to_dropdown($this->generic_model->get('carnet_expedido','*',NULL,NULL,NULL,TRUE),'id','expedido');

		$datos = $this->generic_model->get_one('usuarios','*',array('id'=>$id));

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

		$data['titulo_formulario'] = 'Editar usuario';
		
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
			
			$this->generic_model->edit('usuarios',$valores,'id',$id);
			$this->session->set_flashdata('edit',TRUE);
			redirect('admin/usuarios');
		}

		$this->template->write('title', 'Administrar usuario');
		$this->template->write_view('header', 'admin/partials/header', $data, TRUE);
		$this->template->write_view('sidebar', 'admin/partials/sidebar');
		$this->template->write_view('content', 'admin/partials/base_form');
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

	/**
	 * Funcion AJAX para eliminar el usuario
	 * @param string $id identificador unico del usuario
	 * @return string respuesta del proceso
	 */
	public function ajax_delete_usuario()
	{
		$id = $this->input->post_get('id', TRUE);
		$data = $this->generic_model->get_one('usuarios','usuario',array('id'=>$id));
		if ($data->usuario != 'admin') {
			if ($this->generic_model->delete('usuarios','id',$id)) {
				echo "Usuario '".$data->usuario."' eliminado correctamente";
			}else{
				echo "Ocurrio un error interno";
			}
		}else{
			http_response_code(400);
			echo "No se puede eliminar el usuario 'admin'";
		}
	}

}

/* End of file usuarios.php */
/* Location: ./application/controllers/admin/usuarios.php */
