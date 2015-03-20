<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	protected $admin;

	public function __construct()
	{
		parent::__construct();

		// check for installer
		if (file_exists(APPPATH . 'controllers/install.php'))
		{
			$this->load->helper('url');
			redirect('install');
		}

		// autoload
		$this->load->library(array('form_validation', 'session'));
		$this->load->helper(array('form', 'halalan', 'password', 'url'));
		//$this->load->language('halalan');
		$this->load->model(array('Abmin', 'Event'));

		// get the current class
		$class = get_class($this);

		// check if signed in
		if ($class != 'Gate')
		{
			$this->admin = $this->session->userdata('admin');
			if ( ! $this->admin)
			{
				show_error('Forbidden', 403);
			}
		}
	}

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */