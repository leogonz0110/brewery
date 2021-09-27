<?php
class Brewery_Activator {
	
	public static function activate_brewery_post() {
		$labels = array(
			'name'               => _x( 'Breweries', 'post type general name' ),
			'singular_name'      => _x( 'Brewery', 'post type singular name' ),
			'add_new'            => _x( 'Add New', 'book' ),
			'add_new_item'       => __( 'Add New Brewery' ),
			'edit_item'          => __( 'Edit Brewery' ),
			'new_item'           => __( 'New Brewery' ),
			'all_items'          => __( 'All Breweries' ),
			'view_item'          => __( 'View Brewery' ),
			'search_items'       => __( 'Search Breweries' ),
			'not_found'          => __( 'No Breweries found' ),
			'not_found_in_trash' => __( 'No Breweries found in the Trash' ), 
			'parent_item_colon'  => '’',
			'menu_name'          => 'Breweries'
		  );
		  $args = array(
			'labels'        => $labels,
			'description'   => 'Holds breweries specific data',
			'public'        => true,
			'menu_position' => 5,
			'supports'      => array( 'title', 'thumbnail', 'custom-fields', 'editor' ),
			'has_archive'   => true,
		  );
		  register_post_type( 'brewery', $args ); 
	}

    public static function register_taxonomy() {
        $labels = array(
			'name'              => _x( 'Brewery Categories', 'taxonomy general name' ),
			'singular_name'     => _x( 'Brewery Category', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Brewery Categories' ),
			'all_items'         => __( 'All Brewery Categories' ),
			'parent_item'       => __( 'Parent Brewery Category' ),
			'parent_item_colon' => __( 'Parent Brewery Category:' ),
			'edit_item'         => __( 'Edit Brewery Category' ), 
			'update_item'       => __( 'Update Brewery Category' ),
			'add_new_item'      => __( 'Add New Brewery Category' ),
			'new_item_name'     => __( 'New Brewery Category' ),
			'menu_name'         => __( 'Brewery Categories' ),
		  );
		  $args = array(
			'labels' => $labels,
			'hierarchical' => true,
		  );
		  register_taxonomy( 'brewery_category', 'brewery', $args );
    }

	public static function import_breweries() {
		$max = 100;
		$ctr = 0;
		$breweries = array();

		for($page = 1; $page <= 2; $page++) {
			$ch = curl_init('https://api.openbrewerydb.org/breweries?page='. $page .'&per_page='. $max);
			curl_setopt(
				$ch, 
				CURLOPT_HTTPHEADER,
				array(
					'Content-Type: application/json'
				)
			);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			$response = curl_exec($ch);
			$breweryApi = json_decode($response);
			foreach($breweryApi as $index => $brewery) {
				array_push($breweries, $brewery);
			}
		}
		
		curl_close($ch);

		foreach($breweries as $index => $brewery) {
			wp_insert_post( array(
				'post_title'    => $brewery->name,
				'post_type'     => 'brewery',
				'post_status'   => 'publish',
				'meta_input'    => array(
					'id' => $brewery->id,
					'brewery_type' => $brewery->brewery_type,
					'street' => $brewery->street,
					'address_2' => $brewery->address_2,
					'address_3' => $brewery->address_3,
					'city' => $brewery->city,
					'state' => $brewery->state,
					'county_province' => $brewery->county_province,
					'postal_code' => $brewery->postal_code,
					'country' => $brewery->country,
					'longitude' => $brewery->longitude,
					'latitude' => $brewery->latitude,
					'phone' => $brewery->phone,
					'website_url' => $brewery->website_url,
				)
			) ); 
			$ctr++;
		}
	}
}