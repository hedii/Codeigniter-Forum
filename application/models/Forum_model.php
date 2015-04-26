<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Forum_model class.
 * 
 * @extends CI_Model
 */
class Forum_model extends CI_Model {

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		$this->load->helper(array('url'));
		
	}
	
	/**
	 * create_forum function.
	 * 
	 * @access public
	 * @param string $title
	 * @param string $description
	 * @return bool
	 */
	public function create_forum($title, $description) {
		
		$data = array(
			'title'       => $title,
			'slug'        => strtolower(url_title($title)),
			'description' => $description,
			'created_at'  => date('Y-m-j H:i:s'),
		);
		
		return $this->db->insert('forums', $data);
		
	}
	
	public function get_forum_id_from_forum_slug($slug) {
		
		$this->db->select('id');
		$this->db->from('forums');
		$this->db->where('slug', $slug);
		return $this->db->get()->row('id');
		
	}
	
	public function get_topic_id_from_topic_slug($slug) {
		
		$this->db->select('id');
		$this->db->from('topics');
		$this->db->where('slug', $slug);
		return $this->db->get()->row('id');
		
	}
	
	/**
	 * get_forums function.
	 * 
	 * @access public
	 * @return array of objects
	 */
	public function get_forums() {
		
		return $this->db->get('forums')->result();
		
	}
	
	public function get_forum($forum_id) {
		
		$this->db->from('forums');
		$this->db->where('id', $forum_id);
		return $this->db->get()->row();
		
	}
	
	public function get_topic($topic_id) {
		
		$this->db->from('topics');
		$this->db->where('id', $topic_id);
		return $this->db->get()->row();
		
	}
	
	public function get_forum_topics($forum_id) {
		
		$this->db->from('topics');
		$this->db->where('forum_id', $forum_id);
		return $this->db->get()->result();
		
	}
	
	public function get_posts($topic_id) {
		
		$this->db->from('posts');
		$this->db->where('topic_id', $topic_id);
		return $this->db->get()->result();
		
	}
	
	public function get_topic_latest_post($topic_id) {
		
		$this->db->from('posts');
		$this->db->where('topic_id', $topic_id);
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit(1);
		return $this->db->get()->row();
		
	}
	
	public function create_topic($forum_id, $title, $content, $user_id) {
		
		$data = array(
			'title'      => $title,
			'slug'       => strtolower(url_title($title)),
			'user_id'    => $user_id,
			'forum_id'   => $forum_id,
			'created_at' => date('Y-m-j H:i:s'),
			'updated_at' => date('Y-m-j H:i:s'),
		);
		
		if ($this->db->insert('topics', $data)) {
			$topic_id = $this->db->insert_id();
			return $this->create_post($topic_id, $user_id, $content);
		}
		return false;
		
	}
	
	public function create_post($topic_id, $user_id, $content) {
		
		$data = array(
			'content'    => $content,
			'user_id'    => $user_id,
			'topic_id'   => $topic_id,
			'created_at' => date('Y-m-j H:i:s'),
		);
		
		if ($this->db->insert('posts', $data)) {
			
			$data = array('updated_at' => date('Y-m-j H:i:s'));
			$this->db->where('id', $topic_id);
			return $this->db->update('topics', $data);
			
		}
		return false;
		
	}
	
	public function count_forum_posts($forum_id) {
		
		$this->db->select('posts.id');
		$this->db->from('posts');
		$this->db->join('topics', 'posts.topic_id = topics.id');
		$this->db->where('topics.forum_id', $forum_id);
		$this->db->group_by('posts.id');
		return count($this->db->get()->result());
		
	}
	
	public function get_forum_latest_topic($forum_id) {
		
		$this->db->from('topics');
		$this->db->where('forum_id', $forum_id);
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit(1);
		return $this->db->get()->row();
		
	} 
	
}
