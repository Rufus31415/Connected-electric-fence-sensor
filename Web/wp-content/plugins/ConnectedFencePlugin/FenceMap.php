<?phpinclude_once plugin_dir_path( __FILE__ )."/see_device_form.php";// Affiche dans une carte Google Maps une liste d'appareil
function show_FenceMap_form($locations){

$json = json_encode($locations );
?>    <div id="map" style="height:70vh"></div>
    <script>
      var map;
      function initMap() {
		 var infos =  <?php echo json_encode($locations ) ?>;
		  
        map = new google.maps.Map(document.getElementById('map'), {
			mapTypeId: 'hybrid',
			zoom: 4,
			center: {lat: -25, lng: 130}
        });
		
  
		var markers = [];
		
		for(var i=0; i<infos.length;i++){
			let infowindow = new google.maps.InfoWindow({
				content: "<b><u>" + infos[i].Device.name + "</u></b><br><b>" + infos[i].LastValue.Value + "</b>V le " +  infos[i].LastValue.Time.date.replace(" ", " à ").replace(".000000", "") + "<br><a href='#title_" + infos[i].Device.id + "'>Historique</a>"
			});
	  
			let marker = new google.maps.Marker({
				position: {lat: parseFloat(infos[i].Lat), lng: parseFloat(infos[i].Long)},
				map: map,
				title: infos[i].Device.name
			});
			
			marker.addListener('click', function() {
				infowindow.open(map, marker);
			});
			
			markers.push(marker);

			infowindow.open(map, marker);
		}
		
		
		// centrer sur les markers
		var bounds = new google.maps.LatLngBounds();
		for (var i = 0; i < markers.length; i++) {
		 bounds.extend(markers[i].getPosition());
		}

		map.fitBounds(bounds);
		
	}
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfXHtG7ylyNenz8ncsqAuS4njElL2dm68&callback=initMap"
    async defer></script>
	
	
<?php

	foreach($locations as $loc){
		echo "<br/><br/>";
		show_see_device_form($loc->Device->id);
	}

 }
 ?>