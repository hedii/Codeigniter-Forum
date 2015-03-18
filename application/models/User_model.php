<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class User_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * get_user function.
	 *
	 * This function return all users into an object if no user id is given.
	 * If a user id is given, it returns this single user object. 
	 * 
	 * @access public
	 * @param bool $user_id (default: false)
	 * @return object
	 */
	public function get_user($user_id = false) {
		
		if ($user_id === false) {
			return $this->db->get('users')->result();
		} else {
			$this->db->from('users');
			$this->db->where('id', $user_id);
			return $this->db->get()->row();
		}

	}
	
	/**
	 * get_user_id_from_username function.
	 *
	 * Given a username, return the user id from the database.
	 * 
	 * @access public
	 * @param mixed $username
	 * @return string
	 */
	public function get_user_id_from_username($username) {
		
		$this->db->select('id');
		$this->db->where('username', $username);
		return $this->db->get('users')->row('id');
		
	}
	
	/**
	 * get_username_from_user_id function.
	 * 
	 * @access public
	 * @param mixed $user_id
	 * @return void
	 */
	public function get_username_from_user_id($user_id) {
		
		$this->db->select('username');
		$this->db->where('id', $user_id);
		return $this->db->get('users')->row('username');
		
	}
	
	/**
	 * get_user_last_post function.
	 * 
	 * @access public
	 * @param mixed $user_id
	 * @return object
	 */
	public function get_user_last_post($user_id) {
		
		$this->db->from('posts');
		$this->db->where('author_id', $user_id);
		$this->db->order_by('date', 'DESC');
		$this->db->limit(1);
		return $this->db->get()->row();
		
	}
	
	/**
	 * get_user_last_post function.
	 * 
	 * @access public
	 * @param mixed $user_id
	 * @return object
	 */
	public function get_user_last_topic($user_id) {
		
		$this->db->from('topics');
		$this->db->where('author_id', $user_id);
		$this->db->order_by('date', 'DESC');
		$this->db->limit(1);
		return $this->db->get()->row();
		
	}
	
	/**
	 * insert_user function.
	 * 
	 * Insert a new user into the database, and fire the send_validation_email
	 * function. If anything goes wrong, it returns false.
	 * 
	 * @access public
	 * @param mixed $username
	 * @param mixed $email
	 * @param mixed $password
	 * @return bool
	 */
	public function insert_user($username, $password, $email) {
		
		$data = array(
			'username' => $username,
			'email'    => $email,
			'password' => $this->hash_password($password),
		);
		
		if ($this->db->insert('users', $data)) {
			$this->send_validation_email($username, $email);
			return true;
		}
		return false;
		
	}
	

	/**
	 * update_user function.
	 * 
	 * @access public
	 * @param mixed $user_id
	 * @param mixed $update_data
	 * @return bool
	 */
	public function update_user($user_id, $update_data) {

		var_dump($update_data);

		// if user wants to update its password, hash the given password
		if (array_key_exists('password', $update_data)) {
			$update_data['password'] = $this->hash_password($update_data['password']);
		}
/*		
		$data = array(
			'username'     => $username,
			'email'        => $email,
			'password'     => $this->hash_password($password),
			'avatar_uri'   => $avatar_uri
		);
*/
		if (!empty($update_data)) {
			$this->db->where('id', $user_id);
			if ($this->db->update('users', $update_data)) {
				return true;
			}
			return false;
		}
		return false;
	}
	
	/**
	 * delete_user function.
	 *
	 * Given a user id, delete this user and return true if the user is deleted.
	 * 
	 * @access public
	 * @param int $user_id
	 * @return bool
	 */
	public function delete_user($user_id) {
		
		if ($this->db->delete('users', array('id' => $user_id))) {
			return true;
		}
		return false;
		
	}
	
	/**
	 * resolve_user_login function.
	 * 
	 * @access public
	 * @param mixed $username
	 * @param mixed $password
	 * @return bool
	 */
	public function resolve_user_login($username, $password) {
		
		$this->db->select('password');
		$this->db->where('username', $username);
		$query = $this->db->get('users');
		
		// initialize $hash
		$hash = '';
		
		if ($query->num_rows() > 0) {
			$result = $query->result_array();
			$hash = $result[0]['password'];
		}
		
		if ($this->verify_password_hash($password, $hash) === true) {
			return true;
		}
		return false;
		
	}
	
	/**
	 * validate_email function.
	 * 
	 * @access public
	 * @param mixed $username
	 * @param mixed $user_email_hash
	 * @return bool
	 */
	public function validate_email($username, $user_email_hash) {
		
		$sql = "SELECT username, email, registration_date FROM users WHERE username='$username'";
		$query = $this->db->query($sql);
		$row = $query->row();
		var_dump($row);
		
		if ($query->num_rows() === 1) {
			
			if (sha1($row->email . $row->registration_date) === $user_email_hash) {
				
				// confirm account in database : update 'confirmed' to true (1)
				$data = array('is_confirmed' => 1);
				$this->db->where('username', $username);
				$this->db->update('users', $data);
				return true;
				
			} else {
				
				return false; // account not confirmed : this should never happen
				
			}
			
		} else {
			
			return false; // account doesn't exists : this should never happen
			
		}
		
	}
	
	/**
	 * hash_password function.
	 *
	 * Hash a given password using the php default function password_hash.
	 * 
	 * @access private
	 * @param mixed $password
	 * @return bool
	 */
	private function hash_password($password) {
		
		return password_hash($password, PASSWORD_BCRYPT);
		
	}
	
	/**
	 * verify_password_hash function.
	 *
	 * Verify that the password match the given hash, using php default
	 * function password_verify
	 * 
	 * @access private
	 * @param mixed $password
	 * @param mixed $hash
	 * @return bool
	 */
	private function verify_password_hash($password, $hash) {
		
		return password_verify($password, $hash);
		
	}
	
	/**
	 * send_validation_email function.
	 *
	 * This function creates a unique hash key from a given username and email,
	 * and send an email containing an email confirmation link to confirm the
	 * user account. If anything goes wrong, it returns false.
	 * 
	 * @access private
	 * @param mixed $username
	 * @param mixed $email
	 * @return bool
	 */
	private function send_validation_email($username, $email) {
		
		// load email library and initialize email conf
		$this->load->library('email');
		$this->email->initialize(array(
			'mailtype' => 'html',
			'charset'  => 'utf-8'
		));
		
		// create a user email hash with user_email and registration_date
		$this->db->select('registration_date');
		$this->db->where('username', $username);
		$user_registration_date = $this->db->get('users')->row('registration_date');
		$user_email_hash = sha1($email . $user_registration_date);
		
		// prepare the email
		$this->email->from('contact@youtube-mp4.fr', 'contact@youtube-mp4.fr');
		$this->email->to($email);
		$this->email->subject("Please confirm your account on " . base_url());
		$message  = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>';
		$message .= "Hi " . $username . ",<br><br>";
		$message .= "Please click the link below to confirm your account on " . base_url() . "<br><br>";
		$message .= "Click this link: <a href=\"" . base_url() . "user/email_validation/" . $username . "/" . $user_email_hash . "\">Confirm your email and validate your account</a>";
		$message .= "</body></html>";
		$this->email->message($message);
		
		// send the email
		if ($this->email->send()) {
			return true;
		}
		return false;
		
	}

}