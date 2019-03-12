<?php

class ActivateClass {

	public function activate() {
        global $wpdb;
        $table_options = $wpdb->prefix . "custom_404_pro_options";
        $table_logs = $wpdb->prefix . "custom_404_pro_logs";
        $is_table_options_query = "SHOW TABLES LIKE '" . $table_options . "';";
        $is_table_logs_query = "SHOW TABLES LIKE '" . $table_options . "';";
        $is_table_options = $wpdb->query($is_table_options_query);
        $is_table_logs = $wpdb->query($is_table_logs_query);
        if(empty($is_table_options) && empty($is_table_logs)) {
            self::create_tables();
            self::initialize_options();
        }
	}

	function create_tables() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_logs = $wpdb->prefix . "custom_404_pro_logs";
		$sql_logs = "CREATE TABLE $table_logs (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			ip text,
			path text,
			referer text,
			user_agent text,
			created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  	PRIMARY KEY (id)
		) $charset_collate;";
		$table_options = $wpdb->prefix . "custom_404_pro_options";
		$sql_options = "CREATE TABLE $table_options (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name text,
			value text,
		  	created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		  	updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  	PRIMARY KEY (id)
		) $charset_collate;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql_logs);
		dbDelta($sql_options);
	}

	function initialize_options() {
		global $wpdb;
		$table_options = $wpdb->prefix . "custom_404_pro_options";
		$sql = "INSERT INTO " . $table_options . " (name, value) VALUES ";
		$sql .= "('mode', NULL),";
		$sql .= "('mode_page', NULL),";
		$sql .= "('mode_url', NULL),";
		$sql .= "('send_email', FALSE),";
		$sql .= "('logging_enabled', FALSE),";
		$sql .= "('redirect_error_code', 302)";
		$wpdb->query($sql);
	}
}