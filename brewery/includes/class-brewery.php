<?php
class Brewery {

    public function __construct() {
		if ( defined( 'BREWERY_VERSION' ) ) {
			$this->version = BREWERY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'brewery';
    }

    public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

    public function run() {
        
	}
}