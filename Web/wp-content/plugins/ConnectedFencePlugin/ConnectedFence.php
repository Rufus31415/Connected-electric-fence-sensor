<?php


class ConnectedFence extends ConnectedDevice {
	public function extractData($RowHexaData){
		$left = substr($RowHexaData,0,2);
		$right = substr($RowHexaData, 2, 2);

		return hexdec($right) * 256 + hexdec($left);
	}
}
