<?php
/*
Plugin Name: ConnectedFencePlugin
Plugin URI: https://github.com/Rufus31415/Connected-electric-fence-sensor
Description: Plugin Wordpress specifique permettant de s'interfacer avec le backend Sigfox et d'afficher les mesures provenants de capteurs connectés
Version: 0.1
Author: Rufus31415
License: MIT
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once plugin_dir_path( __FILE__ )."/ConnectedDevice.php";
foreach (glob(plugin_dir_path( __FILE__ )."/*.php") as $filename)
{
    include_once $filename;
}

/**
 * Classe Poll_Plugin
 * Déclare le plugin
 */
class CF_Plugin
{
    /**
     * Constructeur
     */
    public function __construct()
    {
		add_shortcode('add_device', array($this, 'add_device_form'));
		add_shortcode('see_device', array($this, 'see_device_form'));
		add_shortcode('FenceMap', array($this, 'FenceMap_form'));
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
    }

    /**
     * Fonction d'installation
     */
    public function install()
    {
    }

    public function uninstall()
    {
    }
	
	public function add_device_form($atts, $content){
		show_add_device_form();
	}
	
	public function see_device_form($atts, $content){
		global $wpdb;
		
		$user = wp_get_current_user();

		$resultats = $wpdb->get_results("SELECT sigfox_id FROM devices WHERE owner=".$user->ID) ;

		foreach ($resultats as $post) {
			show_see_device_form($post->sigfox_id);
		}
	}
	
	public function FenceMap_form($atts, $content){
		if(isset($atts["id"])){
			$attDevice = explode("/", $atts["id"]);
						
			$locations = array();
			
			foreach($attDevice as $att){
				$attExploded = explode(":", $att);
				
				$id = $attExploded[0];
				$latLong = explode(";", $attExploded[1]);
				$lat = $latLong[0];
				$long = $latLong[1];
				
				$device = new ConnectedFence($id);
				
				$data = $device->get_formated_API_data();
				

				$locations[] = new DeviceLocation($device, $lat, $long, $data[0]);
			}
			
			show_FenceMap_form($locations);
		}
	}
	
    /**
     * Register styles and scripts. The scripts are placed in the footer for compability issues.
     */
    function wp_enqueue_scripts()
    {
        wp_register_script( 'google_charts', 'https://www.gstatic.com/charts/loader.js');
        wp_enqueue_script( 'google_charts' );
		wp_enqueue_script( 'jquery' );
    }

}

new CF_Plugin();
