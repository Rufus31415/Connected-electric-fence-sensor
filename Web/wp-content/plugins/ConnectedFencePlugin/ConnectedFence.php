<?php

// La clôture connecté est un appareil connecté
class ConnectedFence extends ConnectedDevice {
	public function extractData($RowHexaData){
		$left = substr($RowHexaData,0,2);
		$right = substr($RowHexaData, 2, 2);
		// Conversion Endianess
		return hexdec($right) * 256 + hexdec($left);
	}
}
?>