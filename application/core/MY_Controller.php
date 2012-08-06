<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Copyright (C) 2012 University of the Philippines Linux Users' Group
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

class MY_Controller extends CI_controller {

	private $module;

	public function __construct()
	{
		parent::__construct();
		if ($this->uri->segment(1) == 'admin') // admin side
		{
			if ($this->session->userdata('type') != 'admin')
			{
				$this->session->set_flashdata('messages', array('negative', e('common_unauthorized')));
				redirect('gate/admin');
			}
		}
		else if ($this->uri->segment(1) == 'voter') // voter side
		{
			if ($this->session->userdata('type') != 'voter')
			{
				$this->session->set_flashdata('messages', array('negative', e('common_unauthorized')));
				redirect('gate/voter');
			}
		}
	}

	public function set_module($module)
	{
		$this->module = $module;
	}

	public function get_module()
	{
		return $this->module;
	}

	// used by admin to send email
	public function _send_email($voter, $password, $pin)
	{
		$email = $this->session->userdata('email');
		$admin = $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name');
		$data['voter'] = $voter;
		$data['password'] = $password;
		$data['pin'] = $pin;
		$data['admin'] = $admin;
		$message = $this->load->view('admin/_email', $data, TRUE);

		$this->email->clear();
		$this->email->from($email, $admin);
		$this->email->to($voter['username']);
		$this->email->subject('Halalan Login Credentials');
		$this->email->message($message);
		$this->email->send();
		//echo $this->email->print_debugger();
	}

	// additional form validation rules
	public function _rule_running_election()
	{
		if ($this->module == 'block')
		{
			if ($this->Election->are_running($this->input->post('chosen_elections')))
			{
				$this->form_validation->set_message('_rule_running_election', e('admin_block_running_election'));
				return FALSE;
			}
			// additional check since an election may have no positions yet
			if ( ! $this->input->post('general_positions') && ! $this->input->post('chosen_positions'))
			{
				$this->form_validation->set_message('_rule_running_election', e('admin_block_no_positions'));
				return FALSE;
			}
		}
		else if ($this->module == 'voter')
		{
			if ($this->Block->in_running_election($this->input->post('block_id')))
			{
				$this->form_validation->set_message('_rule_running_election', e('admin_voter_running_election'));
				return FALSE;
			}
		}
		else
		{
			if ($this->Election->is_running($this->input->post('election_id')))
			{
				$this->form_validation->set_message('_rule_running_election', e('admin_' . $this->module . '_running_election'));
				return FALSE;
			}
		}
		return TRUE;
	}

	public function _rule_is_existing($str, $table_fields)
	{
		// modified is_unique rule
		list($table, $fields) = explode('.', $table_fields);
		$fields = explode(',', $fields);
		$where = array();
		foreach ($fields as $field)
		{
			$where[$field] = $this->input->post($field, TRUE);
		}
		$query = $this->db->limit(1)->get_where($table, $where);
		$test = $query->row_array();
		if ( ! empty($test))
		{
			$error = FALSE;
			if ($data = $this->session->userdata($this->module)) // check when in edit mode
			{
				if ($test['id'] != $data['id'])
				{
					$error = TRUE;
				}
			}
			else
			{
				$error = TRUE;
			}
			if ($error)
			{
				$value = $test[$fields[0]];
				if ($this->module == 'candidate')
				{
					$value = $test[$fields[0]] . ', ' . $test[$fields[1]];
					if ( ! empty($test[$fields[2]]))
					{
						$value .= ' "' . $test[$fields[2]] . '"';
					}
				}
				$message = e('admin_' . $this->module . '_exists') . ' (' . $value . ')';
				$this->form_validation->set_message('_rule_is_existing', $message);
				return FALSE;
			}
		}
		return TRUE;
	}

	public function _rule_dependencies()
	{
		if ($test = $this->session->userdata($this->module)) // check when in edit mode
		{
			if ($this->module == 'block')
			{
				// don't check if no elections or positions are selected since we already have a rule for them
				if ( ! $this->input->post('chosen_elections'))
				{
					return TRUE;
				}
				// don't check if elections and positions do not change
				$chosen_elections = array();
				$chosen_positions = array();
				$tmp = $this->Block_Election_Position->select_all_by_block_id($test['id']);
				foreach ($tmp as $t)
				{
					$chosen_elections[] = $t['election_id'];
					$chosen_positions[] = $t['election_id'] . '|' . $t['position_id'];
				}
				$chosen_elections = array_unique($chosen_elections);
				$fill = $this->_fill_positions($chosen_elections);
				$general_positions = array();
				foreach ($fill[0] as $f)
				{
					$general_positions[] = $f['value'];
				}
				$tmp = FALSE; // not array() since $this->input->post returns FALSE when empty
				foreach ($chosen_positions as $c)
				{
					// remove from $chosen_positions the general positions
					if ( ! in_array($c, $general_positions))
					{
						$tmp[] = $c;
					}
				}
				$chosen_positions = $tmp;
				if ($chosen_elections == $this->input->post('chosen_elections') && $general_positions == $this->input->post('general_positions') && $chosen_positions == $this->input->post('chosen_positions'))
				{
					return TRUE;
				}
			}
			else if ($this->module == 'voter')
			{
				// don't check if no block is selected since we already have a rule for this
				if ( ! $this->input->post('block_id'))
				{
					return TRUE;
				}
				// don't check if block does not change
				if ($test['block_id'] == $this->input->post('block_id'))
				{
					return TRUE;
				}
			}
			else if ($this->module == 'candidate')
			{
				// don't check if no election or position is selected since we already have a rule for them
				if ( ! $this->input->post('election_id') OR ! $this->input->post('position_id'))
				{
					return TRUE;
				}
				// don't check if election and position do not change
				if ($test['election_id'] == $this->input->post('election_id') && $test['position_id'] == $this->input->post('position_id'))
				{
					return TRUE;
				}
			}
			else
			{
				// don't check if no election is selected since we already have a rule for this
				if ( ! $this->input->post('election_id'))
				{
					return TRUE;
				}
				// don't check if election does not change
				if ($test['election_id'] == $this->input->post('election_id'))
				{
					return TRUE;
				}
			}
			$model = ucfirst($this->module);
			if ($model == 'Voter')
			{
				$model = 'Boter';
			}
			if ($this->$model->in_use($test['id']))
			{
				$this->form_validation->set_message('_rule_dependencies', e('admin_' . $this->module . '_dependencies'));
				return FALSE;
			}
		}
		return TRUE;
	}

	public function _rule_upload_csv()
	{
		$config['upload_path'] = HALALAN_UPLOAD_PATH . 'csvs/';
		$config['allowed_types'] = 'csv';
		$this->upload->initialize($config);
		if ( ! $this->upload->do_upload('csv'))
		{
			$message = $this->upload->display_errors('', '');
			$this->form_validation->set_message('_rule_upload_csv', $message);
			return FALSE;
		}
		else
		{
			$upload_data = $this->upload->data();
			$this->session->set_userdata('csv_upload_data', $upload_data);
			return TRUE;
		}
	}

	public function _rule_upload_image()
	{
		$config['allowed_types'] = HALALAN_ALLOWED_TYPES;
		$config['encrypt_name'] = TRUE;
		if ($this->module == 'party')
		{
			$config['upload_path'] = HALALAN_UPLOAD_PATH . 'logos/';
			$type = 'logo';
			$size = HALALAN_LOGO_SIZE;
		}
		else if ($this->module == 'candidate')
		{
			$config['upload_path'] = HALALAN_UPLOAD_PATH . 'pictures/';
			$type = 'picture';
			$size = HALALAN_PICTURE_SIZE;
		}
		if ($_FILES[$type]['error'] != UPLOAD_ERR_NO_FILE)
		{
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload($type))
			{
				$message = $this->upload->display_errors('', '');
				$this->form_validation->set_message('_rule_upload_image', $message);
				return FALSE;
			}
			else
			{
				$upload_data = $this->upload->data();
				$return = $this->_resize($upload_data, $size);
				if ($return != 'OKS')
				{
					unlink($upload_data['full_path']);
					$this->form_validation->set_message('_rule_upload_image', $return);
					return FALSE;
				}
				else
				{
					if ($test = $this->session->userdata($this->module)) // edit
					{
						// delete old image first
						unlink($config['upload_path'] . $test[$type]);
					}
					$this->session->set_userdata('image_upload_data', $upload_data['file_name']);
					return TRUE;
				}
			}
		}
		return TRUE;
	}

	// used in _rule_dependencies and blocks controller
	public function _fill_positions($election_ids)
	{
		$general = array();
		$specific = array();
		foreach ($election_ids as $election_id)
		{
			$positions = $this->Position->select_all_by_election_id($election_id);
			foreach ($positions as $position)
			{
				$value = $election_id . '|' . $position['id'];
				$text = $position['position'] . ' (' . $election_id . ')';
				if ($position['unit'])
				{
					$specific[] = array('value' => $value, 'text' => $text);
				}
				else
				{
					$general[] = array('value' => $value, 'text' => $text);
				}
			}
		}
		return array($general, $specific);
	}

	// used in _rule_upload_image
	public function _resize($upload_data, $n)
	{
		$error = array();
		$width = $upload_data['image_width'];
		$height = $upload_data['image_height'];
		if ($width > $n OR $height > $n)
		{
			$config['source_image'] = $upload_data['full_path'];
			$config['quality'] = '100%';
			$config['width'] = $n;
			$config['height'] = ($n * $height) / $width;
			$this->image_lib->initialize($config);
			if ( ! $this->image_lib->resize())
			{
				return $this->image_lib->display_errors();
			}
		}
		return 'OKS';
	}

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */