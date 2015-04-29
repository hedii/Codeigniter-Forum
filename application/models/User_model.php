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
		
		if ($this->db->insert('users', $data)) {
			
			//send confirmation email
			return $this->send_confirmation_email($username, $email);
			
		}
		
	}
	
	/**
	 * create_admin_user function.
	 * 
	 * @access public
	 * @param string $username
	 * @param string $email
	 * @param string $password
	 * @return bool
	 */
	public function create_admin_user($username, $email, $password) {
		
		$data = array(
			'username'     => $username,
			'email'        => $email,
			'password'     => $this->hash_password($password),
			'created_at'   => date('Y-m-j H:i:s'),
			'is_admin'     => '1',
			'is_confirmed' => '1',
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
	
	/**
	 * get_username_from_user_id function.
	 * 
	 * @access public
	 * @param int $user_id
	 * @return string
	 */
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
	 * get_users function.
	 * 
	 * @access public
	 * @return object
	 */
	public function get_users() {
		
		$this->db->from('users');
		return $this->db->get()->result();
		
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
	 * confirm_account function.
	 * 
	 * @access public
	 * @param string $username
	 * @param string $hash
	 * @return bool
	 */
	public function confirm_account($username, $hash) {
		
		// find the email for the given user
		$email = $this->db->select('email')
			->from('users')
			->where('username', $username)
			->get()
			->row('email');
		
		// find the registration date for the given user
		$registration_date = $this->db->select('created_at')
			->from('users')
			->where('username', $username)
			->get()
			->row('created_at');

		// if the user from the url exists
		if ($email && $registration_date) {
			
			if (sha1($email . $registration_date) === $hash) {
				
				// values from the url are good, we can validate the account
				$data = array('is_confirmed' => '1');
				$this->db->where('username', $username);
				return $this->db->update('users', $data);
				
			}
			return false;
			
		}
		return false;
		
	}
	
	/**
	 * update_user function.
	 * 
	 * @access public
	 * @param int $user_id
	 * @param array $update_data
	 * @return bool
	 */
	public function update_user($user_id, $update_data) {
		
		// if user wants to update its password, hash the given password
		if (array_key_exists('password', $update_data)) {
			$update_data['password'] = $this->hash_password($update_data['password']);
		}
		
		if (!empty($update_data)) {
			
			$this->db->where('id', $user_id);
			return $this->db->update('users', $update_data);
			
		}
		return false;
		
	}
	
	/**
	 * delete_user function.
	 * 
	 * @access public
	 * @param int $user_id
	 * @return bool
	 */
	public function delete_user($user_id) {
		
		// delete all user topics, posts and delete user account
		$this->db->where('id', $user_id);
		if ($this->db->delete('users')) {
			$this->db->where('user_id', $user_id);
			if ($this->db->delete('topics')) {
				$this->db->where('user_id', $user_id);
				return $this->db->delete('posts');
			}
			return false;
		}
		return false;
		
		/* OLD
		$data = array('is_deleted' => '1');
		$this->db->where('id', $user_id);
		return $this->db->update('users', $data);
		*/
		
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
	
	/**
	 * send_confirmation_email function.
	 * 
	 * @access private
	 * @param string $username
	 * @param string $email
	 * @return bool
	 */
	private function send_confirmation_email($username, $email) {
		
		// load email library and url helper
		$this->load->library('email');
		$this->load->helper('url');
		
		// get the site email address
		$email_address = $this->config->item('site_email');
		
		// initialize the email configuration
		$this->email->initialize(array(
			'mailtype' => 'html',
			'charset'  => 'utf-8'
		));
		
		// get user registration date
		$registration_date = $this->db->select('created_at')->from('users')->where('username', $username)->get()->row('created_at');
		
		// create a user email hash with user email and user registration date
		$hash = sha1($email . $registration_date);
		
		// prepare the email
		$this->email->from($email_address, $email_address);
		$this->email->to($email);
		$this->email->subject('Please confirm your email to validate your new user account.');
		$message  = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>';
		$message .= "Hi " . $username . ",<br><br>";
		$message .= "Please click the link below to confirm your account on " . base_url() . "<br><br>";
		$message .= "Click this link: <a href=\"" . base_url() . "user/email_validation/" . $username . "/" . $hash . "\">Confirm your email and validate your account</a>";
		$message .= "</body></html>";
		$this->email->message($message);
		
		// send the email and return status
		return $this->email->send();
		
	}
	
}
