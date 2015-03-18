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
		$this->load->model('forum_model');
		$this->load->model('user_model');
		//$this->output->enable_profiler(TRUE);
		
	}
	
	/**
	 * index function.
	 * 
	 * @access public
	 * @param bool $slug (default: false)
	 * @return void
	 */
	public function index($slug = false) {
		
		$data = [];
		$data['forums'] = null;
		
		if ($slug == false) {
		
			$forums = $this->forum_model->get_forum();
			
			foreach ($forums as $forum) {
				
				// if there is at least a topic on the forum, send forum object normally
				if ($this->forum_model->get_last_forum_topic($forum->id) !== null) {

					$topics                    = $this->forum_model->get_forum_topics($forum->id);
					$latest_topic              = (object)$this->forum_model->get_last_forum_topic($forum->id);
					$latest_topic->author_name = 'by ' . $this->user_model->get_username_from_user_id($latest_topic->author_id);
					$latest_topic->permalink   = base_url() . 'forum/' . $forum->slug . '/' . $latest_topic->slug;
					$latest_topic->date        = $this->time_ago_format_date($latest_topic->date) . ' ago';		
					
					$data['forums'][] = (object)[
						'id'             => $forum->id,
						'title'          => $forum->title,
						'slug'           => $forum->slug,
						'permalink'      => base_url() . 'forum/' . $forum->slug,
						'description'    => $forum->description,
						'category_id'    => $forum->category_id,
						'topics'         => $topics,
						'count_topics'   => count($topics),
						'latest_topic'   => $latest_topic,
						'count_posts'    => $this->forum_model->count_forum_posts($forum->id)
					];
				
				} else {
					
					// there is no topic on this forum
					$data['forums'][] = (object)[
						'id'             => $forum->id,
						'title'          => $forum->title,
						'slug'           => $forum->slug,
						'permalink'      => base_url() . 'forum/' . $forum->slug,
						'description'    => $forum->description,
						'category_id'    => $forum->category_id,
						'topics'         => null,
						'count_topics'   => '0',
						'latest_topic'   => (object)[
							'permalink'   => '',
							'author_name' => '',
							'date'        => ''
						],
						'count_posts'    => $this->forum_model->count_forum_posts($forum->id)
					];
					
				}
				
			}
			
			$this->load->view('header', $data);
			$this->load->view('forum/forum_index', $data);
			$this->load->view('footer');
		
		} else {
			
			$limit = 20;
			$forum_id = $this->forum_model->get_forum_id_from_forum_slug($slug);
			$forum    = $this->forum_model->get_forum($forum_id);
			
			// pagination
			$this->load->library('pagination');
			$config['base_url']        = base_url() . 'forum/' . $slug;
			$config['total_rows']      = count($this->forum_model->get_forum_topics($forum_id));
			$config['per_page']        = $limit;
			$config['uri_segment']     = 3;
			$config['num_links']       = 2;
			$config['full_tag_open']   = '<ul class="pagination pagination-sm">';
			$config['full_tag_close']  = '</ul>';
			$config['num_tag_open']    = '<li>';
			$config['num_tag_close']   = '</li>';
			$config['cur_tag_open']    = '<li class="active"><a href="#">';
			$config['cur_tag_close']   = ' <span class="sr-only">(current)</span></a></li>';
			$config['prev_link']       = false;
			$config['next_link']       = false;
			$config['first_link']      = '&laquo;';
			$config['first_tag_open']  = '<li class="pagination-first-tag">';
			$config['first_tag_close'] = '</li><li class="disabled"><a href="#"><span>...</span></a></li>';
			$config['last_link']       = '&raquo;';
			$config['last_tag_open']   = '<li class="disabled"><a href="#"><span>...</span></a></li><li class="pagination-last-tag">';
			$config['last_tag_close']  = '</li>';
			
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
			$data['pagination'] = $this->pagination->create_links();
			
			$topics   = $this->forum_model->get_forum_topics($forum_id, $order_by_last_post_date = true, $limit, $page);
			
			$data['forum'] = (object)[
				'id'             => $forum->id,
				'title'          => $forum->title,
				'slug'           => $forum->slug,
				'description'    => $forum->description,
				'category_id'    => $forum->category_id,
				'permalink'      => base_url() . 'forum/' . $forum->slug,
				'new_topic_link' => base_url() . 'forum/' . $forum->slug . '/new'
			];
			
			foreach ($topics as $topic) {
				
				$posts = $this->forum_model->get_post($topic->id);
				$count_topic_posts = 0;
				
				foreach ($posts as $post) {
					$count_topic_posts++;
				}
				
				$latest_post = $this->forum_model->get_last_topic_post($topic->id);
				
				$data['topics'][] = (object)[
					'id'          => $topic->id,
					'title'       => $topic->title,
					'slug'        => $topic->slug,
					'permalink'   => base_url() . 'forum/' . $forum->slug . '/' . $topic->slug,
					'date'        => $this->time_ago_format_date($topic->date),
					'count_posts' => $count_topic_posts,
					'author'      => (object)[
						'id'        => $topic->author_id,
						'name'      => $this->user_model->get_username_from_user_id($topic->author_id),
						'permalink' => base_url() . 'user/' . $this->user_model->get_username_from_user_id($topic->author_id)
					],
					'latest_post' => (object)[
						'id'     => $latest_post->id,
						'date'   => $this->time_ago_format_date($latest_post->date),
						'author' => (object)[
							'id'        => $latest_post->author_id,
							'name'      => $this->user_model->get_username_from_user_id($latest_post->author_id),
							'permalink' => base_url(). 'user/' . $this->user_model->get_username_from_user_id($latest_post->author_id)
						]
					]
				];
				
			}
			
			$this->load->view('header', $data);
			$this->load->view('forum/forum_single', $data);
			$this->load->view('footer');
			
		}
		
	}
	
	/**
	 * topic function.
	 * 
	 * @access public
	 * @param mixed $slug
	 * @return void
	 */
	public function topic($slug) {
		
		// set basic variables
		$slug       = $this->uri->segment(3, 0);
		$forum_slug = $this->uri->segment(2, 0);
		$forum_id   = $this->forum_model->get_forum_id_from_forum_slug($forum_slug);
		$topic_id   = $this->forum_model->get_topic_id_from_topic_slug($slug);
		
		// get the forum object
		$data['forum'] = $this->forum_model->get_forum($forum_id);
		
		// get topic object
		$data['topic'] = $this->forum_model->get_topic($topic_id);
		
		// pagination
		$limit = 15;
		$this->load->library('pagination');
		$config['base_url']        = base_url() . 'forum/' . $forum_slug . '/' . $slug . '/';
		$config['total_rows']      = $this->forum_model->count_posts($topic_id);
		$config['per_page']        = $limit;
		$config['uri_segment']     = 4;
		$config['num_links']       = 2;
		$config['full_tag_open']   = '<ul class="pagination pagination-sm">';
		$config['full_tag_close']  = '</ul>';
		$config['num_tag_open']    = '<li>';
		$config['num_tag_close']   = '</li>';
		$config['cur_tag_open']    = '<li class="active"><a href="#">';
		$config['cur_tag_close']   = ' <span class="sr-only">(current)</span></a></li>';
		$config['prev_link']       = false;
		$config['next_link']       = false;
		$config['first_link']      = '&laquo;';
		$config['first_tag_open']  = '<li class="pagination-first-tag">';
		$config['first_tag_close'] = '</li><li class="disabled"><a href="#"><span>...</span></a></li>';
		$config['last_link']       = '&raquo;';
		$config['last_tag_open']   = '<li class="disabled"><a href="#"><span>...</span></a></li><li class="pagination-last-tag">';
		$config['last_tag_close']  = '</li>';
		
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$data['pagination'] = $this->pagination->create_links();
		
		// get all posts from this topic, limited by pagination
		$posts = $this->forum_model->get_post($topic_id, $limit, $page);
		
		// loop through posts and create post objects
		foreach ($posts as $post) {
			
			$author_id = $post->author_id;
			$author    = $this->user_model->get_user($author_id);
			
			$data['posts'][] = (object)[
				'id'          => $post->id,
				'date'        => date("m-d-Y", strtotime($post->date)) . ' at ' . date("H:i:s", strtotime($post->date)),
				'content'     => $post->content,
				'permalink'   => base_url() . 'forum/' . $forum_slug . '/' . $slug . '/#' . $post->id,
				'edit_link'   => base_url() . 'forum/' . $forum_slug . '/' . $slug . '/edit/#' . $post->id,
				'report_link' => base_url() . 'forum/' . $forum_slug . '/' . $slug . '/report/#' . $post->id,
				'author'      => (object)[
					'id'           => $post->author_id,
					'name'         => $this->user_model->get_username_from_user_id($post->author_id),
					'avatar'       =>  base_url() . 'uploads/avatars/' . $author->avatar,
					'permalink'    => base_url(). 'user/' . $this->user_model->get_username_from_user_id($post->author_id),
					'count_posts'  => $this->forum_model->count_user_posts($post->author_id),
					'count_topics' => $this->forum_model->count_user_topics($post->author_id)
				]
			];
			
		}
		
		// load helpers and libraries
		$this->load->helper(array('form'));
		$this->load->library('form_validation');
		
		// form validation
		$this->form_validation->set_rules('reply_post_content', 'Reply', 'required|prep_for_form');
		
		// run the form
		if ($this->form_validation->run() === false) {
			
			// form not ok, show errors to the user
			$this->load->view('header', $data);
			$this->load->view('forum/topic/topic_single', $data);
			$this->load->view('footer');
			
		} else {
			
			// form validation ok, setup some variables
			$topic_id  = $topic_id;
			$author_id = $_SESSION['user_id'];
			$content   = $this->input->post('reply_post_content');
			
			if ($this->forum_model->create_post($topic_id, $author_id, $content) === true) {
				
				// insert new post ok
				$data['success'] = 'post published!';
				redirect('forum/' . $forum_slug . '/' . $slug);
				
			} else {
				
				// insert new post NOT ok: this should never happen
				$data['error'] = 'There was a problem creating your new post. Please try again.';
				$this->load->view('header', $data);
				$this->load->view('forum/topic/topic_single', $data);
				$this->load->view('footer');
				
			}
			
		}
		
	}
	
	public function new_topic() {
		
		$forum_slug = $this->uri->segment(2, 0);
		$forum_id   = $this->forum_model->get_forum_id_from_forum_slug($forum_slug);
		
		$this->load->helper(array('form'));
		$this->load->library('form_validation');
		
		// form validation
		$this->form_validation->set_rules('new_topic_title', 'Title', 'trim|required|min_length[4]|max_length[100]|is_unique[topics.title]');
		$this->form_validation->set_rules('new_topic_content', 'Content', 'required|min_length[2]');
		
		$data = (object)[];
		
		if ($this->form_validation->run() === false) {
		
			// form not ok, show errors to the user
			$this->load->view('header', $data);
			$this->load->view('forum/topic/topic_new', $data);
			$this->load->view('footer');
		
		} else {
			
			$author_id  = $_SESSION['user_id'];
			$title      = $this->input->post('new_topic_title');
			$slug = url_title($this->input->post('new_topic_title'), 'dash', true);
			$content    = $this->input->post('new_topic_content');
			
			if ($this->forum_model->create_topic($forum_id, $author_id, $title, $slug, $content)) {
				
				// new topic OK, redirect user to the new created topic
				redirect ('forum/' . $forum_slug . '/' . $slug );
				
			} else {
				
				// insert new topic NOT ok: this should never happen
				$data['error'] = 'There was a problem creating your new topic. Please try again.';
				$this->load->view('header', $data);
				$this->load->view('forum/topic/topic_new', $data);
				$this->load->view('footer');
				
			}
			
		}
		
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
	
}