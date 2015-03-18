<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User class.
 * 
 * @extends CI_Controller
 */
class User extends CI_Controller {

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('forum_model');
		//$this->output->enable_profiler(TRUE);
		
	}
	
	/**
	 * index function.
	 *
	 * This method is the user profile
	 * 
	 * @access public
	 * @param bool $slug (default: false)
	 * @return void
	 */
	public function index($slug = false) {
		
		// get the slug from the url
		$slug = $this->uri->segment(2, 0);
		
		if ($slug == false) {
			
			// because no slug was passed to the url, we redirect to the website root
			redirect('/');
			return;
			
		}

		// get the user id from the username passed in the url (username = slug)
		$user_id = $this->user_model->get_user_id_from_username($slug);
		
		// get the user object from the database
		$user = $this->user_model->get_user($user_id);
		
		// get the user last 10 posts from the database
		$posts = $this->forum_model->get_user_posts($user_id, 3);
		
		foreach ($posts as $post) {
			
			$topic            = $this->forum_model->get_topic($post->topic_id);
			$forum            = $this->forum_model->get_forum($topic->forum_id);
			$topic->permalink = base_url() . 'forum/' . $forum->slug . '/' . $topic->slug;
			
			$data['posts'][] = (object)[
				'id'        => $post->id,
				'date'      => date("m-d-Y", strtotime($post->date)),
				'permalink' => base_url() . 'forum/' . $forum->slug . '/' . $topic->slug . '/#' . $post->id,
				'topic'     => $topic
			];
			
		}
		
		// get the user last 3 topics from the database
		$topics = $this->forum_model->get_user_topics($user_id, 3);
		
		foreach ($topics as $topic) {
			
			$data['topics'][] = (object)[
				'id'        => $topic->id,
				'title'     => $topic->title,
				'date'      => date("m-d-Y", strtotime($topic->date)),
				'permalink' => base_url() . 'forum/' . $forum->slug . '/' . $topic->slug,
				'forum'     => $this->forum_model->get_forum($topic->forum_id)
			];
			
		}

		// assign values to the user object
		$data['user'] = (object)[
			'id'                  => (int) $user->id,
			'username'            => $user->username,
			'email'               => $user->email,
			'avatar'              => base_url() . 'uploads/avatars/' . $user->avatar,
			'registration_date'   => $this->time_ago_format_date($user->registration_date) . ' ago',
			'is_confirmed'        => (bool) $user->is_confirmed,
			'is_admin'            => (bool) $user->is_admin,
			'permalink'           => base_url() . 'user/' . $user->username,
			'delete_account_link' => base_url() . 'user/delete/' . $user->username,
			'all_posts_link'      => base_url() . 'user/all_posts/' . $user->username,
			'all_topics_link'     => base_url() . 'user/all_topics/' . $user->username,
			'count_posts'         => $this->forum_model->count_user_posts($user_id),
			'count_topics'        => $this->forum_model->count_user_topics($user_id),
			'latest_post'         => $this->user_model->get_user_last_post($user_id),
			'latest_topic'        => $this->user_model->get_user_last_topic($user_id),
		];
		
		// get latest post only if the user has published yet
		if ($data['user']->latest_post !== null) {
			
			$data['user']->latest_post->date        = $this->time_ago_format_date($this->user_model->get_user_last_post($user_id)->date) . ' ago';
			$data['user']->latest_post->topic_title = $this->forum_model->get_topic($data['user']->latest_post->topic_id)->title;
			$data['user']->latest_post->permalink   = 
				base_url()
				. 'forum/'
				. $this->forum_model->get_forum_by_topic_id($data['user']->latest_post->topic_id)->slug
				. '/'
				. $this->forum_model->get_topic($data['user']->latest_post->topic_id)->slug
				. '/#'
				. $data['user']->latest_post->id;
				
			$data['user']->latest_topic->permalink = 
				base_url()
				. 'forum/'
				. $this->forum_model->get_forum_by_topic_id($data['user']->latest_topic->id)->slug // a verifier
				. '/'
				. $this->forum_model->get_topic($data['user']->latest_topic->id)->slug;
				
		}
		
		// the standard min_length for password. See just below to understand.
		$current_password_min_length = 'min_length[6]';
		
		// verify if admin user has changed his password and alert him if not.
		if ($_SESSION['username'] === 'admin') {
			if ($this->user_model->resolve_user_login('admin', 'admin')) {
				$data['admin_must_change_password'] = '<strong>CAUTION!</strong> For security reasons, you have to change your default admin password.<br>Don\'t you think <i>"admin"</i> is a little too weak for an admin user password?<br>Seriously, change it right now or get hacked!';
				// if the password is admin, let the admin, we have to allow admin
				// to enter his current password (that is less than the standard
				// min length 6) before he change it. So we have to fix a custom
				// form validation his current password.
				$current_password_min_length = 'min_length[4]';
			}
		}
		
		// load helpers and libraries for user update profile form
		$this->load->helper(array('form'));
		$this->load->library('form_validation');
		
		// form validation 
		$password_required_if = $this->input->post('profile_password') ? '|required' : '' ; // if there is somthing on password input, current password is required
		$this->form_validation->set_rules('profile_username', 'Username', 'trim|min_length[4]|max_length[20]|alpha_dash|is_unique[users.username]', array('is_unique' => 'This username already exists. Please choose another one.'));
		$this->form_validation->set_rules('profile_email', 'Email', 'trim|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('profile_current_password', 'Current Password', 'trim'. $password_required_if .'|callback_verify_current_password|' . $current_password_min_length . '|max_length[24]');
		$this->form_validation->set_rules('profile_password', 'Password', 'trim|min_length[6]|max_length[24]|matches[profile_password_confirm]');
		$this->form_validation->set_rules('profile_password_confirm', 'Password Confirmation', 'trim|min_length[6]|max_length[24]');
		
		// run the form
		if ($this->form_validation->run() === false) {
		
			// form not ok, show errors to the user
			$this->load->view('header', $data);
			$this->load->view('user/user_profile', $data);
			$this->load->view('footer');
		
		} else {
			
			$user_id = $_SESSION['user_id'];
			$update_data = [];
			
			if ($this->input->post('profile_username') != '') {
				$update_data['username'] = $this->input->post('profile_username');
			}
			if ($this->input->post('profile_email') != '') {
				$update_data['email'] = $this->input->post('profile_email');
			}
			if ($this->input->post('profile_password') != '') {
				$update_data['password'] = $this->input->post('profile_password');
			}
			
			// avatar upload
			if (isset($_FILES['userfile']['name']) && !empty($_FILES['userfile']['name'])) {
				
				// setup upload configuration and load upload library
				$config['upload_path']      = './uploads/avatars/';
				$config['allowed_types']    = 'gif|jpg|png';
				$config['max_size']         = 2048;
				$config['max_width']        = 1024;
				$config['max_height']       = 1024;
				$config['file_ext_tolower'] = true;
				$config['encrypt_name']     = true;			
				$this->load->library('upload', $config);
				
				if (!$this->upload->do_upload()) {
					
					// upload NOT ok
					$error = array('error' => $this->upload->display_errors());
					$this->load->view('upload_form', $error);
				
				} else {
					
					// Upload ok send name to $updated_data
					$update_data['avatar'] = $this->upload->data('file_name');
					
				}
				
			}

			// if the form is ok
			if ($this->user_model->update_user($user_id, $update_data)) {
				
				// if username change, update session
				if(isset($update_data['username'])) {
					$_SESSION['username'] = $update_data['username'];
					$data['user']->permalink = base_url() . 'user/' . $update_data['username'];
				}
				
				// load view
				$data['success'] = 'Your profile has been successfully updated!';
				$this->load->view('header', $data);
				$this->load->view('user/user_profile_update_success', $data);
				$this->load->view('footer');
				
			} else {
				
				// update user not ok : this should never happen
				$data['error'] = 'There was a problem updating your new account. Please try again.';
				$this->load->view('header', $data);
				$this->load->view('user/user_profile', $data);
				$this->load->view('footer');
				
			}
			
		}

	}
	
	/**
	 * register function.
	 * 
	 * @access public
	 * @return void
	 */
	public function register() {
		
		$data = (object)[];
		
		// load helpers and libraries
		$this->load->helper(array('form'));
		$this->load->library('form_validation');
		
		// form validation
		$this->form_validation->set_rules('register_username', 'Username', 'trim|required|min_length[4]|max_length[20]|alpha_dash|is_unique[users.username]', array('is_unique' => 'This username already exists. Please choose another one.'));
		$this->form_validation->set_rules('register_password', 'Password', 'trim|required|min_length[6]|max_length[24]');
		$this->form_validation->set_rules('register_password_confirmation', 'Password Confirmation', 'trim|required|min_length[6]|max_length[24]|matches[register_password]');
		$this->form_validation->set_rules('register_email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		
		// run the form
		if ($this->form_validation->run() === false) {
			
			// form not ok, show errors to the user
			$this->load->view('header', $data);
			$this->load->view('user/user_register', $data);
			$this->load->view('footer');
			
		} else {
			
			$username = $this->input->post('register_username');
			$password = $this->input->post('register_password');
			$email    = $this->input->post('register_email');
			
			// form ok
			if ($this->user_model->insert_user($username, $password, $email)) {
				
				// insert new user ok
				$this->load->view('header', $data);
				$this->load->view('user/user_register_success', $data);
				$this->load->view('footer');
				
			} else {
				
				// insert user not ok : this should never happen
				$data['error'] = 'There was a problem creating your new account. Please try again.';
				$this->load->view('header', $data);
				$this->load->view('user/user_register', $data);
				$this->load->view('footer');
				
			}
			
		}
		
	}
	
	/**
	 * login function.
	 * 
	 * @access public
	 * @return void
	 */
	public function login() {
		
		$data = (object)[];
		
		// load helpers and libraries
		$this->load->helper(array('form'));
		$this->load->library('form_validation');
		
		// form validation
		$this->form_validation->set_rules('login_username', 'username', 'trim|required|alpha_dash');
		$this->form_validation->set_rules('login_password', 'password', 'trim|required');
		
		// run the form
		if ($this->form_validation->run() == false) {
			
			// form not ok, show errors to the user
			$this->load->view('header', $data);
			$this->load->view('user/user_login', $data);
			$this->load->view('footer');
			
		} else {
			
			// form ok
			$username = $this->input->post('login_username');
			$password = $this->input->post('login_password');
			
			
			if ($this->user_model->resolve_user_login($username, $password)) {
				
				$user_id = $this->user_model->get_user_id_from_username($username);
				$user = $this->user_model->get_user($user_id);
				
				// user login ok
				$this->session->set_userdata(array(
					'username'     => (string) $user->username,
					'user_id'      => (int)    $user->id,
					'logged_in'    => (bool)   true,
					'is_confirmed' => (bool)   $user->is_confirmed,
					'is_admin'     => (bool)   $user->is_admin
				));
				
				
				
				// redirect user to index page
				redirect('/');
				return;
				
			} else {
				
				// user login not ok, show errors to the user
				$data->error = 'Wrong username or password.';
				$this->load->view('header', $data);
				$this->load->view('user/user_login', $data);
				$this->load->view('footer');
			
			}

		}
		
	}
	
	/**
	 * logout function.
	 * 
	 * @access public
	 * @return void
	 */
	public function logout() {
		
		$user_datas = $_SESSION;
		
		// really destroy the session
		foreach ($user_datas as $key => $value) {
			$this->session->unset_userdata($key);
		}
		session_destroy();
		
		// redirect user to index page
		redirect('/');
		
	}
	
	public function all_posts($slug) {
		
		// get the slug from the url
		$slug = $this->uri->segment(3, 0);
		
		if ($slug == false) {
			
			// because no slug was passed to the url, we redirect to the website root
			redirect('/');
			return;
			
		}

		// get the user id from the username passed in the url (username = slug)
		$user_id = $this->user_model->get_user_id_from_username($slug);
		
		// get the user object from the database
		$user = $this->user_model->get_user($user_id);
		
		// get the user posts from the database
		$posts = $this->forum_model->get_user_posts($user_id);
		
		foreach ($posts as $post) {
			
			$topic            = $this->forum_model->get_topic($post->topic_id);
			$forum            = $this->forum_model->get_forum($topic->forum_id);
			$topic->permalink = base_url() . 'forum/' . $forum->slug . '/' . $topic->slug;
			
			$data['posts'][] = (object)[
				'id'        => $post->id,
				'date'      => date("m-d-Y", strtotime($post->date)),
				'permalink' => base_url() . 'forum/' . $forum->slug . '/' . $topic->slug . '/#' . $post->id,
				'topic'     => $topic
			];
			
		}
		
		$this->load->view('header', $data);
		$this->load->view('user/user_profile_all_posts', $data);
		$this->load->view('footer', $data);
		
	}
	
	public function all_topics($slug) {
		
		// get the slug from the url
		$slug = $this->uri->segment(3, 0);
		
		if ($slug == false) {
			
			// because no slug was passed to the url, we redirect to the website root
			redirect('/');
			return;
			
		}

		// get the user id from the username passed in the url (username = slug)
		$user_id = $this->user_model->get_user_id_from_username($slug);
		
		// get the user object from the database
		$user = $this->user_model->get_user($user_id);
		
		// get the user posts from the database
		$topics = $this->forum_model->get_user_topics($user_id);
		//var_dump($topics);
		
		foreach ($topics as $topic) {
			
			$forum = $this->forum_model->get_forum($topic->forum_id);
			//var_dump($forum);
			
			$data['topics'][] = (object)[
				'id'        => $topic->id,
				'title'     => $topic->title,
				'slug'      => $topic->slug,
				'date'      => date("m-d-Y", strtotime($topic->date)),
				'permalink' => base_url() . 'forum/' . $forum->slug . '/' . $topic->slug,
				'forum'     => (object)[
					'id' => $forum->id,
					'title' => $forum->title,
					'slug'  => $forum->slug,
					'permalink' => base_url() . 'forum/' . $forum->slug
				]
			];
			
		}
		
		$this->load->view('header', $data);
		$this->load->view('user/user_profile_all_topics', $data);
		$this->load->view('footer', $data);
		
	}
	
	/**
	 * email_validation function.
	 * 
	 * @access public
	 * @param mixed $username
	 * @param mixed $user_email_hash
	 * @return void
	 */
	public function email_validation($username, $user_email_hash) {
		
		// avoid blank at the end of url
		$user_email_hash = trim($user_email_hash);
		
		$data['title'] = 'Email validation';
		
		if ($this->user_model->validate_email($username, $user_email_hash) === true) {
			
			// email and account validation ok
			$data['message'] = 'Congratulation, your email address and your account are now validated! Please <a href="' . base_url() . 'login">Login</a>.';
			$this->load->view('header', $data);
			$this->load->view('user/user_email_validation', $data);
			$this->load->view('footer');
			
		} else {
			
			// email and account validation NOT ok : this should never happen
			$data['message'] = 'An error has occured, your email address and account cannot be validated. Please contact the webesite admin.';
			$this->load->view('header', $data);
			$this->load->view('user/user_email_validation', $data);
			$this->load->view('footer');
			
		}
		
	}
	
	public function delete($slug) {
		
		if (!$slug) {
			redirect('/');
			return;
		}
		
		// if the user is on his own profil page, he has the right to delete his account
		if (isset($_SESSION['username']) && $_SESSION['username'] === $slug && $_SESSION['logged_in'] === true) {
			
			$data = [];
		
			$this->load->view('header', $data);
			$this->load->view('user/user_delete', $data);
			$this->load->view('footer');
			
		}
		
	}
	
	/**
	 * is_logged_in function.
	 * 
	 * @access private
	 * @return bool
	 */
	private function is_logged_in() {
		
		if (isset($_SESSION['username']) && $_SESSION['logged_in'] === true) {
			return true;
		}
		return false;
		
	}
	
	/**
	 * is_confirmed function.
	 * 
	 * @access private
	 * @return bool
	 */
	private function is_confirmed() {
		
		if (isset($_SESSION['username']) && $_SESSION['is_confirmed'] === 1) {
			return true;
		}
		return false;
		
	}
	
	/**
	 * is_admin function.
	 * 
	 * @access private
	 * @return bool
	 */
	private function is_admin() {
		
		if (isset($_SESSION['username']) && $_SESSION['is_admin'] === 1) {
			return true;
		}
		return false;
		
	}
	
	/**
	 * verify_current_password function.
	 *
	 * This methode is a callback function for user profile update
	 * 
	 * @access public
	 * @param mixed $str
	 * @return bool
	 */
	public function verify_current_password($str) {
		
		if ($str != '') {
			
			if ($this->user_model->resolve_user_login($_SESSION['username'], $str) === true) {
				return true;
			}
			$this->form_validation->set_message('verify_current_password', 'The {field} field does not match your password.');
			return false;
			
		}
		return true;
		
	}
	
	/**
	 * time_ago_format_date function.
	 * 
	 * @access private
	 * @param mixed $date
	 * @return string
	 */
	private function time_ago_format_date($date) {
		
		// require timeago library
		require_once(APPPATH . 'libraries/timeago.inc.php');
		
		$timeAgo = new TimeAgo();
		return $timeAgo->inWords($date);
		
	}
	
	public function faker() {
		
		require_once(APPPATH . 'third_party/vendor/autoload.php');
		
		$faker = Faker\Factory::create();
		
/*		
		foreach (range(1, 50) as $x) {

			$username = $password = $faker->userName;
			
			$email = $faker->email;
			
			$this->user_model->insert_user($username, $password, $email);
			
		}
*/
/*
		for ($i=0; $i < 500; $i++) {

			$forum_id  = rand(1, 2);
			$author_id = rand(0, 166);
			$title     = $faker->sentence($nbWords = 6);
			$slug      = url_title($title, 'dash', true);
			$content   = $faker->text($maxNbChars = rand(100, 1000));
			
			
			$this->forum_model->create_topic($forum_id, $author_id, $title, $slug, $content);
			
		}
*/
/*
		for ($i=0; $i < 5000; $i++) {

			$topic_id  = rand(1, 1331);
			$author_id = rand(0, 166);
			$content   = $faker->text($maxNbChars = rand(100, 1000));
			
			
			$this->forum_model->create_post($topic_id, $author_id, $content);
			
		}
		
*/
/*	
		for ($i=0; $i < 500; $i++) {

			$forum_id  = 3;
			$author_id = rand(0, 166);
			$title     = $faker->sentence($nbWords = 6);
			$slug      = url_title($title, 'dash', true);
			$content   = $faker->text($maxNbChars = rand(100, 1000));
			
			
			$this->forum_model->create_topic($forum_id, $author_id, $title, $slug, $content);
			
		}
*/
/*		
		for ($i=0; $i < 5000; $i++) {

			$topic_id  = rand(1833, 2332);
			$author_id = rand(0, 166);
			$content   = $faker->text($maxNbChars = rand(100, 1000));
			
			
			$this->forum_model->create_post($topic_id, $author_id, $content);
			
		}
*/	
	}

	
}
