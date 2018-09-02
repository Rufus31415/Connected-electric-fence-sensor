<?php
// Affiche les mesures du capteur $deviceID dans un graf Google Chart
function show_see_device_form($deviceID){
try{
	$device = new ConnectedFence($deviceID);
	
?>
    <script type="text/javascript">
    google.charts.load('current', {'packages':['annotatedtimeline']});
    google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = new google.visualization.DataTable();
        data.addColumn('date', 'Temps');
        data.addColumn('number', 'Voltage (Volt)');

        data.addRows(<?php echo $device->get_JS_API_data(); ?>);
		
		var StartT = new Date(); // today!
		StartT.setDate(StartT.getDate() - 2);// 2 jours avant

        var options = {
          title: '<?php echo $device->name; ?>',
          hAxis: {
            format: 'M/d/yy hh:mm'
          },
          vAxis: {
            gridlines: {color: 'none'},
            minValue: 0
          },
		  explorer: {
			maxZoomOut:1,
			maxZoomIn:100,
			keepInBounds: true,
			axis: 'horizontal'
		  },
		  max:10000,
		  min:0,
		  zoomEndTime: new Date(),
		  zoomStartTime : StartT
        };

        var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('curve_<?php echo $device->id; ?>'));

        chart.draw(data, options);
      }
    </script>
	<h3 id="title_<?php echo $device->id; ?>"><?php echo $device->name; ?></h3>
    <div id="curve_<?php echo $device->id; ?>" style="width: 100%; height: 500px"></div>

<?php	
} catch (Exception $ex) {
echo "<pre>".var_dump($ex)."</pre>";
}
}



?>