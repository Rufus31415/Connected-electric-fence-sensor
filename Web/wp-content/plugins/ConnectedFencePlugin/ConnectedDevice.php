<?php
// Représente un appareil connecté qui a la capacité de récupérer et convertir en JSON ses données depuis le backend Sigfox
abstract class ConnectedDevice{
	protected $_associatedAPIAccess;
	
	public $Registered = false;
	
	// String : ID du device expl : 1AD134
	public $id;
	
	public $name;
	
	public function __construct($id){
		global $wpdb;
		$resultats = $wpdb->get_results("SELECT APIAccess.login, devices.device_name, APIAccess.password FROM devices,APIAccess WHERE devices.sigfox_id='$id' AND devices.api_access_id=APIAccess.ID") ;
	
		$this->id = $id;
		$this->name = $id;
		
		if(count($resultats)==1){
			$this->Registered = true;
			$this->_associatedAPIAccess = new SigfoxAPIAccess($resultats[0]->login, $resultats[0]->password);
			$this->name = $resultats[0]->device_name;
		}
		else{
			$this->Registered = false;
		}
	}
	
	abstract public function extractData($RowHexaData);
	public function get_row_API_data($limit = null, $before = null){
		if(!$this->Registered) throw new Exception("Unable to get row API data for ".!$this->id);
		
		// Conctruction de l'URL
		$host = "https://backend.sigfox.com/api/devices/".$this->id."/messages";
		
		if($limit != null || $before != null) $host .= "?";
		if($limit != null) $host .= "limit=".$limit;
		if($limit != null && $before != null) $host .= "&";
		if($before != null) $host .= "before=".$before;
				
		$process = curl_init($host);

		// authentification
		curl_setopt($process, CURLOPT_USERPWD, $this->_associatedAPIAccess->get_CURLOPT_USERPWD());
		
		// curl_exec retourne une chaine de caractère
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

		$data = curl_exec($process);
		
		curl_close($process);
		
		return json_decode($data,true);
	}
	
	
	
	public function get_formated_API_data($limit = null, $before = null){
		$row_data = $this->get_row_API_data($limit, $before);
	
		$formated_data = array();
		
		foreach($row_data["data"] as $row_point){
			$value = $this->extractData($row_point["data"]);
			$formated_data[] = new DatedValue($value, $row_point["time"]);
		}
		
		return $formated_data;
	}
	
	
	public function get_JS_API_data($limit = null, $before = null){
		$formated_data = $this->get_formated_API_data($limit, $before);
		
		$javascript_table = "[";
		
		foreach($formated_data as $value){
			$javascript_table .= "[new Date(".$value->Time->format("Y,m-1,d,H,i,s")."),".$value->Value."],";
		}
		
		$javascript_table .= "]";
		
		return $javascript_table;
	}}

?>