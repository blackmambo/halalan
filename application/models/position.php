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

class Position extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function insert($position)
	{
		return $this->db->insert('positions', $position);
	}

	public function update($position, $id)
	{
		return $this->db->update('positions', $position, array('id' => $id));
	}

	public function delete($id)
	{
		$this->db->where('position_id', $id);
		$this->db->delete('blocks_elections_positions');
		$this->db->where('id', $id);
		return $this->db->delete('positions');
	}

	public function select($id)
	{
		$this->db->from('positions');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function select_all()
	{
		$this->db->from('positions');
		$this->db->order_by('ordinality', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function select_all_by_ids($ids)
	{
		$this->db->from('positions');
		$this->db->where_in('id', $ids);
		$this->db->order_by('ordinality', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function select_all_by_election_id($election_id)
	{
		$this->db->from('positions');
		$this->db->where('election_id', $election_id);
		$this->db->order_by('ordinality', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function in_use($position_id)
	{
		$this->db->from('candidates');
		$this->db->where('position_id', $position_id);
		$has_candidates = $this->db->count_all_results() > 0 ? TRUE : FALSE;
		$this->db->from('blocks_elections_positions');
		$this->db->where('position_id', $position_id);
		$has_blocks = $this->db->count_all_results() > 0 ? TRUE : FALSE;
		return $has_candidates || $has_blocks ? TRUE : FALSE;
	}

	public function in_running_election($id)
	{
		$this->db->from('positions');
		$this->db->where('id', $id);
		$this->db->where('election_id IN (SELECT id FROM elections WHERE status = 1)');
		return $this->db->count_all_results() > 0 ? TRUE : FALSE;
	}

	public function for_dropdown($election_id)
	{
		$this->db->from('positions');
		$this->db->where('election_id', $election_id);
		$this->db->order_by('ordinality', 'ASC');
		$query = $this->db->get();
		$tmp = $query->result_array();
		$positions = array();
		foreach ($tmp as $t)
		{
			$positions[$t['id']] = $t['position'];
		}
		return $positions;
	}

}

/* End of file position.php */
/* Location: ./application/models/position.php */
