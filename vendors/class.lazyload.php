<?php
class SatelliteLazyLoad extends SatellitePlugin {
	
	/**
	 * DB table name to lazyloader on
	 *
	 */
	var $table = '';
	
	/**
	 * Fields for SELECT query
	 * Only these fields will be fetched.
	 * Use asterix for all available fields
	 *
	 */
	var $fields = '*';
		
	/**
	 * Records to prior to lazy loading
	 *
	 */
	var $per_page = 10;
	
	/**
	 * WHERE conditions
	 * This should be an array
	 *
	 */
	var $where = '';
	
	/**
	 * ORDER condition
	 *
	 */
	var $order = array('modified', "DESC");
	
	var $plugin_url = '';
	var $sub = '';
	var $parent = '';
	
	var $allcount = 0;
	var $allRecords = array();
	
	var $lazyloader = '';
	
	function SatelliteLazyLoader($table = '', $fields = '', $sub = '', $parent = '') {
		$this -> sub = $sub;
		$this -> parentd = $parent;
	
		if (!empty($table)) {
			$this -> table = $table;
		}
		
		if (!empty($fields)) {
			$this -> fields = $fields;
		}
	}
	
	function start_loading() {
		global $wpdb;

    if (!empty($this -> fields)) {
			if (is_array($this -> fields)) {
				$this -> fields = implode(", ", $this -> fields);
			}
		}
		
		$query = "SELECT " . $this -> fields . " FROM `" . $this -> table . "`";
		$countquery = "SELECT COUNT(*) FROM `" . $this -> table . "`";

		//check if some conditions where passed.
		if (!empty($this -> where)) {
			//append the "WHERE" command to the query
			$query .= " WHERE";
			$countquery .= " WHERE";
			$c = 1;
			
			foreach ($this -> where as $key => $val) {
				if (!empty($val) && is_array($val)) {
					$k = 1;
				
					foreach ($val as $vkey => $vval) {
						if (eregi("LIKE", $val)) {
							$query .= " `" . $key . "` " . $vval . "";	
							$countquery .= " `" . $key . "` " . $vval . "";
						} elseif (preg_match("/SE (.*)/si", $vval, $vmatches)) {
							if (!empty($vmatches[1])) {
								$query .= " `" . $key . "` <= " . $vmatches[1] . "";
								$countquery .= " `" . $key . "` <= " . $vmatches[1] . "";;
							}
						} elseif (preg_match("/LE (.*)/si", $vval, $vmatches)) {
							if (!empty($vmatches[1])) {
								$query .= " `" . $key . "` >= " . $vmatches[1] . "";
								$countquery .= " `" . $key . "` >= " . $vmatches[1] . "";
							}
						} else {
							$query .= " `" . $key . "` = '" . $vval . "'";
							$countquery .= " `" . $key . "` = '" . $vval . "'";
						}
						
						if ($k < count($val)) {
							$query .= " AND";
							$countquery .= " AND";
						}
						
						$k++;
						$vmatches = false;
					}
				} else {
					if (eregi("LIKE", $val)) {
						$query .= " `" . $key . "` " . $val . "";	
						$countquery .= " `" . $key . "` " . $val . "";
					} elseif (preg_match("/SE (.*)/si", $val, $vmatches)) {
						if (!empty($vmatches[1])) {
							$query .= " `" . $key . "` <= " . $vmatches[1] . "";
							$countquery .= " `" . $key . "` <= " . $vmatches[1] . "";
						}
					} elseif (preg_match("/LE (.*)/si", $val, $vmatches)) {
						if (!empty($vmatches[1])) {
							$query .= " `" . $key . "` >= " . $vmatches[1] . "";
							$countquery .= " `" . $key . "` >= " . $vmatches[1] . "";
						}
					} else {
						$query .= " `" . $key . "` = '" . $val . "'";
						$countquery .= " `" . $key . "` = '" . $val . "'";
					}
					
					if ($c < count($this -> where)) {
						$query .= " AND";
						$countquery .= " AND";
					}
					
					$c++;
					$vmatches = false;
				}
			}
		}
		
		$r = 1;
		
		list($osortby, $osort) = $this -> order;
		$query .= " ORDER BY `" . $osortby . "` " . $osort;
		//echo $query;
		
		$records = $wpdb -> get_results($query);
		$records_count = count($records);
		
		$pageparam = (!empty($this -> sub) && $this -> sub == "N") ? '' : 'page=' . $this -> pre . $this -> sub . '&amp;';
		$pageparam = '';
		$search = (empty($this -> searchterm)) ? '' : '&amp;' . $this -> pre . 'searchterm=' . urlencode($this -> searchterm);
    
    $this -> lazyloader .= 'The Lazy Loader!';
		
		return $records;
	}
	
}
?>