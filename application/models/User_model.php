<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class User_model extends CI_Model {

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
	 * create_user function.
	 * 
	 * @access public
	 * @param string $username
	 * @param string $email
	 * @param string $password
	 * @return bool true on success, false on failure
	 */
	public function create_user($username, $email, $password) {
		
		$data = array(
			'username'   => $username,
			'email'      => $email,
			'password'   => $this->hash_password($password),
			'created_at' => date('Y-m-j H:i:s'),
		);
		
		return $this->db->insert('users', $data);
		
	}
	
	/**
	 * resolve_user_login function.
	 * 
	 * @access public
	 * @param string $username
	 * @param string $password
	 * @return bool true on success, false on failure
	 */
	public function resolve_user_login($username, $password) {
		
		$this->db->select('password');
		$this->db->from('users');
		$this->db->where('username', $username);
		$hash = $this->db->get()->row('password');
		
		return $this->verify_password_hash($password, $hash);
		
	}
	
	/**
	 * get_user_id_from_username function.
	 * 
	 * @access public
	 * @param string $username
	 * @return int the user id
	 */
	public function get_user_id_from_username($username) {
		
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('username', $username);

		return $this->db->get()->row('id');
		
	}
	
	public function get_username_from_user_id($user_id) {
		
		$this->db->select('username');
		$this->db->from('users');
		$this->db->where('id', $user_id);

		return $this->db->get()->row('username');
		
	}
	
	/**
	 * get_user function.
	 * 
	 * @access public
	 * @param int $user_id
	 * @return object the user object
	 */
	public function get_user($user_id) {
		
		$this->db->from('users');
		$this->db->where('id', $user_id);
		return $this->db->get()->row();
		
	}
	
	/**
	 * count_user_posts function.
	 * 
	 * @access public
	 * @param int $user_id
	 * @return int
	 */
	public function count_user_posts($user_id) {
		
		$this->db->select('id');
		$this->db->from('posts');
		$this->db->where('user_id', $user_id);
		return $this->db->get()->num_rows();
		
	}
	
	/**
	 * count_user_topics function.
	 * 
	 * @access public
	 * @param int $user_id
	 * @return int
	 */
	public function count_user_topics($user_id) {
		
		$this->db->select('id');
		$this->db->from('topics');
		$this->db->where('user_id', $user_id);
		return $this->db->get()->num_rows();
		
	}
	
	/**
	 * get_user_last_post function.
	 * 
	 * @access public
	 * @param int $user_id
	 * @return mixed object or false if no post
	 */
	public function get_user_last_post($user_id) {
		
		$this->db->from('posts');
		$this->db->where('user_id', $user_id);
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit(1);
		return $this->db->get()->row();
		
	}
	
	/**
	 * get_user_last_topic function.
	 * 
	 * @access public
	 * @param int $user_id
	 * @return object or false if no topic
	 */
	public function get_user_last_topic($user_id) {
		
		$this->db->from('topics');
		$this->db->where('user_id', $user_id);
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit(1);
		return $this->db->get()->row();
		
	}
	
	/**
	 * hash_password function.
	 * 
	 * @access private
	 * @param string $password
	 * @return string|bool could be a string on success, or bool false on failure
	 */
	private function hash_password($password) {
		
		return password_hash($password, PASSWORD_BCRYPT);
		
	}
	
	/**
	 * verify_password_hash function.
	 * 
	 * @access private
	 * @param string $password
	 * @param string $hash
	 * @return bool
	 */
	private function verify_password_hash($password, $hash) {
		
		return password_verify($password, $hash);
		
	}
	
}
