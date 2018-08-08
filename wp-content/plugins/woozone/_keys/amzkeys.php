<?php
if ( !defined('ABSPATH') ) {
	die;
}

/**
 * aaWoozoneAmzKeysLib
 * http://www.aa-team.name
 * =======================
 *
 * @author       AA-Team
 */
if (!class_exists('aaWoozoneAmzKeysLib')) { class aaWoozoneAmzKeysLib {
	
	// plugin global object
	public $the_plugin = null;
	private $P = array();


	/**
	 * Constructor
	 */
	public function __construct( $parent=null ) {
		//global $WooZone;
		$this->the_plugin = $parent; //$WooZone;
	}
	
	public function get_available_access_key( $used_keys=array() ) {
		global $wpdb;

		$table = $wpdb->prefix . 'amz_amzkeys';

		// AND a.locked='N'
		$order_by = "a.locked ASC, a.last_request_time ASC, a.ratio_success DESC, a.id DESC";
		if ( !empty($used_keys) && is_array($used_keys) ) {
			$used_keys_ = implode(',', array_map(array($this->the_plugin, 'prepareForInList'), $used_keys));
			$row = $wpdb->get_row( "SELECT a.id, a.access_key, a.secret_key FROM " . $table . " as a WHERE 1=1 AND a.publish='Y' and a.id NOT IN ($used_keys_) ORDER BY $order_by LIMIT 1;", ARRAY_A );			
		}
		else {
			$row = $wpdb->get_row( "SELECT a.id, a.access_key, a.secret_key FROM " . $table . " as a WHERE 1=1 AND a.publish='Y' ORDER BY $order_by LIMIT 1;", ARRAY_A );
		}
		//$row_id = (int) $row['id'];
		return $row;
	}

	/*public function set_current_access_key_status( $id=0, $params=array(), $params_format=array() ) {
		global $wpdb;

		$table = $wpdb->prefix . 'amz_amzkeys';
		$ret = $wpdb->update( 
			$table, 
			$params, 
			array( 'id' => $id ), 
			$params_format, 
			array( '%d' ) 
		);
		return $ret;
	}*/
	public function set_current_access_key_status( $id=0, $pms=array() ) {
		global $wpdb;

		$table = $wpdb->prefix . 'amz_amzkeys';
		//$q = "UPDATE $table as a SET a.lock_time = NOW(), a.locked = 'N' WHERE 1=1 and a.id = %s;";

		$qpart = array();
		$qpart[] = "UPDATE $table as a SET";
		if ( !empty($pms) ) {
			foreach ($pms as $key => $val) {
				switch ($key) {
					case 'last_request_id':
					case 'locked':
					case 'last_request_status':
						$qpart[] = ", a.$key = '$val'";
						break;

					case 'last_request_output':
					case 'last_request_input':
						$qpart[] = ", a.$key = '" . esc_sql( $val ) . "'";
						break;
						
					case 'last_request_time':
					case 'lock_time':
						$qpart[] = ", a.$key = NOW()";
						break;
						
					case 'nb_requests':
					case 'nb_requests_valid':
						$qpart[] = ", a.$key = a.$key + 1";
						break;

					case 'ratio_success':
						$qpart[] = ", a.$key = ROUND( ( a.nb_requests_valid * 100 ) / a.nb_requests, 2 )";
						break;
				}
			}
		}
		//$qpart[] = "WHERE 1=1 and a.id = %s;";
		$qpart[] = "WHERE 1=1 and a.id = '$id';";

		$q = implode(' ', $qpart);
		$q = str_replace('SET ,', 'SET', $q);
		//var_dump('<pre>', $q , '</pre>');
		//$q = $wpdb->prepare( $q, $id );
		//var_dump('<pre>', $q , '</pre>');
		$res = $wpdb->query( $q );
		return $res;
	}

	public function lock_current_access_key( $id=0, $pms=array() ) {
		$pms = array_replace_recursive( array(
			'locked' 				=> 'Y',
			'lock_time' 			=> true,
		), $pms);

		return $this->set_current_access_key_status( $id, $pms );
	}

	public function unlock_current_access_key( $id=0, $pms=array() ) {
		$pms = array_replace_recursive( array(
			'locked' 				=> 'N',
			'lock_time' 			=> true,
			'nb_requests' 			=> true,
			'last_request_time' 	=> true,
		), $pms);

		return $this->set_current_access_key_status( $id, $pms );
	}



	public function get_available_keys() {
		global $wpdb;

		$table = $wpdb->prefix . 'amz_amzkeys';

		$query = "SELECT a.* FROM $table as a WHERE 1=1 ORDER BY a.id ASC;";
		$res = $wpdb->get_results( $query, ARRAY_A );
		return $res;
	}
		
	public function get_key_by_id( $id=0 ) {
		global $wpdb;

		$table = $wpdb->prefix . 'amz_amzkeys';

		$query = "SELECT a.* FROM $table as a WHERE 1=1 AND a.id = %s LIMIT 1;";
		$query = $wpdb->prepare( $query, $id );
		$res = $wpdb->get_row( $query, ARRAY_A );
		return $res;
	}

	public function get_key_by_aws( $access_key='', $secret_key='' ) {
		global $wpdb;

		$table = $wpdb->prefix . 'amz_amzkeys';

		$query = "SELECT a.* FROM $table as a WHERE 1=1 AND a.access_key = %s AND a.secret_key = %s LIMIT 1;";
		$query = $wpdb->prepare( $query, $access_key, $secret_key );
		$res = $wpdb->get_row( $query, ARRAY_A );
		return $res;
	}

	public function publish_key( $id=0 ) {
		global $wpdb;

		$table = $wpdb->prefix . 'amz_amzkeys';

		$row = $this->get_key_by_id( $id );
		if ( isset($row['id']) ) {

			$row_id = (int) $row['id'];

			// publish/unpublish
			$res = $wpdb->update( 
				$table, 
				array( 
					'publish' => 'Y' == $row['publish'] ? 'N' : 'Y'
				), 
				array( 'id' => $row_id ), 
				array( 
					'%s'
				), 
				array( '%d' ) 
			);
			return $res;
		}
		return false;
	}

	public function delete_key( $id=0 ) {
		global $wpdb;

		$table = $wpdb->prefix . 'amz_amzkeys';

		$query = "DELETE a FROM $table as a WHERE 1=1 AND a.id = %d;";
		$query = $wpdb->prepare( $query, $id );
		$res = $wpdb->query( $query );
		return $res;
	}

	public function add_key_indb( $AccessKeyID, $SecretAccessKey ) {
		global $wpdb;

		$table = $wpdb->prefix . 'amz_amzkeys';

		$insert_id = $this->the_plugin->db_custom_insert(
			$table,
			array(
				'values' => array(
					'access_key'		=> $AccessKeyID,
					'secret_key' 		=> $SecretAccessKey,
				),
				'format' => array(
					'%s', '%s'
				)
			),
			true // use <insert ignore>
		);
		return $insert_id;
	}
} }

?>