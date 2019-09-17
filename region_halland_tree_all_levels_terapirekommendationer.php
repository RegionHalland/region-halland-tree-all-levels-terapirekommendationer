<?php

	/**
	 * @package Region Halland Tree All Levels Terapirekommendationer
	 */
	/*
	Plugin Name: Region Halland Tree All Levels terapirekommendationer
	Description: Front-end-plugin för hela siten som tree-menu
	Version: 1.0.0
	Author: Roland Hydén
	License: MIT
	Text Domain: regionhalland
	*/

	// Return all page childs to a page
	function get_region_halland_tree_all_levels_terapirekommendationer()
	{
		
		// Datbasvariabler
		$servername = ENV('DB_HOST');
		$username = ENV('DB_USER');
		$password = ENV('DB_PASSWORD');
		$dbname = ENV('DB_NAME');
		
		// Skapa databas koppling
	 	$conn = mysqli_connect($servername, $username, $password, $dbname);

	 	// Hämta alla sidor
	 	$sql = "SELECT ";
		$sql .= "ID, ";
		$sql .= "post_title, ";
		$sql .= "post_parent ";
		$sql .= "FROM wp_posts ";
		$sql .= "WHERE ";
		$sql .= "post_status = 'publish' ";
		$sql .= "AND ";
		$sql .= "post_type = 'page'";
		$sql .= "AND ";
		$sql .= "post_parent <> 0";
		$sql .= "ORDER BY ";
		$sql .= "menu_order";
		$result = mysqli_query($conn, $sql);
		$myData = array();
		while($row = mysqli_fetch_assoc($result)) {
		    $myID = $row['ID'];
		    $myTitle = utf8_encode($row['post_title']);
		    $myParent = $row['post_parent'];
		    array_push($myData, array(
	           'ID' => $myID,
	           'post_title' => $myTitle,
	           'post_parent'  => $myParent,
               'page_url' => get_permalink($myID)
	        ));
		}

		$myDataFinal = region_halland_tree_all_levels_terapirekommendationer_buildtree($myData);
		
		// Returnera träd
		return $myDataFinal;
	}

	// Funktion för att bygga träd utifrån array med parent/child
	function region_halland_tree_all_levels_terapirekommendationer_buildtree($data, $post_parent = 0, $tree = array())
	{
	    foreach($data as $idx => $row)
	    {
	        if($row['post_parent'] == $post_parent)
	        {
	            foreach($row as $k => $v)
	                $tree[$row['ID']][$k] = $v;
	            unset($data[$idx]);
	            $tree[$row['ID']]['children'] = region_halland_tree_all_levels_terapirekommendationer_buildtree($data, $row['ID']);
	        }
	    }
	    ksort($tree);
	    return $tree;
	}

	// Metod som anropas när pluginen aktiveras
	function region_halland_tree_all_levels_terapirekommendationer_activate() {
		// Ingenting just nu...
	}

	// Metod som anropas när pluginen avaktiveras
	function region_halland_tree_all_levels_terapirekommendationer_deactivate() {
		// Ingenting just nu...
	}
	
	// Vilken metod som ska anropas när pluginen aktiveras
	register_activation_hook( __FILE__, 'region_halland_tree_all_levels_terapirekommendationer_activate');
	
	// Vilken metod som ska anropas när pluginen avaktiveras
	register_deactivation_hook( __FILE__, 'region_halland_tree_all_levels_terapirekommendationer_deactivate');

?>