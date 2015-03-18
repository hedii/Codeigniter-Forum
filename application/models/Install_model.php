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
		$this->load->dbforge();
		
	}
	
	/**
	 * create_database function.
	 * 
	 * @access  public
	 * @param   string $database_name
	 * @return  bool
	 */
	public function create_database($database_name) {
		
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
		
		// create categories table
		$this->db->query('USE ' . $database_name);
		$sql = "
			CREATE TABLE `categories` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`title` int(11) NOT NULL,
				`position` int(11) DEFAULT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `title` (`title`)
			);
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// create sessions table
		$this->db->query('USE ' . $database_name);
		$sql = "
			CREATE TABLE `ci_sessions` (
				`id` varchar(40) NOT NULL,
				`ip_address` varchar(45) NOT NULL,
				`timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
				`data` blob DEFAULT '' NOT NULL,
				PRIMARY KEY (id),
				KEY `ci_sessions_timestamp` (`timestamp`)
			);
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// alter session table
		$this->db->query('USE ' . $database_name);
		$sql = "ALTER TABLE ci_sessions ADD CONSTRAINT ci_sessions_id_ip UNIQUE (id, ip_address);";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// create users table
		$this->db->query('USE ' . $database_name);
		$sql = "
			CREATE TABLE `users` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`username` varchar(20) NOT NULL DEFAULT '',
				`password` varchar(255) NOT NULL DEFAULT '',
				`email` varchar(255) NOT NULL DEFAULT '',
				`avatar` varchar(255) NOT NULL DEFAULT 'default.jpg',
				`registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`is_confirmed` tinyint(1) unsigned NOT NULL DEFAULT '0',
				`is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
				`is_deleted` tinyint(1) NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`),
				UNIQUE KEY `username` (`username`),
				UNIQUE KEY `email` (`email`)
			);
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// create forums table
		$this->db->query('USE ' . $database_name);
		$sql = "
			CREATE TABLE `forums` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`title` varchar(255) NOT NULL DEFAULT '',
				`slug` varchar(255) NOT NULL DEFAULT '',
				`description` varchar(255) DEFAULT NULL,
				`category_id` int(11) unsigned DEFAULT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `slug` (`slug`),
				UNIQUE KEY `title` (`title`),
				KEY `category_id` (`category_id`),
				CONSTRAINT `forums_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
			);
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// create topics table
		$this->db->query('USE ' . $database_name);
		$sql = "
			CREATE TABLE `topics` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`title` varchar(255) NOT NULL DEFAULT '',
				`slug` varchar(255) NOT NULL,
				`author_id` int(11) unsigned NOT NULL,
				`forum_id` int(11) unsigned DEFAULT NULL,
				`date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`is_sticky` tinyint(1) unsigned NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`),
				UNIQUE KEY `slug` (`slug`),
				UNIQUE KEY `title` (`title`),
				KEY `author_id` (`author_id`),
				KEY `forum_id` (`forum_id`),
				CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
				CONSTRAINT `topics_ibfk_2` FOREIGN KEY (`forum_id`) REFERENCES `forums` (`id`)
			);
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// create posts table
		$this->db->query('USE ' . $database_name);
		$sql = "
			CREATE TABLE `posts` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`topic_id` int(11) unsigned NOT NULL,
				`author_id` int(11) unsigned NOT NULL,
				`content` longtext CHARACTER SET latin1 NOT NULL,
				`date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`updated` tinyint(1) NOT NULL DEFAULT '0',
				`updated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY (`id`),
				KEY `topic_id` (`topic_id`),
				KEY `author_id` (`author_id`),
				CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`),
				CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
			);
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// create admin user
		$this->db->query('USE ' . $database_name);
		$password_hash = password_hash('admin', PASSWORD_BCRYPT);
		$sql = "
			INSERT INTO users (username, password, email, is_confirmed, is_admin)
			VALUES ('admin', '$password_hash', 'you@example.com', '1', '1');
		";
		if(!$this->db->query($sql)) {
			return false;
		}
		
		// if everything run ok, return true
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
		
		// create connection
		$conn = new mysqli($hostname, $username, $password);
		
		// check connection
		if ($conn->connect_error) {
			return false;
		} 
		return true;
		
	}
	
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