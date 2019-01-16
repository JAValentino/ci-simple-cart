<?php
	
	class Shop_model extends CI_Model{
		function fetch(){
			$query = $this->db->get("shop");
			return $query->result();
		}
	}
?>