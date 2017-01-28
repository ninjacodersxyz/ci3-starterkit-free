<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permisos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if( !$this->session->userdata('logged') )
			redirect('/login');
	}

	/**
	 * Listado de todos los permisos disponibles en el sistema
	 * @return HTML
	 */
	public function index()
	{
		$this->load->library('table');

		$temp = $this->generic_model->get('permisos','*');
		
		foreach ($temp as &$value) {
			$value['roles'] = explode(',',$value['roles']);
		}

		$data['permisos'] = $temp;
		$data['roles'] = $this->generic_model->get('roles','*');
		$data['roles_array'] = $this->functions->array_to_dropdown($data['roles'],'id','rol',FALSE);

		$data['new']=($this->session->userdata('new'))? true : false;
		$data['edit']=($this->session->userdata('edit'))? true : false;

		$this->template->write('title', 'Listado de permisos');
		$this->template->write_view('header', 'admin/partials/header', $data, TRUE);
		$this->template->write_view('sidebar', 'admin/partials/sidebar');
		$this->template->write_view('content', 'admin/permisos/permisos_list');
		$this->template->render();
	}

	/**
	 * Pagina que permite agregar un permiso en el sistema
	 * @return HTML
	 */
	public function add()
	{
		$this->load->library('form');

		$this->form
		->open(current_url())
		->text('clase','Clase','trim|required')
		->text('funcion','Funcion','trim|required')
		->textarea('descripcion','Descripcion','trim',NULL,'rows="5"')
		->submit('Guardar','guardar','class="btn btn-primary btn-large"');

		$data['form'] = $this->form->get();
		$data['errors'] = $this->form->errors;

		$data['titulo_formulario'] = 'Agregar permiso';
		
		if ($this->form->valid) {
			$post_vars = $this->form->get_post();
			$valores= array(
				'clase' => $post_vars['clase'],
				'funcion' => $post_vars['funcion'],
				'descripcion' => $post_vars['descripcion']
				);
			$this->generic_model->add('permisos',$valores);
			$this->session->set_flashdata('new',TRUE);
			redirect('admin/permisos');
		}

		$this->template->write('title', 'Administrar permiso');
		$this->template->write_view('header', 'admin/partials/header', $data, TRUE);
		$this->template->write_view('sidebar', 'admin/partials/sidebar');
		$this->template->write_view('content', 'admin/partials/base_form');
		$this->template->render();
	}

	/**
	 * Pagina para editar los permisos
	 * @param  string $id identificador unico del registro en la base de datos
	 * @return HTML
	 */
	public function edit($id)
	{
		$this->load->library('form');

		$datos = $this->generic_model->get_one('permisos','*',array('id'=>$id));

		if(!count($datos))
			redirect('admin/permisos');

		$this->form
		->open(current_url())
		->text('clase','Clase','trim|required',$datos->clase)
		->text('funcion','Funcion','trim|required',$datos->funcion)
		->textarea('descripcion','Descripcion','trim',$datos->descripcion,'rows="5"')
		->submit('Guardar','guardar','class="btn btn-primary btn-large"');

		$data['form'] = $this->form->get();
		$data['errors'] = $this->form->errors;

		$data['titulo_formulario'] = 'Editar permiso';
		
		if ($this->form->valid) {
			$post_vars = $this->form->get_post();
			$valores= array(
				'clase' => $post_vars['clase'],
				'funcion' => $post_vars['funcion'],
				'descripcion' => $post_vars['descripcion']
				);
			$this->generic_model->edit('permisos',$valores,'id',$id);
			$this->session->set_flashdata('edit',TRUE);
			redirect('admin/permisos');
		}

		$this->template->write('title', 'Administrar permiso');
		$this->template->write_view('header', 'admin/partials/header', $data, TRUE);
		$this->template->write_view('sidebar', 'admin/partials/sidebar');
		$this->template->write_view('content', 'admin/partials/base_form');
		$this->template->render();
	}

	/**
	 * Funcion Ajax para modificar los permisos
	 * @param string $rol_id identificador unico del rol
	 * @param string $permiso_id identificador del oermiso
	 * @param string $satus nuevo estado para el rol
	 *                      		     se agrega o se quita de los permisos habilitados para el rol
	 * @return string estado de respuesta del servidor
	 */
	public function ajax_modify_permiso()
	{
		$rol_id = $this->input->post_get('rol', TRUE);
		$permiso_id = $this->input->post_get('permiso', TRUE);
		$status = intval($this->input->post_get('status', TRUE));

		$permiso = $this->generic_model->get_one('permisos','*',array('id'=>$permiso_id));
		$temp = array_filter(explode(',',$permiso->roles), function($value) { 
			return $value !== '';
		});

		$in_array_key = array_search($rol_id,$temp);
		if ($status) {
			if ($in_array_key === false)
				$temp[]= $rol_id;
		}else{
			if ($in_array_key !== false)
				unset($temp[$in_array_key]);
		}

		sort($temp);
		$permiso ->roles = implode(',',$temp);
		$edit = $this->generic_model->edit('permisos',$permiso,'id',$permiso_id);

		if ($edit) {
			echo 1;
		}else{
			http_response_code(400);
			echo "Ocurrio un error, intentelo nuevamente mas tarde";
		}
	}

	/**
	 * Funcion AJAX para eliminar el permiso
	 * @param string $id identificador unico del permiso
	 * @return string || integer respuesta del servidor
	 */
	public function ajax_delete_permiso()
	{
		$id = $this->input->post_get('id', TRUE);
		if ($this->generic_model->delete('permisos','id',$id)) {
			echo 1;
		}else{
			http_response_code(400);
			echo "Ocurrio un error interno";
		}
	}

}

/* End of file permisos.php */
/* Location: ./application/controllers/admin/permisos.php */