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
		
		//$this->output->enable_profiler(TRUE);
		
	}
	
	/**
	 * index function.
	 * 
	 * @access public
	 * @param mixed $slug (default: false)
	 * @return void
	 */
	public function index($slug = false) {
		
		// create the data object
		$data = new stdClass();
		
		if ($slug === false) {
			
			// create objects
			$forums = $this->forum_model->get_forums();
			
			foreach ($forums as $forum) {
				
				$forum->permalink    = base_url($forum->slug);
				$forum->topics       = $this->forum_model->get_forum_topics($forum->id);
				$forum->count_topics = count($forum->topics);
				$forum->count_posts  = $this->forum_model->count_forum_posts($forum->id);
				
				if ($forum->count_topics > 0) {
					
					// $forum has topics
					$forum->latest_topic            = $this->forum_model->get_forum_latest_topic($forum->id);
					$forum->latest_topic->permalink = $forum->slug . '/' . $forum->latest_topic->slug;
					$forum->latest_topic->author    = $this->user_model->get_username_from_user_id($forum->latest_topic->user_id);
					
				} else {
					
					// $forum doesn't have topics yet
					$forum->latest_topic = new stdClass();
					$forum->latest_topic->permalink = null;
					$forum->latest_topic->title = null;
					$forum->latest_topic->author = null;
					$forum->latest_topic->created_at = null;
					
				}
	
			}
			
			// create breadcrumb
			$breadcrumb  = '<ol class="breadcrumb">';
			$breadcrumb .= '<li class="active">Home</li>';
			$breadcrumb .= '</ol>';
			
			// assign created objects to the data object
			$data->forums     = $forums;
			$data->breadcrumb = $breadcrumb;
			
			// load views and send data
			$this->load->view('header');
			$this->load->view('forum/index', $data);
			$this->load->view('footer');
			
		} else {
			
			// get id from slug
			$forum_id = $this->forum_model->get_forum_id_from_forum_slug($slug);
			
			// create objects
			$forum    = $this->forum_model->get_forum($forum_id);
			$topics   = $this->forum_model->get_forum_topics($forum_id);
			
			// create breadcrumb
			$breadcrumb  = '<ol class="breadcrumb">';
			$breadcrumb .= '<li><a href="' . base_url() . '">Home</a></li>';
			$breadcrumb .= '<li class="active">' . $forum->title . '</li>';
			$breadcrumb .= '</ol>';
			
			foreach ($topics as $topic) {
				
				$topic->author                  = $this->user_model->get_username_from_user_id($topic->user_id);
				$topic->permalink               = $slug . '/' . $topic->slug;
				$topic->posts                   = $this->forum_model->get_posts($topic->id);
				$topic->count_posts             = count($topic->posts);
				$topic->latest_post             = $this->forum_model->get_topic_latest_post($topic->id);
				$topic->latest_post->author     = $this->user_model->get_username_from_user_id($topic->latest_post->user_id);
				
			}
			
			// assign created objects to the data object
			$data->forum      = $forum;
			$data->topics     = $topics;
			$data->breadcrumb = $breadcrumb;
			
			// load views and send data
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
		
		// if the user is not logged in as administrator, he cannot create a new forum
		if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
			$data->login_as_admin_needed = true;
		} else {
			$data->login_as_admin_needed = false;
		}
		
		// create breadcrumb
		$breadcrumb  = '<ol class="breadcrumb">';
		$breadcrumb .= '<li><a href="' . base_url() . '">Home</a></li>';
		$breadcrumb .= '<li class="active">Create a new forum</li>';
		$breadcrumb .= '</ol>';
		
		// assign breadcrumb to the data object
		$data->breadcrumb = $breadcrumb;
		
		// load form helper and validation library
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// set validation rules
		$this->form_validation->set_rules('title', 'Forum Title', 'trim|required|alpha_numeric_spaces|min_length[4]|max_length[255]|is_unique[forums.title]', array('is_unique' => 'The forum title you entered already exists. Please choose another forum title.'));
		$this->form_validation->set_rules('description', 'Description', 'trim|alpha_numeric_spaces|max_length[80]');
		
		if ($this->form_validation->run() === false) {
			
			// keep what the user has entered previously on fields
			$data->title       = $this->input->post('title');
			$data->description = $this->input->post('description');
			
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
	
	/**
	 * create_topic function.
	 * 
	 * @access public
	 * @param string $forum_slug
	 * @return void
	 */
	public function create_topic($forum_slug) {
		
		// create the data object
		$data = new stdClass();
		
		// if the user is not logged in, he cannot create a new topic
		if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
			$data->login_needed = true;
		} else {
			$data->login_needed = false;
		}
		
		// set variables from the the URI
		$forum_slug = $this->uri->segment(1);
		$forum_id   = $this->forum_model->get_forum_id_from_forum_slug($forum_slug);
		$forum      = $this->forum_model->get_forum($forum_id);
		
		// create breadcrumb
		$breadcrumb  = '<ol class="breadcrumb">';
		$breadcrumb .= '<li><a href="' . base_url() . '">Home</a></li>';
		$breadcrumb .= '<li><a href="' . base_url($forum->slug) . '">' . $forum->title . '</a></li>';
		$breadcrumb .= '<li class="active">Create a new topic</li>';
		$breadcrumb .= '</ol>';
		
		// assign breadcrumb to the data object
		$data->breadcrumb = $breadcrumb;
		
		// load form helper and validation library
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// set validation rules
		$this->form_validation->set_rules('title', 'Topic Title', 'trim|required|alpha_numeric_spaces|min_length[4]|max_length[255]|is_unique[topics.title]', array('is_unique' => 'The topic title you entered already exists in our database. Please enter another topic title.'));
		$this->form_validation->set_rules('content', 'Content', 'required|min_length[4]');
		
		if ($this->form_validation->run() === false) {
			
			// keep what the user has entered previously on fields
			$data->title   = $this->input->post('title');
			$data->content = $this->input->post('content');
			
			// validation not ok, send validation errors to the view
			$this->load->view('header');
			$this->load->view('topic/create/create', $data);
			$this->load->view('footer');
			
		} else {
			
			// set variables from the form
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
	
	/**
	 * topic function.
	 * 
	 * @access public
	 * @param string $forum_slug
	 * @param string $topic_slug
	 * @return void
	 */
	public function topic($forum_slug, $topic_slug) {
		
		// create the data object
		$data = new stdClass();
		
		// get ids from slugs
		$forum_id = $this->forum_model->get_forum_id_from_forum_slug($forum_slug);
		$topic_id = $this->forum_model->get_topic_id_from_topic_slug($topic_slug);
		
		// create objects
		$forum = $this->forum_model->get_forum($forum_id);
		$topic = $this->forum_model->get_topic($topic_id);
		$posts = $this->forum_model->get_posts($topic_id);
		
		foreach ($posts as $post) {
			
			$post->author = $this->user_model->get_username_from_user_id($post->user_id);
			
		}
		
		// create breadcrumb
		$breadcrumb  = '<ol class="breadcrumb">';
		$breadcrumb .= '<li><a href="' . base_url() . '">Home</a></li>';
		$breadcrumb .= '<li><a href="' . base_url($forum->slug) . '">' . $forum->title . '</a></li>';
		$breadcrumb .= '<li class="active">' . $topic->title . '</li>';
		$breadcrumb .= '</ol>';
		
		// assign created objects to the data object
		$data->forum      = $forum;
		$data->topic      = $topic;
		$data->posts      = $posts;
		$data->breadcrumb = $breadcrumb;
		
		// load views and send data
		$this->load->view('header');
		$this->load->view('topic/single', $data);
		$this->load->view('footer');
		
	}
	
	/**
	 * create_post function.
	 * 
	 * @access public
	 * @param string $forum_slug
	 * @param string $topic_slug
	 * @return void
	 */
	public function create_post($forum_slug, $topic_slug) {
		
		// create the data object
		$data = new stdClass();
		
		// if the user is not logged in, he cannot reply to a topic
		if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
			$data->login_needed = true;
		} else {
			$data->login_needed = false;
		}
		
		// get ids from slugs
		$forum_id = $this->forum_model->get_forum_id_from_forum_slug($forum_slug);
		$topic_id = $this->forum_model->get_topic_id_from_topic_slug($topic_slug);
		
		// create objects
		$forum = $this->forum_model->get_forum($forum_id);
		$topic = $this->forum_model->get_topic($topic_id);
		$posts = $this->forum_model->get_posts($topic_id);
		
		foreach ($posts as $post) {
			
			$post->author = $this->user_model->get_username_from_user_id($post->user_id);
			
		}
		
		// create breadcrumb
		$breadcrumb  = '<ol class="breadcrumb">';
		$breadcrumb .= '<li><a href="' . base_url() . '">Home</a></li>';
		$breadcrumb .= '<li><a href="' . base_url($forum->slug) . '">' . $forum->title . '</a></li>';
		$breadcrumb .= '<li class="active">' . $topic->title . '</li>';
		$breadcrumb .= '</ol>';
		
		// assign created objects to the data object
		$data->forum      = $forum;
		$data->topic      = $topic;
		$data->posts      = $posts;
		$data->breadcrumb = $breadcrumb;
		
		// load form helper and validation library
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// set validation rules
		$this->form_validation->set_rules('reply', 'Reply', 'required|min_length[2]');
		
		if ($this->form_validation->run() === false) {
			
			// keep what the user has entered previously on fields
			$data->content = $this->input->post('reply');
			
			// validation not ok, send validation errors to the view
			$this->load->view('header');
			$this->load->view('topic/reply', $data);
			$this->load->view('footer');
			
		} else {
			
			$user_id = $_SESSION['user_id'];
			$content = $this->input->post('reply');
			
			if ($this->forum_model->create_post($topic_id, $user_id, $content)) {
				
				// post creation ok
				redirect(base_url($forum_slug . '/' . $topic_slug));
				
			} else {
				
				// post creation failed, this should never happen
				$data->error = 'There was a problem creating your reply. Please try again.';
				
				// send error to the view
				$this->load->view('header');
				$this->load->view('topic/reply', $data);
				$this->load->view('footer');
				
			}
			
		}
		
	}
	
}
