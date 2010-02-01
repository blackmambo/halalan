<?php

class Position extends Model {

	function Position()
	{
		parent::Model();
	}

	function insert($position)
	{
		$chosen = $position['chosen'];
		unset($position['chosen']);
		$this->db->insert('positions', $position);
		if (!empty($chosen))
		{
			$position_id = $this->db->insert_id();
			foreach ($chosen as $election_id)
			{
				$this->db->insert('elections_positions', compact('election_id', 'position_id'));
			}
		}
		return true;
	}

	function update($position, $id)
	{
		$chosen = $position['chosen'];
		unset($position['chosen']);
		$this->db->update('positions', $position, compact('id'));
		if (!empty($chosen))
		{
			$this->db->where('position_id', $id);
			$this->db->delete('elections_positions');
			$position_id = $id;
			foreach ($chosen as $election_id)
			{
				$this->db->insert('elections_positions', compact('election_id', 'position_id'));
			}
		}
		return true;
	}

	function delete($id)
	{
		$this->db->where('position_id', $id);
		$this->db->delete('elections_positions');
		$this->db->where('position_id', $id);
		$this->db->delete('positions_voters');
		$this->db->where(compact('id'));
		return $this->db->delete('positions');
	}

	function select($id)
	{
		$this->db->from('positions');
		$this->db->where(compact('id'));
		$query = $this->db->get();
		return $query->row_array();
	}

	function select_multiple($ids)
	{
		$this->db->from('positions');
		$this->db->where_in('id', $ids);
		$this->db->order_by('ordinality ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	function select_all()
	{
		$this->db->from('positions');
		$this->db->order_by('ordinality ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	function select_all_by_ids($ids)
	{
		$this->db->from('positions');
		$this->db->where_in('id', $ids);
		$this->db->order_by('ordinality ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	function select_all_by_election_id($election_id)
	{
		$this->db->from('positions');
		$this->db->join('elections_positions', 'positions.id = elections_positions.position_id');
		$this->db->where('election_id', $election_id);
		$this->db->order_by('ordinality ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	function select_all_with_units($voter_id)
	{
		$this->db->from('positions');
		$this->db->join('positions_voters', 'positions.id = positions_voters.position_id', 'left');
		$this->db->where(array('positions.unit'=>'0'));
		$this->db->or_where(array('positions_voters.voter_id'=>$voter_id));
		$this->db->order_by('ordinality ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	function select_all_non_units()
	{
		$this->db->from('positions');
		$this->db->where(array('unit'=>'0'));
		$this->db->order_by('ordinality ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	function select_all_units()
	{
		$this->db->from('positions');
		$this->db->where(array('unit'=>'1'));
		$this->db->order_by('ordinality ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	function select_by_position($position)
	{
		$this->db->from('positions');
		$this->db->where(compact('position'));
		$query = $this->db->get();
		return $query->row_array();
	}

	function in_use($position_id)
	{
		$this->db->from('candidates');
		$this->db->where(compact('position_id'));
		return ($this->db->count_all_results() > 0) ? TRUE : FALSE;
	}

}

?>
