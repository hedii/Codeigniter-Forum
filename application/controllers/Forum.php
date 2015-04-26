<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Forum class.
 * 
 * @extends CI_Controller
 */
class Forum extends CI_Controller {

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
		
	}
	
	public function index($slug = false) {
		
		// create the data object
		$data = new stdClass();
		
		if ($slug === false) {
			
			$forums = $this->forum_model->get_forums();
			
			foreach ($forums as $forum) {
				
				$forum->permalink = base_url($forum->slug);
				
				/* @todo count posts, topics, etc... */
				
			}
			
			$data->forums = $forums;
			
			$this->load->view('header');
			$this->load->view('forum/index', $data);
			$this->load->view('footer');
			
		} else {
			
			$forum_id = $this->forum_model->get_forum_id_from_forum_slug($slug);
			$forum    = $this->forum_model->get_forum($forum_id);
			
			$data->forum = $forum;
			
			$this->load->view('header');
			$this->load->view('forum/single', $data);
			$this->load->view('footer');
			
		}
		
	}
	
	
	/**
	 * create function.
	 * 
	 * @access public
	 * @return void
	 */
	public function create_forum() {
		
		// create the data object
		$data = new stdClass();
		
		// load form helper and validation library
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// set validation rules
		$this->form_validation->set_rules('title', 'Forum Title', 'trim|required|alpha_numeric_spaces|min_length[4]|max_length[255]|is_unique[forums.title]', array('is_unique' => 'The forum title you entered already exists. Please choose another forum title.'));
		$this->form_validation->set_rules('description', 'Description', 'trim|alpha_numeric_spaces|max_length[80]');
		
		if ($this->form_validation->run() === false) {
			
			// validation not ok, send validation errors to the view
			$this->load->view('header');
			$this->load->view('forum/create/create', $data);
			$this->load->view('footer');
			
		} else {
			
			// set variables from the form
			$title       = $this->input->post('title');
			$description = $this->input->post('description');
			
			if ($this->forum_model->create_forum($title, $description)) {
				
				// forum creation ok
				$this->load->view('header');
				$this->load->view('forum/create/create_success', $data);
				$this->load->view('footer');
				
			} else {
				
				// forum creation failed, this should never happen
				$data->error = 'There was a problem creating the new forum. Please try again.';
				
				// send error to the view
				$this->load->view('header');
				$this->load->view('forum/create/create', $data);
				$this->load->view('footer');
				
			}
			
		}
		
	}
	
	public function create_topic($forum_slug) {
		
		// create the data object
		$data = new stdClass();
		
		// set variables from the the URI
		$forum_slug = $this->uri->segment(1);
		$forum_id   = $this->forum_model->get_forum_id_from_forum_slug($forum_slug);
		
		// load form helper and validation library
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// set validation rules
		$this->form_validation->set_rules('title', 'Topic Title', 'trim|required|alpha_numeric_spaces|min_length[4]|max_length[255]|is_unique[topics.title]', array('is_unique' => 'The topic title you entered already exists in our database. Please enter another topic title.'));
		$this->form_validation->set_rules('content', 'Content', 'required|min_length[4]');
		
		if ($this->form_validation->run() === false) {
			
			// validation not ok, send validation errors to the view
			$this->load->view('header');
			$this->load->view('topic/create/create', $data);
			$this->load->view('footer');
			
		} else {
			
			$title   = $this->input->post('title');
			$content = $this->input->post('content');
			$user_id = $_SESSION['user_id'];
			
			if ($this->forum_model->create_topic($forum_id, $title, $content, $user_id)) {
				
				// topic creation ok
				redirect(base_url($forum_slug . '/' . strtolower(url_title($title))));
				
			} else {
				
				// topic creation failed, this should never happen
				$data->error = 'There was a problem creating your new topic. Please try again.';
				
				// send error to the view
				$this->load->view('header');
				$this->load->view('topic/create/create', $data);
				$this->load->view('footer');
				
			}
			
		}
		
	}
	
	public function topic($forum_slug, $topic_slug) {
		
		// create the data object
		$data = new stdClass();
		
		$forum_id = $this->forum_model->get_forum_id_from_forum_slug($forum_slug);
		$topic_id = $this->forum_model->get_topic_id_from_topic_slug($topic_slug);
		
		$forum = $this->forum_model->get_forum($forum_id);
		$topic = $this->forum_model->get_topic($topic_id);
		$posts = $this->forum_model->get_posts($topic_id);
		
		foreach ($posts as $post) {
			
			$post->author = $this->user_model->get_username_from_user_id($post->user_id);
			
		}
		
		
		$data->forum = $forum;
		$data->topic = $topic;
		$data->posts = $posts;
		
		$this->load->view('header');
		$this->load->view('topic/single', $data);
		$this->load->view('footer');
		
		//var_dump($forum, $topic, $posts);
		
	}
	
}
