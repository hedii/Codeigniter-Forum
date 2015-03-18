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
		
		if (!isset($_SESSION) || !isset($_SESSION['username']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
			
			// user is not logged in as admin, redirect him
			redirect();
			return;
			
		}
		
		$this->load->model('user_model');
		$this->load->model('forum_model');
		
	}
	
	public function index() {

		$data[] = (object)[];			
		//var_dump($data);
			
		$this->load->view('header');
		$this->load->view('admin/admin_index', $data);
		$this->load->view('footer');
		
		
	}
	
	public function new_forum() {

		$this->load->helper(array('form'));
		$this->load->library('form_validation');
		
		$data[] = (object)[];
		//var_dump($data);
		
		// form validation
		$this->form_validation->set_rules('new_forum_title', 'Title', 'trim|required|min_length[4]|max_length[100]|is_unique[forums.title]');
		$this->form_validation->set_rules('new_forum_description', 'Description', 'trim|min_length[4]|max_length[200]');
		
		if ($this->form_validation->run() === false) {
			
			// form not ok, show errors to the user
			$this->load->view('header');
			$this->load->view('admin/forum/admin_new_forum', $data);
			$this->load->view('footer');
		
		} else {
			
			$title       = $this->input->post('new_forum_title');
			$slug        = url_title($this->input->post('new_forum_title'), 'dash', true);
			$description = $this->input->post('new_forum_description');
			
			if ($this->forum_model->create_forum($title, $slug, $description)) {
				
				// new forum OK, redirect user
				redirect ('admin/');
				
			} else {
				
				// insert new forum NOT ok: this should never happen
				$data['error'] = 'There was a problem creating your new forum. Please try again.';
				$this->load->view('header', $data);
				$this->load->view('forum/topic/topic_new', $data);
				$this->load->view('footer');
				
			}
			
		}
		
	}

}