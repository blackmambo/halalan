<?php

class Abstain extends Model {

	function Abstain()
	{
		parent::Model();
	}

	function insert($abstain)
	{
		return $this->db->insert('abstains', $abstain);
	}

	function count_all_by_position_id($position_id)
	{
		$this->db->from('abstains');
		$this->db->where(compact('position_id'));
		$query = $this->db->get();
		return count($query->result_array());
	}

}

?>