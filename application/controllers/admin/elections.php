<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Copyright (C) 2006-2012 University of the Philippines Linux Users' Group
 *
 * This file is part of Halalan.
 *
 * Halalan is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Halalan is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Halalan.  If not, see <http://www.gnu.org/licenses/>.
 */

class Elections extends CI_Controller {

	public $admin;
	public $settings;

	public function __construct()
	{
		parent::__construct();
		$this->admin = $this->session->userdata('admin');
		if ( ! $this->admin)
		{
			$this->session->set_flashdata('messages', array('negative', e('common_unauthorized')));
			redirect('gate/admin');
		}
		$this->settings = $this->config->item('halalan');
	}
	
	public function index()
	{
		$data['elections'] = $this->Election->select_all();
		$admin['username'] = $this->admin['username'];
		$admin['title'] = e('admin_elections_title');
		$admin['body'] = $this->load->view('admin/elections', $data, TRUE);
		$this->load->view('admin', $admin);
	}

	public function add()
	{
		$this->_election('add');
	}

	public function edit($id)
	{
		$this->_election('edit', $id);
	}

	public function delete($id) 
	{
		if ( ! $id)
		{
			redirect('admin/elections');
		}
		$election = $this->Election->select($id);
		if ( ! $election)
		{
			redirect('admin/elections');
		}
		if ($election['status'])
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_delete_election_running')));
		}
		else if ($this->Election->in_use($id))
		{
			$this->session->set_flashdata('messages', array('negative', e('admin_delete_election_in_use')));
		}
		else
		{
			$this->Election->delete($id);
			$this->session->set_flashdata('messages', array('positive', e('admin_delete_election_success')));
		}
		redirect('admin/elections');
	}

	public function options($case, $id)
	{
		if ($case == 'status' || $case == 'results')
		{
			$election = $this->Election->select($id);
			if ($election)
			{
				$data = array();
				if ($case == 'status')
				{
					$data['status'] = ! $election['status'];
				}
				else
				{
					$data['results'] = ! $election['results'];
				}
				$this->Election->update($data, $id);
				$this->session->set_flashdata('messages', array('positive', e('admin_options_election_success')));
			}
		}
		redirect('admin/elections');
	}

	public function _election($case, $id = null)
	{
		if ($case == 'add')
		{
			$data['election'] = array('election' => '', 'description' => '');
		}
		else if ($case == 'edit')
		{
			if ( ! $id)
			{
				redirect('admin/elections');
			}
			$data['election'] = $this->Election->select($id);
			if ( ! $data['election'])
			{
				redirect('admin/elections');
			}
			if ($data['election']['status'])
			{
				$this->session->set_flashdata('messages', array('negative', e('admin_edit_election_running')));
				redirect('admin/elections');
			}
		}
		$this->form_validation->set_rules('election', e('admin_election_election'), 'required');
		$this->form_validation->set_rules('description', e('admin_election_description'));
		if ($this->form_validation->run())
		{
			$election['election'] = $this->input->post('election', TRUE);
			$election['description'] = $this->input->post('description', TRUE);
			if ($case == 'add')
			{
				$this->Election->insert($election);
				$this->session->set_flashdata('messages', array('positive', e('admin_add_election_success')));
				redirect('admin/elections/add');
			}
			else if ($case == 'edit')
			{
				$this->Election->update($election, $id);
				$this->session->set_flashdata('messages', array('positive', e('admin_edit_election_success')));
				redirect('admin/elections/edit/' . $id);
			}
		}
		$data['action'] = $case;
		$admin['title'] = e('admin_' . $case . '_election_title');
		$admin['body'] = $this->load->view('admin/election', $data, TRUE);
		$admin['username'] = $this->admin['username'];
		$this->load->view('admin', $admin);
	}

}

/* End of file elections.php */
/* Location: ./application/controllers/admin/elections.php */
