<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin class.
 * 
 * @extends CI_Controller
 */
class Admin extends CI_Controller {

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
		$this->load->model('forum_model');
		$this->load->model('user_model');
		
		//$this->output->enable_profiler(TRUE);
		
	}
	
	public function index() {
		
		// if the user is not admin, redirect to base url
		if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
			redirect(base_url());
			return;
		}
		
		// create the data object
		$data = new stdClass();
		
		$this->load->view('header');
		$this->load->view('admin/home', $data);
		$this->load->view('footer');
		
	}
	
	public function users() {
		
		// if the user is not admin, redirect to base url
		if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
			redirect(base_url());
			return;
		}
		
		// create the data object
		$data = new stdClass();
		
		$this->load->view('header');
		$this->load->view('admin/users', $data);
		$this->load->view('footer');
		
	}
	
	public function forums_and_topics() {
		
		// if the user is not admin, redirect to base url
		if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
			redirect(base_url());
			return;
		}
		
		// create the data object
		$data = new stdClass();
		
		$this->load->view('header');
		$this->load->view('admin/forums_and_topics', $data);
		$this->load->view('footer');
		
	}
	
	public function options() {
		
		// if the user is not admin, redirect to base url
		if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
			redirect(base_url());
			return;
		}
		
		// create the data object
		$data = new stdClass();
		
		$this->load->view('header');
		$this->load->view('admin/options', $data);
		$this->load->view('footer');
		
	}
	
	public function emails() {
		
		// if the user is not admin, redirect to base url
		if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
			redirect(base_url());
			return;
		}
		
		// create the data object
		$data = new stdClass();
		
		$this->load->view('header');
		$this->load->view('admin/emails', $data);
		$this->load->view('footer');
		
	}
	
}
