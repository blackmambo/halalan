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

class Election extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function insert($election)
	{
		return $this->db->insert('elections', $election);
	}

	public function update($election, $id)
	{
		return $this->db->update('elections', $election, array('id' => $id));
	}

	public function delete($id)
	{
		return $this->db->delete('elections', array('id' => $id));
	}

	public function select($id)
	{
		$this->db->from('elections');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function select_all()
	{
		$this->db->from('elections');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function select_all_by_ids($ids)
	{
		$this->db->from('elections');
		$this->db->where_in('id', $ids);
		$query = $this->db->get();
		return $query->result_array();
	}

	// elections with results should not be running
	public function select_all_with_results()
	{
		$this->db->from('elections');
		$this->db->where('results', TRUE);
		$this->db->where('status', FALSE); // not running
		$query = $this->db->get();
		return $query->result_array();
	}

	public function select_by_election($election)
	{
		$this->db->from('elections');
		$this->db->where('election', $election);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function in_use($election_id)
	{
		$this->db->from('positions');
		$this->db->where('election_id', $election_id);
		$has_positions = $this->db->count_all_results() > 0 ? TRUE : FALSE;
		$this->db->from('parties');
		$this->db->where('election_id', $election_id);
		$has_parties = $this->db->count_all_results() > 0 ? TRUE : FALSE;
		return $has_positions || $has_parties ? TRUE : FALSE;
	}

	public function is_running($id)
	{
		$this->db->from('elections');
		$this->db->where('status', TRUE);
		$this->db->where('id', $id);
		return $this->db->count_all_results() > 0 ? TRUE : FALSE;
	}

	public function are_running($ids)
	{
		$this->db->from('elections');
		$this->db->where('status', TRUE);
		$this->db->where_in('id', $ids);
		return $this->db->count_all_results() > 0 ? TRUE : FALSE;
	}

	public function for_dropdown()
	{
		$this->db->from('elections');
		$this->db->order_by('election', 'ASC');
		$query = $this->db->get();
		$tmp = $query->result_array();
		$elections = array();
		foreach ($tmp as $t)
		{
			$elections[$t['id']] = $t['election'];
		}
		return $elections;
	}

}

/* End of file election.php */
/* Location: ./application/models/election.php */
