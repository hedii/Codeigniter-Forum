<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Forum class.
 * 
 * @extends CI_Controller
 */
class Install extends CI_Controller {

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		parent::__construct();
		$this->load->model('install_model');
		$this->load->helper(array('form', 'cookie', 'url'));
		$this->load->library(array('form_validation', 'session'));
		
	}
	
	/**
	 * index function.
	 * 
	 * @access public
	 * @return void
	 */
	public function index() {
		
		$data = (object)[];
		
		// form validation
		$this->form_validation->set_rules('install_db_hostname', 'Hostname', 'trim|required');
		$this->form_validation->set_rules('install_db_username', 'Username', 'trim|required');
		$this->form_validation->set_rules('install_db_password', 'Password', 'trim|required');
		
		if ($this->form_validation->run() === false) {
		
			// form not ok, show errors to the user
			$this->load->view('header', $data);
			$this->load->view('install/install_index', $data);
			$this->load->view('footer', $data);
		
		} else {
			
			$hostname = $this->input->post('install_db_hostname');
			$username = $this->input->post('install_db_username');
			$password = $this->input->post('install_db_password');
		
			// replace hostname in the database.php config file
			$find    = "'hostname' =>";
			$replace = "\t" . "'hostname' => '" . $hostname . "'," . "\n";
			if ($this->install_model->edit_database_config_file($find, $replace) !== true) {
				
				$data->error = 'The hostname on your database config file cannot be replaced...';
				$this->load->view('header', $data);
				$this->load->view('install/install_index', $data);
				$this->load->view('footer', $data);
				return;
				
			}
			
			// replace username in the database.php config file
			$find    = "'username' =>";
			$replace = "\t" . "'username' => '" . $username . "'," . "\n";
			if ($this->install_model->edit_database_config_file($find, $replace) !== true) {
				
				$data->error = 'The username on your database config file cannot be replaced...';
				$this->load->view('header', $data);
				$this->load->view('install/install_index', $data);
				$this->load->view('footer', $data);
				return;
				
			}
			
			// replace password in the database.php config file
			$find    = "'password' =>";
			$replace = "\t" . "'password' => '" . $password . "'," . "\n";
			if ($this->install_model->edit_database_config_file($find, $replace) !== true) {
				
				$data->error = 'The password on your database config file cannot be replaced...';
				$this->load->view('header', $data);
				$this->load->view('install/install_index', $data);
				$this->load->view('footer', $data);
				return;
				
			}
			
			// test the database connection with these new values
			if ($this->install_model->test_database_connexion($hostname, $username, $password) === true) {
				
				redirect('install/database_creation');
				
			} else {
				
				// database connection failed, the user must enter right values
				$data->error = 'Could not connect to MySQL with values you entered. Please enter valid MySQL hostname, username and password.';
				
				// reset the database.php config file
				$find    = "'hostname' =>";
				$replace = "\t" . "'hostname' => 'localhost'," . "\n";
				$this->install_model->edit_database_config_file($find, $replace);
				$find    = "'username' =>";
				$replace = "\t" . "'username' => ''," . "\n";
				$this->install_model->edit_database_config_file($find, $replace);
				$find    = "'password' =>";
				$replace = "\t" . "'password' => ''," . "\n";
				$this->install_model->edit_database_config_file($find, $replace);
				
				// reset variable values that where defined before
				$hostname = null;
				$username = null;
				$password = null;
				
				// send errors to the view
				$this->load->view('header', $data);
				$this->load->view('install/install_index', $data);
				$this->load->view('footer', $data);
				return;
			}
		
		}
		
	}
	
	/**
	 * database_creation function.
	 * 
	 * @access public
	 * @return void
	 */
	public function database_creation() {
		
		$data = (object)[];
		
		// form validation
		$this->form_validation->set_rules('database_name', 'Database name', 'trim|required|alpha_numeric|max_length[64]');
		
		if ($this->form_validation->run() === false) {
		
			// form not ok, show errors to the user
			$this->load->view('header', $data);
			$this->load->view('install/install_database_creation', $data);
			$this->load->view('footer');
		
		} else {
		
			$database_name = $this->input->post('database_name');
			setcookie('db_name', $database_name);
			
			if ($this->install_model->create_database($database_name) === true) {
				
				// database creation ok, go to next install step
				redirect('install/tables_creation');
				
			} else {
				
				// create new database NOT ok: this should never happen
				$data['error'] = 'There was a problem creating the new database. Please try again.';
				$this->load->view('header', $data);
				$this->load->view('install/install_database_creation', $data);
				$this->load->view('footer');
				
			}
		
		}
		
	}
	
	/**
	 * tables_creation function.
	 * 
	 * @access public
	 * @return void
	 */
	public function tables_creation() {
	
		$data = (object)[];
		
		// form validation
		$this->form_validation->set_rules('db_name_cookie', 'Database name', 'trim|required|alpha_dash|max_length[64]');
		
		if ($this->form_validation->run() === false) {
		
			// form not ok, show errors to the user
			$this->load->view('header', $data);
			$this->load->view('install/install_tables_creation', $data);
			$this->load->view('footer');
		
		} else {
			
			$database_name = $_COOKIE['db_name'];
			
			if ($this->install_model->create_tables($database_name) === true) {
				
				// database creation ok, go to next install step
				redirect('install/site_settings');
				
			} else {
				
				// create new database NOT ok: this should never happen
				$data['error'] = 'There was a problem generating tables in the database. Please try again.';
				$this->load->view('header', $data);
				$this->load->view('install/install_tables_creation', $data);
				$this->load->view('footer');
				
			}
		
		}
	
	}
	
	/**
	 * site_settings function.
	 * 
	 * @access public
	 * @return void
	 */
	public function site_settings() {
		
		$data = (object)[];
		
		// delete the cookie we have created before
		if (isset($_COOKIE['db_name'])) {
			// empty value and expiration one hour before
			setcookie('db_name', '', time() - 3600);
		}
		
		// form validation
		$this->form_validation->set_rules('install_base_url', 'Base url', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('install_site_title', 'Forum Title', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('install_site_slogan', 'Forum Slogan', 'trim|max_length[255]');
		$this->form_validation->set_rules('admin_username', 'Username', 'trim|required|alpha_numeric|min_length[4]|max_length[20]');
		$this->form_validation->set_rules('admin_email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('admin_password', 'Password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('admin_password_confirm', 'Password Confirmation', 'trim|required|min_length[6]|matches[admin_password]');

		
		if ($this->form_validation->run() === false) {
		
			// form not ok, show errors to the user
			$this->load->view('header', $data);
			$this->load->view('install/install_site_settings', $data);
			$this->load->view('footer');
		
		} else {
			
			// load the user model
			$this->load->model('user_model');
			
			// setup variables from the form inputs
			$base_url    = $this->input->post('install_base_url');
			$site_title  = addslashes($this->input->post('install_site_title'));
			$site_slogan = null !== $this->input->post('install_site_slogan') ? addslashes($this->input->post('install_site_slogan')) : '';
			$username = $this->input->post('admin_username');
			$email    = $this->input->post('admin_email');
			$password = $this->input->post('admin_password');
			
			// create the admin user
			if ($this->user_model->create_admin_user($username, $email, $password) !== true) {
				
				$data->error = 'There was a problem trying to create the admin user. Please try again...';
				$this->load->view('header', $data);
				$this->load->view('install/install_site_settings', $data);
				$this->load->view('footer', $data);
				return;
				
			}
			
			// set the site email address
			$find    = '$config[\'site_email\'] =';
			$replace = '$config[\'site_email\'] = \'' . $email . '\';' . "\n";
			if ($this->install_model->edit_forum_config_file($find, $replace) !== true) {
				
				$data->error = 'The Forum email address on your forum config file cannot be updated...';
				$this->load->view('header', $data);
				$this->load->view('install/install_site_settings', $data);
				$this->load->view('footer', $data);
				return;
				
			}
			
			// change session driver to session stored in database
			$find    = '$config[\'sess_driver\'] =';
			$replace = '$config[\'sess_driver\'] = \'' . 'database' . '\';' . "\n";
			if ($this->install_model->edit_main_config_file($find, $replace) !== true) {
				
				$data->error = 'The session driver on your main config file cannot be updated...';
				$this->load->view('header', $data);
				$this->load->view('install/install_site_settings', $data);
				$this->load->view('footer', $data);
				return;
				
			}
			
			// change session path to session stored in database
			$find    = '$config[\'sess_save_path\'] =';
			$replace = '$config[\'sess_save_path\'] = \'' . 'ci_sessions' . '\';' . "\n";
			if ($this->install_model->edit_main_config_file($find, $replace) !== true) {
				
				$data->error = 'The session path on your main config file cannot be updated...';
				$this->load->view('header', $data);
				$this->load->view('install/install_site_settings', $data);
				$this->load->view('footer', $data);
				return;
				
			}
			
			// replace base url in the config.php config file
			$find    = '$config[\'base_url\'] =';
			$replace = '$config[\'base_url\'] = \'' . $base_url . '\';' . "\n";
			if ($this->install_model->edit_main_config_file($find, $replace) !== true) {
				
				$data->error = 'The base url on your main config file cannot be replaced...';
				$this->load->view('header', $data);
				$this->load->view('install/install_site_settings', $data);
				$this->load->view('footer', $data);
				return;
				
			}
			
			// replace site title in the forum.php config file
			$find    = '$config[\'site_title\'] =';
			$replace = '$config[\'site_title\'] = \'' . $site_title . '\';' . "\n";
			if ($this->install_model->edit_forum_config_file($find, $replace) !== true) {
				
				$data->error = 'The Forum title on your forum config file cannot be replaced...';
				$this->load->view('header', $data);
				$this->load->view('install/install_site_settings', $data);
				$this->load->view('footer', $data);
				return;
				
			}
			
			// replace site slogan in the forum.php config file
			$find    = '$config[\'site_slogan\'] =';
			$replace = '$config[\'site_slogan\'] = \'' . $site_slogan . '\';' . "\n";
			if ($this->install_model->edit_forum_config_file($find, $replace) !== true) {
				
				$data->error = 'The Forum slogan on your forum config file cannot be replaced...';
				$this->load->view('header', $data);
				$this->load->view('install/install_site_settings', $data);
				$this->load->view('footer', $data);
				return;
				
			}
			
			// replace default route in the routes.php config file
			$find    = '$route[\'default_controller\'] =';
			$replace = '$route[\'default_controller\'] = \'' . 'forum' . '\';' . "\n";
			if ($this->install_model->edit_routes_config_file($find, $replace) !== true) {
				
				$data->error = 'The default route on your routes config file cannot be replaced...';
				$this->load->view('header', $data);
				$this->load->view('install/install_site_settings', $data);
				$this->load->view('footer', $data);
				return;
				
			}

			// forum settings ok, go to the final installation step
			redirect('install/finish');
		
		}
		
	}
	
	/**
	 * finish function.
	 * 
	 * @access public
	 * @return void
	 */
	public function finish() {
		
		$data = (object)[];
		
		$this->load->view('header', $data);
		$this->load->view('install/install_finish', $data);
		$this->load->view('footer', $data);
		
	}
	
	/**
	 * delete_files function.
	 * 
	 * @access public
	 * @return void
	 */
	public function delete_files() {
		
		$data = (object)[];
		
		if($this->install_model->delete_installation_files()) {
			redirect('/');
			return;
		} else {
			echo 'Unable to delete installation files, please do it manually.';
		}
		
	}
	
}