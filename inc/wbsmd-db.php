<?php

if ( ! defined('ABSPATH') ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists('WbsmdDB') ) {
	class WbsmdDB {

		public function __construct() { 
            define( "WBSMD_RM_GROUP_TABLE","relevance_monitoring_group" );
            define( "WBSMD_RM_TABLE","relevance_monitoring" );

            add_action ('after_switch_theme', [$this, 'create_relevance_monitoring_group_table'] );
            add_action( 'after_switch_theme', [$this, 'create_relevance_monitoring_table'] );
        }
        
        public function create_relevance_monitoring_group_table() {
            global $wpdb;
        
            $table_name = $wpdb->prefix . WBSMD_RM_GROUP_TABLE;
            
            $charset_collate = $wpdb->get_charset_collate();
            
            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                group_name varchar(255) NOT NULL,
                created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;";
            
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
        
        public function create_relevance_monitoring_table() {
            global $wpdb;
        
            $table_name = $wpdb->prefix . WBSMD_RM_TABLE;
            
            $charset_collate = $wpdb->get_charset_collate();
            
            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                group_id mediumint(9) NOT NULL,
                link varchar(255) NOT NULL,
                result text NOT NULL,
                PRIMARY KEY (id),
                FOREIGN KEY (group_id) REFERENCES {$wpdb->prefix}relevance_monitoring_group(id) ON DELETE CASCADE
            ) $charset_collate;";
            
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        public static function get_relevance_monitoring_records() {
            global $wpdb;
            $table_name = $wpdb->prefix . WBSMD_RM_GROUP_TABLE;
            $results = $wpdb->get_results("SELECT * FROM $table_name");
            return $results;
        }

        public static function delete_group( $id ) {
            global $wpdb;
            $table_name = $wpdb->prefix . WBSMD_RM_GROUP_TABLE;

            $wpdb->delete(
                $table_name,
                array(
                    'ID' => $id, 
                )
            );
            
            if ( $wpdb->last_error ) {
                error_log("Error creating relevance_monitoring table: " . $wpdb->last_error);
                return [
                    'success' => false,
                    'errors' => $wpdb->last_error
                ];
            }
        }

        static function create_relevance_group( string $group_name = 'no_name' ) {
            global $wpdb;
            $table_name = $wpdb->prefix . WBSMD_RM_GROUP_TABLE;
            $existing_link = $wpdb->get_var($wpdb->prepare(
                "SELECT group_name FROM $table_name WHERE group_name = %s",
                $group_name
            ));
            if ($existing_link) {
                return [
                    'success' => false,
                    'errors' => 'value_exist' 
                ];
            }
            $wpdb->insert(
                $table_name,
                ['group_name' => $group_name],
                ['%s']
            );
            if ( $wpdb->last_error ) {
                error_log("Error creating relevance_monitoring table: " . $wpdb->last_error);
                return [
                    'success' => false,
                    'errors' => $wpdb->last_error
                ];
            }
            return [
                'success' => true,
                'group_id' => $wpdb->insert_id
            ];
        }

        static function save_result_to_db( $group_id, $result ) {
            global $wpdb;
            $table_name = $wpdb->prefix . WBSMD_RM_TABLE;

            $wpdb->insert(
                $table_name,
                [
                    'group_id' => $group_id,
                    'link' => $result['link'],
                    'result' => wp_json_encode($result)
                ],
                [
                    '%d',
                    '%s',
                    '%s'
                ]
            );
            
            if ( $wpdb->last_error ) {
                error_log("Error creating relevance_monitoring table: " . $wpdb->last_error);
                return [
                    'success' => false,
                    'errors' => $wpdb->last_error
                ];
            }
            return true;
        }

        static function get_relevance_monitoring_records_by_group_id( $group_id ) {
            global $wpdb;
        
            $table_name = $wpdb->prefix . WBSMD_RM_TABLE;
        
            $sql = $wpdb->prepare(
                "SELECT * FROM $table_name WHERE group_id = %d",
                $group_id
            );
        
            $results = $wpdb->get_results($sql);
        
            return $results;
        }

        static function get_relevance_monitoring_group_record( $group_id ) {
            global $wpdb;
        
            $table_name = $wpdb->prefix . WBSMD_RM_GROUP_TABLE;
        
            $sql = $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d",
                $group_id
            );
        
            $group = $wpdb->get_results( $sql );
        
            return $group;
        }
	}
}