<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Install_model class.
 * 
 * @extends CI_Model
 */
class Install_model extends CI_Model {

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
	 * create_database function.
	 * 
	 * @access  public
	 * @param   string $database_name
	 * @return  bool
	 */
	public function create_database($database_name) {
		
		$this->load->database();
		$this->load->dbforge();
		
		if ($this->dbforge->create_database($database_name)) {
			$find    = "'database' =>";
			$replace = "\t" . "'database' => '" . $database_name . "'," . "\n";
			
			if ($this->edit_database_config_file($find, $replace) === true) {
				return true;
			}
		}
		return false;
		
	}
	
	/**
	 * create_tables function.
	 * 
	 * @access  public
	 * @param   string $database_name
	 * @return  bool
	 */
	public function create_tables($database_name) {
		
		$this->load->database();
		$this->load->dbforge();
		
		// create sessions table
		$this->db->query('USE ' . $database_name);
		$sql = "
			CREATE TABLE IF NOT EXISTS `ci_sessions` (
			  `id` varchar(40) NOT NULL,
			  `ip_address` varchar(45) NOT NULL,
			  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
			  `data` blob NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// create forums table
		$this->db->query('USE ' . $database_name);
		$sql = "
			CREATE TABLE IF NOT EXISTS `forums` (
			  `id` int(11) unsigned NOT NULL,
			  `title` varchar(255) NOT NULL DEFAULT '',
			  `slug` varchar(255) NOT NULL DEFAULT '',
			  `description` varchar(255) DEFAULT NULL,
			  `created_at` datetime NOT NULL,
			  `updated_at` datetime DEFAULT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// create options table
		$this->db->query('USE ' . $database_name);
		$sql = "
			CREATE TABLE IF NOT EXISTS `options` (
			  `id` int(11) unsigned NOT NULL,
			  `name` varchar(255) NOT NULL DEFAULT '',
			  `value` varchar(255) NOT NULL DEFAULT ''
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// create posts table
		$this->db->query('USE ' . $database_name);
		$sql = "
			CREATE TABLE IF NOT EXISTS `posts` (
			  `id` int(11) unsigned NOT NULL,
			  `content` longtext NOT NULL,
			  `user_id` int(11) unsigned NOT NULL,
			  `topic_id` int(11) unsigned NOT NULL,
			  `created_at` datetime NOT NULL,
			  `updated_at` datetime DEFAULT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// create topics table
		$this->db->query('USE ' . $database_name);
		$sql = "
			CREATE TABLE IF NOT EXISTS `topics` (
			  `id` int(11) unsigned NOT NULL,
			  `title` varchar(255) NOT NULL DEFAULT '',
			  `slug` varchar(255) NOT NULL DEFAULT '',
			  `created_at` datetime NOT NULL,
			  `updated_at` datetime DEFAULT NULL,
			  `user_id` int(11) unsigned NOT NULL,
			  `forum_id` int(11) unsigned NOT NULL,
			  `is_sticky` tinyint(1) NOT NULL DEFAULT '0'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// create users table
		$this->db->query('USE ' . $database_name);
		$sql = "
			CREATE TABLE IF NOT EXISTS `users` (
			  `id` int(11) unsigned NOT NULL,
			  `username` varchar(255) NOT NULL DEFAULT '',
			  `email` varchar(255) NOT NULL DEFAULT '',
			  `password` varchar(255) NOT NULL DEFAULT '',
			  `avatar` varchar(255) DEFAULT 'default.jpg',
			  `created_at` datetime NOT NULL,
			  `updated_at` datetime DEFAULT NULL,
			  `updated_by` int(11) unsigned NOT NULL,
			  `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `is_moderator` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `is_confirmed` tinyint(1) unsigned NOT NULL DEFAULT '0',
			  `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0'
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// alter session table
		$this->db->query('USE ' . $database_name);
		$sql = "
			ALTER TABLE `ci_sessions`
			  ADD PRIMARY KEY (`id`),
			  ADD KEY `ci_sessions_timestamp` (`timestamp`);		
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// alter forums table
		$this->db->query('USE ' . $database_name);
		$sql = "
			ALTER TABLE `forums`
			  ADD PRIMARY KEY (`id`);		
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// alter options table
		$this->db->query('USE ' . $database_name);
		$sql = "
			ALTER TABLE `options`
			  ADD PRIMARY KEY (`id`);		
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// alter posts table
		$this->db->query('USE ' . $database_name);
		$sql = "
			ALTER TABLE `posts`
			  ADD PRIMARY KEY (`id`);		
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// alter topics table
		$this->db->query('USE ' . $database_name);
		$sql = "
			ALTER TABLE `topics`
			  ADD PRIMARY KEY (`id`);		
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// alter users table
		$this->db->query('USE ' . $database_name);
		$sql = "
			ALTER TABLE `users`
			  ADD PRIMARY KEY (`id`);		
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// alter forums table
		$this->db->query('USE ' . $database_name);
		$sql = "
			ALTER TABLE `forums`
			  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;		
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// alter options table
		$this->db->query('USE ' . $database_name);
		$sql = "
			ALTER TABLE `options`
			  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;		
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// alter posts table
		$this->db->query('USE ' . $database_name);
		$sql = "
			ALTER TABLE `posts`
			  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;		
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// alter topics table
		$this->db->query('USE ' . $database_name);
		$sql = "
			ALTER TABLE `topics`
			  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;		
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// alter users table
		$this->db->query('USE ' . $database_name);
		$sql = "
			ALTER TABLE `users`
			  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;		
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// if everything is ok, return true
		return true;

	}

	/**
	 * edit_database_config_file function.
	 * 
	 * @access public
	 * @param string $find
	 * @param string $replace
	 * @return bool
	 */
	public function edit_database_config_file($find, $replace) {
		
		$reading = fopen(APPPATH . 'config/database.php', 'r');
		$writing = fopen(APPPATH . 'config/database.tmp', 'w');
		
		$replaced = false;
		
		while (!feof($reading)) {
			
			$line = fgets($reading);
			if (stristr($line, $find)) {
				$line = $replace;
				$replaced = true;
			}
			fputs($writing, $line);
			
		}
		fclose($reading); fclose($writing);
		// might as well not overwrite the file if we didn't replace anything
		if ($replaced) {
			rename(APPPATH . 'config/database.tmp', APPPATH . 'config/database.php');
			return true;
		} else {
			unlink(APPPATH . 'config/database.tmp');
			return false;
		}		
		
	}
	
	/**
	 * edit_main_config_file function.
	 * 
	 * @access public
	 * @param string $find
	 * @param string $replace
	 * @return bool
	 */
	public function edit_main_config_file($find, $replace) {
		
		$reading = fopen(APPPATH . 'config/config.php', 'r');
		$writing = fopen(APPPATH . 'config/config.tmp', 'w');
		
		$replaced = false;
		
		while (!feof($reading)) {
			
			$line = fgets($reading);
			if (stristr($line, $find)) {
				$line = $replace;
				$replaced = true;
			}
			fputs($writing, $line);
			
		}
		fclose($reading); fclose($writing);
		// might as well not overwrite the file if we didn't replace anything
		if ($replaced) {
			rename(APPPATH . 'config/config.tmp', APPPATH . 'config/config.php');
			return true;
		} else {
			unlink(APPPATH . 'config/config.tmp');
			return false;
		}		
		
	}
	
	/**
	 * edit_forum_config_file function.
	 * 
	 * @access public
	 * @param string $find
	 * @param string $replace
	 * @return bool
	 */
	public function edit_forum_config_file($find, $replace) {
		
		$reading = fopen(APPPATH . 'config/forum.php', 'r');
		$writing = fopen(APPPATH . 'config/forum.tmp', 'w');
		
		$replaced = false;
		
		while (!feof($reading)) {
			
			$line = fgets($reading);
			if (stristr($line, $find)) {
				$line = $replace;
				$replaced = true;
			}
			fputs($writing, $line);
			
		}
		fclose($reading); fclose($writing);
		// might as well not overwrite the file if we didn't replace anything
		if ($replaced) {
			rename(APPPATH . 'config/forum.tmp', APPPATH . 'config/forum.php');
			return true;
		} else {
			unlink(APPPATH . 'config/forum.tmp');
			return false;
		}
		
	}
	
	/**
	 * edit_routes_config_file function.
	 * 
	 * @access public
	 * @param string $find
	 * @param string $replace
	 * @return bool
	 */
	public function edit_routes_config_file($find, $replace) {
		
		$reading = fopen(APPPATH . 'config/routes.php', 'r');
		$writing = fopen(APPPATH . 'config/routes.tmp', 'w');
		
		$replaced = false;
		
		while (!feof($reading)) {
			
			$line = fgets($reading);
			if (stristr($line, $find)) {
				$line = $replace;
				$replaced = true;
			}
			fputs($writing, $line);
			
		}
		fclose($reading); fclose($writing);
		// might as well not overwrite the file if we didn't replace anything
		if ($replaced) {
			rename(APPPATH . 'config/routes.tmp', APPPATH . 'config/routes.php');
			return true;
		} else {
			unlink(APPPATH . 'config/routes.tmp');
			return false;
		}
		
	}
	
	/**
	 * test_database_connexion function.
	 * 
	 * @access public
	 * @param string $hostname
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */
	public function test_database_connexion($hostname, $username, $password) {
		
		$this->load->database();
		$this->load->dbforge();
		
		// create connection
		$conn = new mysqli($hostname, $username, $password);
		
		// check connection
		if ($conn->connect_error) {
			return false;
		} 
		return true;
		
	}
	
	/**
	 * delete_installation_files function.
	 * 
	 * @access public
	 * @return true ???? MUST FIX
	 */
	public function delete_installation_files() {
		
		$installation_items = array(
			APPPATH . 'controllers/Install.php',
			APPPATH . 'views/install',
			APPPATH . 'models/Install_model.php'
		);
		
		foreach ($installation_items as $installation_item) {
			$this->delete_files($installation_item);
		}
		
		return true;
		
	}
	
	/**
	 * delete_files function.
	 * 
	 * @access private
	 * @param string $target
	 * @return void
	 */
	private function delete_files($target) {
		
		if (is_dir($target)) {
			$files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned
			foreach($files as $file) {
				$this->delete_files($file);
			}
			if(file_exists($target) && is_dir($target)) {
				rmdir($target);
			}
		} elseif (is_file($target)) {
			unlink( $target );
		}
		
	}

}