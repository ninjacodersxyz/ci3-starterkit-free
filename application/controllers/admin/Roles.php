<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if( !$this->session->userdata('logged') )
			redirect('/login');
	}

	/**
	 * Listado de roles existentes en el sistema
	 * @return HTML
	 */
	public function index()
	{
		$this->load->library('table');

		$roles = $this->generic_model->get('roles','rol,descripcion,status, id');

		$data['new']=($this->session->userdata('new'))? true : false;
		$data['edit']=($this->session->userdata('edit'))? true : false;

		$this->table->set_template($this->config->item('table_template'));
		$this->table->set_heading('Rol', 'Descripcion', 'Status', 'Operaciones');
		$data['tabla'] = $this->table->generate($roles);

		$this->template->write('title', 'Listado de roles');
		$this->template->write_view('header', 'admin/partials/header', $data, TRUE);
		$this->template->write_view('sidebar', 'admin/partials/sidebar');
		$this->template->write_view('content', 'admin/roles/roles_list');
		$this->template->render();
	}

	/**
	 * Pagina para adicionar roles en la base de datos
	 * @return HTML
	 */
	public function add()
	{
		$sexo = $this->functions->enum_select('usuarios','sexo');
		$status =array(0=>'Inhabilitado', 1=>'Habilitado');
		
		$this->load->library('form');

		$this->form
		->open(current_url())
		->text('rol','Rol','trim|required')
		->textarea('descripcion','Descripcion','trim',NULL,'rows="5"')
		->select('status', $status, 'Estado')
		->submit('Guardar','guardar','class="btn btn-primary btn-large"');

		$data['form'] = $this->form->get();
		$data['errors'] = $this->form->errors;

		$data['titulo_formulario'] = 'Agregar Rol';
		
		if ($this->form->valid) {
			$post_vars = $this->form->get_post();
			$valores= array(
				'rol'=>$post_vars['rol'],
				'descripcion'=>$post_vars['descripcion'],
				'status'=>$post_vars['status'][0]
				);
			$this->generic_model->add('roles',$valores);
			$this->session->set_flashdata('new',TRUE);
			redirect('admin/roles');
		}

		$this->template->write('title', 'Administrar Rol');
		$this->template->write_view('header', 'admin/partials/header', $data, TRUE);
		$this->template->write_view('sidebar', 'admin/partials/sidebar');
		$this->template->write_view('content', 'admin/partials/base_form');
		$this->template->render();
	}

	/**
	 * Pagina para editar el rol
	 * @param  string $id identificador unico del registro en la base de datos
	 * @return HTML
	 */
	public function edit($id)
	{
		$sexo = $this->functions->enum_select('usuarios','sexo');
		$status =array(0=>'Inhabilitado', 1=>'Habilitado');

		$datos = $this->generic_model->get_one('roles','*',array('id'=>$id));

		if(!count($datos))
			redirect('admin/permisos');
		
		$this->load->library('form');

		$this->form
		->open(current_url())
		->text('rol','Rol','trim|required',$datos->rol)
		->textarea('descripcion','Descripcion','trim',$datos->descripcion,'rows="5"')
		->select('status', $status, 'Estado',$datos->status)
		->submit('Guardar','guardar','class="btn btn-primary btn-large"');

		$data['form'] = $this->form->get();
		$data['errors'] = $this->form->errors;

		$data['titulo_formulario'] = 'Editar Rol';
		
		if ($this->form->valid) {
			$post_vars = $this->form->get_post();
			$valores= array(
				'rol'=>$post_vars['rol'],
				'descripcion'=>$post_vars['descripcion'],
				'status'=>$post_vars['status'][0]
				);
			$this->generic_model->edit('roles',$valores,'id',$id);
			$this->session->set_flashdata('edit',TRUE);
			redirect('admin/roles');
		}

		$this->template->write('title', 'Administrar Rol');
		$this->template->write_view('header', 'admin/partials/header', $data, TRUE);
		$this->template->write_view('sidebar', 'admin/partials/sidebar');
		$this->template->write_view('content', 'admin/partials/base_form');
		$this->template->render();
	}

	/**
	 * Administracion de usuarios del Rol
	 * @param  string $id identificador unico del rol
	 * @return HTML
	 */
	public function users($id)
	{
		$this->load->helper('form');
		$data['rol'] = $this->generic_model->get_one('roles','*',array('id'=>$id));
		
		if (!count($data['rol'])) redirect('admin/roles');

		$usuarios_roles =$this->generic_model->get('usuarios_roles','*',array('rol_id'=>$id));
		$usuarios_roles =(count($usuarios_roles))?  $this->functions->array_to_dropdown($usuarios_roles,'usuario_id',NULL,FALSE) : array();

		$usuarios = $this->functions->array_to_dropdown($this->generic_model->get('usuarios','id, CONCAT_WS(" ",nombres,ap_paterno,ap_materno) as nombre', array('usuario !='=>'admin') ,0,array('id','ASC')),'id','nombre',FALSE);

		$data['inhabilitados'] = array();
		$data['habilitados'] = array();

		foreach ($usuarios as $k1 => $v1) {
			if (in_array($k1, $usuarios_roles)) {
				$data['habilitados'][$k1] = $v1;
			}else{
				$data['inhabilitados'][$k1] = $v1;
			}
		}

		$this->template->write('title', 'Administrar Usuarios del Rol');
		$this->template->write_view('header', 'admin/partials/header', $data, TRUE);
		$this->template->write_view('sidebar', 'admin/partials/sidebar');
		$this->template->write_view('content', 'admin/roles/usuarios_roles');
		$this->template->render();
	}

	/**
	 * Funcion AJAX para eliminar el rol
	 * @param string $id identificador unico del rol
	 * @return string respuesta del proceso
	 */
	public function ajax_delete_role()
	{
		$id = $this->input->post_get('id', TRUE);
		if ($this->generic_model->delete('roles','id',$id)) {
			echo "Rol eliminado correctamente";
		}else{
			echo "Ocurrio un error interno";
		}
	}

	/**
	 * Funcion AJAX para administrar a los usuarios asignados al rol
	 * @param string $id identificador unico del rol
	 * @param string $usuarios cadena separada por comas con todos los usuarios que se agregan al rol
	 * @return void
	 */
	public function ajax_manage_users_roles()
	{
		$id = $this->input->post_get('id', TRUE);
		$usuarios = array_filter(explode(',',$this->input->post_get('usuarios', TRUE)), function($value) { 
			return $value !== ''; 
		});
		$this->generic_model->delete('usuarios_roles','rol_id',$id);
		foreach ($usuarios as $value) {
			$this->generic_model->add('usuarios_roles',array('usuario_id'=>$value, 'rol_id'=>$id));
		}
		echo 1;
	}
	
}

/* End of file roles.php */
/* Location: ./application/controllers/admin/roles.php */