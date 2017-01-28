<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Generic_model extends CI_Model {

	/**
	 * Get data from the database based on one or many filters
	 * @param string $table name of the table on the database
	 * @param  string $fields fields separated with commas to be extracted from the database
	 *                        		could be included special* database operators (COUNT(), CONCAT_WS(), etc)
	 *                        		* check the compatibility between database sources
	 * @param  array $where  where filter to be applied to the query
	 * @param  boolean $single   if is TRUE return only one record instead of many
	 * @param  array  $order_by order by query to be applied on the queried records 
	 * @param  int  $perpage  superior limit
	 * @param  integer $start    initial limit
	 * @return array || object            returned data based on the $single param
	 */
	public function get($table,$fields,$where=NULL,$single=FALSE,$order_by=NULL,$perpage=null,$start=0){
		$this->db->select($fields);
		$this->db->from($table);

		if (is_array($where))
			$this->db->where($where);

		if (is_array($order_by))
			$this->db->order_by($order_by[0],$order_by[1]);

		if($perpage)
			$this->db->limit($perpage,$start);

		$query = $this->db->get();

		$result = ($single) ? $query->result_row() : $query->result_array();
		return $result;
	}

	/**
	 * Get one record from a table on the database
	 * @param string $table name of the table on the database
	 * @param  string $fields fields separated with commas to be extracted from the database
	 *                        		could be included special* database operators (COUNT(), CONCAT_WS(), etc)
	 *                        		* check the compatibility between database sources
	 * @param  array $where  where filter to be applied to the query
	 * @return object
	 */
	public function get_one($table,$fields,$where=NULL){
		$this->db->select($fields)->from($table);

		if (is_array($where))
			$this->db->where($where);
		
		$query = $this->db->get();
		return $query->row();
	}

	/**
	 * Add data to an specific table on the database
	 * @param string $table name of the table on the database
	 * @param array $data  data to be inserted
	 * @return boolean          return the status of the query
	 */
	public function add($table,$data){
		$this->db->insert($table, $data);

		if ($this->db->affected_rows() == '1')
			return TRUE;
		return FALSE; 
	}

	/**
	 * Edit one record from the database in one table
	 * @param string $table name of the table on the database
	 * @param  array $data    data to be edited
	 * @param  string $fieldID table field name ('id','uid',etc)
	 * @param  string $ID      value to search for the $fieldID
	 * @return boolean          return the status of the query
	 */
	public function edit($table,$data,$fieldID,$ID){
		$this->db->where($fieldID,$ID)->update($table, $data);

		if ($this->db->affected_rows() >= 0)
			return TRUE;
		return FALSE;       
	}

	/**
	 * Delete the data from one table
	 * @param  string $table   database table name
	 * @param  string $fieldID table field name ('id','uid',etc)
	 * @param  string $ID      value to search for the $fieldID
	 * @return boolean          return the status of the query
	 */
	public function delete($table,$fieldID,$ID){
		$this->db->where($fieldID,$ID)->delete($table);
		
		if ($this->db->affected_rows() == '1')
			return TRUE;
		return FALSE;
	}

	/**
	 * Count the data on one table
	 * @param  string $table database table name
	 * @return int        qty of the records
	 */
	public function count($table){
		return $this->db->count_all($table);
	}

	public function run_sql($sql, $data = TRUE){
		$query = $this->db->query($sql);

		if ($this->db->affected_rows() == '1')
			return ($data) ?  $query->result_array() : TRUE;
		return FALSE;
	}
	
}

/* End of file general.php */
/* Location: ./application/models/general.php */
