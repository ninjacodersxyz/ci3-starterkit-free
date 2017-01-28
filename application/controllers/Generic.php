<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generic extends CI_Controller {


	/**
	 * Pagina de error 404
	 * @return HTML
	 */
	public function page_404()
	{
		$this->template->set_template('simple');
		$this->template->write('title', '404 - Pagina no encontrada');
		$this->template->write_view('content', 'admin/partials/page_404');
		$this->template->write('scripts', "centerOnMid('.new-well', window);");
		$this->template->render();
	}

}

/* End of file Generic.php */
/* Location: ./application/controllers/Generic.php */