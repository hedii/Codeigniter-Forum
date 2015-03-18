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
		
	}
	
	/**
	 * get_forum function.
	 * 
	 * @access public
	 * @param bool $forum_id (default: false)
	 * @return object
	 */
	public function get_forum($forum_id = false) {
		
		if ($forum_id === false) {
			return $this->db->get('forums')->result();
		} else {
			$this->db->from('forums');
			$this->db->where('id', $forum_id);
			return $this->db->get()->row();
		}
		
	}
	
	/**
	 * get_topic function.
	 * 
	 * @access public
	 * @param mixed $topic_id
	 * @return object
	 */
	public function get_topic($topic_id) {
		
		$this->db->from('topics');
		$this->db->where('id', $topic_id);
		return $this->db->get()->row();
		
	}
	
	/**
	 * get_post function.
	 * 
	 * @access public
	 * @param mixed $topic_id
	 * @return object
	 */
	public function get_post($topic_id, $limit = false, $offset = false) {
		
		$this->db->from('posts');
		$this->db->where('topic_id', $topic_id);
		$this->db->limit($limit, $offset);
		return $this->db->get()->result();
		
	}
	
	/**
	 * get_forum_id_from_forum_slug function.
	 * 
	 * @access public
	 * @param mixed $forum_slug
	 * @return int
	 */
	public function get_forum_id_from_forum_slug($forum_slug) {
		
		$this->db->select('id');
		$this->db->where('slug', $forum_slug);
		return $this->db->get('forums')->row('id');
		
	}
	
	/**
	 * get_topic_id_from_topic_slug function.
	 * 
	 * @access public
	 * @param mixed $topic_slug
	 * @return int
	 */
	public function get_topic_id_from_topic_slug($topic_slug) {
		
		$this->db->select('id');
		$this->db->where('slug', $topic_slug);
		return $this->db->get('topics')->row('id');
		
	}
	
	/**
	 * get_forum_topics function.
	 *
	 * Given a forum id, return all topics on this forum.
	 * If $order_by_last_post_date is set to true, topics are sorted by most
	 * recent post on it (last commented topic will come first).
	 * 
	 * @access public
	 * @param int $forum_id
	 * @param bool $order_by_last_post_date (default: false)
	 * @return object
	 */
	public function get_forum_topics($forum_id, $order_by_last_post_date = false, $limit = false, $offset = false) {
		
		if ($order_by_last_post_date === true) {

			if ($limit !== false) {
				
				$this->db->select('topics.id, topics.title, topics.slug, topics.author_id, topics.forum_id, topics.date');
				$this->db->distinct();
				$this->db->from('topics');
				$this->db->where('forum_id', $forum_id);
				$this->db->join('posts', 'posts.topic_id = topics.id', 'inner');
				$this->db->group_by("topics.id");
				$this->db->order_by('MAX(posts.date)', 'DESC');
				$this->db->limit($limit, $offset);
				return $this->db->get()->result();
				
			} else {
				
				$this->db->select('topics.id, topics.title, topics.slug, topics.author_id, topics.forum_id, topics.date');
				$this->db->distinct();
				$this->db->from('topics');
				$this->db->where('forum_id', $forum_id);
				$this->db->join('posts', 'posts.topic_id = topics.id', 'inner');
				$this->db->group_by("topics.id");
				$this->db->order_by('MAX(posts.date)', 'DESC');
				return $this->db->get()->result();
				
			}
			
		} else {
			
			$this->db->from('topics');
			$this->db->where('forum_id', $forum_id);
			return $this->db->get()->result();
			
		}
		
	}
	
	/**
	 * get_last_forum_topic function.
	 * 
	 * @access public
	 * @param mixed $forum_id
	 * @return object
	 */
	public function get_last_forum_topic($forum_id) {
		
		$this->db->from('topics');
		$this->db->where('forum_id', $forum_id);
		$this->db->order_by('date', 'DESC');
		$this->db->limit(1);
		return $this->db->get()->row();
		
	}
	
	/**
	 * get_last_topic_post function.
	 * 
	 * @access public
	 * @param mixed $topic_id
	 * @return object
	 */
	public function get_last_topic_post($topic_id) {
		
		$this->db->from('posts');
		$this->db->where('topic_id', $topic_id);
		$this->db->order_by('date', 'DESC');
		$this->db->limit(1);
		return $this->db->get()->row();
		
	}
	

	/**
	 * get_user_posts function.
	 * 
	 * @access public
	 * @param int $user_id
	 * @param bool $limit (default: false)
	 * @param string $orderby (default: 'DESC')
	 * @return object
	 */
	public function get_user_posts($user_id, $limit = false, $orderby = 'DESC') {
		
		$this->db->from('posts');
		$this->db->where('author_id', $user_id);
		if ($limit !== false) {
			$this->db->limit($limit);
		}
		$this->db->order_by('date', $orderby);
		return $this->db->get()->result();
		
	}
	
	/**
	 * get_user_topics function.
	 * 
	 * @access public
	 * @param mixed $user_id
	 * @param bool $limit (default: false)
	 * @param string $orderby (default: 'DESC')
	 * @return object
	 */
	public function get_user_topics($user_id, $limit = false, $orderby = 'DESC') {
		
		$this->db->from('topics');
		$this->db->where('author_id', $user_id);
		if ($limit !== false) {
			$this->db->limit($limit);
		}
		$this->db->order_by('date', $orderby);
		return $this->db->get()->result();
		
	}
	
	/**
	 * get_forum_by_topic_id function.
	 * 
	 * @access public
	 * @param mixed $topic_id
	 * @return void
	 */
	public function get_forum_by_topic_id($topic_id) {
		
		$this->db->select('forum_id');
		$this->db->from('topics');
		$this->db->where('id', $topic_id);
		$forum_id = $this->db->get()->row('forum_id');
		return $this->get_forum($forum_id);
		
	}
	
	/**
	 * count_user_posts function.
	 * 
	 * @access public
	 * @param mixed $user_id
	 * @return int
	 */
	public function count_user_posts($user_id) {
		
		$this->db->select('id');
		$this->db->from('posts');
		$this->db->where('author_id', $user_id);
		return count($this->db->get()->result());
		
	}
	
	/**
	 * count_user_topics function.
	 * 
	 * @access public
	 * @param mixed $user_id
	 * @return int
	 */
	public function count_user_topics($user_id) {
		
		$this->db->select('id');
		$this->db->from('topics');
		$this->db->where('author_id', $user_id);
		return count($this->db->get()->result());
		
	}
	
	/**
	 * count_posts function.
	 * 
	 * @access public
	 * @param mixed $topic_id
	 * @return int
	 */
	public function count_posts($topic_id) {
		
		$this->db->select('id');
		$this->db->from('posts');
		$this->db->where('topic_id', $topic_id);
		return count($this->db->get()->result());
		
	}
	
	/**
	 * count_forum_posts function.
	 * 
	 * @access public
	 * @param mixed $forum_id
	 * @return int
	 */
	public function count_forum_posts($forum_id) {

		$this->db->select('posts.id');
		$this->db->from('posts');
		$this->db->join('topics', 'posts.topic_id = topics.id');
		$this->db->where('topics.forum_id', $forum_id);
		$this->db->group_by('posts.id');
		return count($this->db->get()->result());
		
	}
	
	/**
	 * create_post function.
	 * 
	 * @access public
	 * @param mixed $topic_id
	 * @param mixed $author_id
	 * @param mixed $content
	 * @return bool
	 */
	public function create_post($topic_id, $author_id, $content) {
		
		$data = array(
			'topic_id'  => $topic_id,
			'author_id' => $author_id,
			'content'   => $content
		);
		
		if ($this->db->insert('posts', $data)) {
			return true;
		}
		return false;
		
	}
	
	/**
	 * create_topic function.
	 * 
	 * @access public
	 * @param mixed $forum_id
	 * @param mixed $author_id
	 * @param mixed $title
	 * @param mixed $slug
	 * @param mixed $content
	 * @return bool
	 */
	public function create_topic($forum_id, $author_id, $title, $slug, $content) {
		
		$topic_data = array(
			'forum_id'  => $forum_id,
			'author_id' => $author_id,
			'title'     => $title,
			'slug'      => $slug
		);
		
		if ($this->db->insert('topics', $topic_data)) {
			$topic_id = $this->db->insert_id();
			if ($this->create_post($topic_id, $author_id, $content)) {
				return true;
			}
			return false;
		}
		return false;
		
	}
	
	/**
	 * create_forum function.
	 * 
	 * @access public
	 * @param mixed $title
	 * @param mixed $slug
	 * @param mixed $description
	 * @return bool
	 */
	public function create_forum($title, $slug, $description) {
		
		$forum_data = array(
			'title'       => $title,
			'slug'        => $slug,
			'description' => $description
		);
		
		if ($this->db->insert('forums', $forum_data)) {
			return true;
		}
		return false;
		
	}
	
	public function edit_post($post_id, $content) {
		
		$data = array(
			'content' => $content,
			'updated' => '1'
		);
		
		$this->db->from('posts');
		$this->db->where('id', $post_id);
		
		
	}

}