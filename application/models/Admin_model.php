<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin_model class.
 * 
 * @extends CI_Model
 */
class Admin_model extends CI_Model {

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}
	
	/**
	 * update_user_rights function.
	 * 
	 * @access public
	 * @param int $user_id
	 * @param bool $is_admin
	 * @param bool $is_moderator
	 * @return bool
	 */
	public function update_user_rights($user_id, $is_admin, $is_moderator) {
		
		$data = array(
			'is_admin'     => $is_admin,
			'is_moderator' => $is_moderator,
			'updated_at'   => date('Y-m-j H:i:s'),
			'updated_by'   => $_SESSION['user_id'],
		);
		
		$this->db->where('id', $user_id);
		return $this->db->update('users', $data);
		
	}
	
}
