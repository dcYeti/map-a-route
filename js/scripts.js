var map, theWay, geocoder, northRoute, southRoute, myRoute, customIcon, circleCustomIcon
var polylines = []
var markers = []


function makeLegButtons(data_array){
   let origin = 'Tesla Supercharger, 1800 Maple Ave, Evanston, IL'
   let destination = 'Ronald Reagan Washington National Airport, washington, dc'
   let itr = 0
   let fin_itr = data_array.length - 1
   let html_out = ''

   data_array.forEach((legs)=>{
      let leg_origin, leg_dest, leg_icon
      global_waypoints.push(data_array[itr][2])
      if(itr == 0){
            leg_origin = origin
            leg_dest = data_array[0][0]
            leg_icon = data_array[0][1]

      } else {
            leg_origin = data_array[itr - 1][0]
            leg_dest = data_array[itr][0]
            leg_icon = data_array[itr][1]                  
      }
      html_out += `<button class="leg-do-stuff" data-origin = "${leg_origin}" data-dest="${leg_dest}" data-leg = "${itr}" data-icon="${leg_icon}">${itr + 1}:  ${leg_origin} to ${leg_dest}</button>`
      itr++
   })
   html_out += `<button class="leg-do-stuff" data-origin = "${data_array[fin_itr][0]}" data-dest="${destination}" data-icon="airport" data-leg = "${itr}">${fin_itr + 1}:  ${data_array[fin_itr][0]} to ${destination}</button>`


   document.getElementById('legs').innerHTML = html_out


}

function secondsToFormatted(seconds) {
   const hours = Math.floor(seconds / 3600);
   const minutes = Math.floor((seconds % 3600) / 60);

   const formattedHours = hours > 0 ? `${hours}h ` : '';
   const formattedMinutes = minutes > 0 ? `${minutes}m` : '';

   return formattedHours + formattedMinutes;
}

function plantMarker(pos,icon_handle,size=50,anchor_x = 32, anchor_y = 40){

   let icon_path = map_icons[icon_handle]
   
   let custom_icon = {
      url: icon_path,
      scaledSize: new google.maps.Size(size,size), // Adjust the size of the icon
      anchor: new google.maps.Point(anchor_x,anchor_y)
   }                            
   
   // Add a marker to the map
   let marker = new google.maps.Marker({
      position: pos,
      map: map,
      title: "Marker Title",
      icon: custom_icon,
      animation: google.maps.Animation.DROP,
   })

   markers.push(marker)
}

function resetMap() {
   // Clear markers
   markers.forEach(function(marker) {
      marker.setMap(null);
   });
   markers = [];

   // Clear polylines
   polylines.forEach(function(polyline) {
      polyline.setMap(null);
   });
   polylines = [];
}

function analyzeDiffs(stops_arr, origin, destination){
   let itr = 0
   let completed = 0
   let dist_results = []
   let time_results = []

   stops_arr.forEach((stop) => {
      let start_point = itr == 0 ? origin : stops_arr[itr - 1]
      let end_point   = itr == stops_arr.length - 1 ? destination : stops_arr[itr+1]
      let calc_waypoint = [{location: stop}]
      let stop_prom = new Promise((resolve,reject)=>{
            let itr_write = itr
            let request = {
               origin: start_point, 
               destination: end_point, 
               travelMode: google.maps.TravelMode.DRIVING,
               waypoints: calc_waypoint
            }

            theWay.route(request,  (result, status) => {
               if (status === google.maps.DirectionsStatus.OK) {
                  let route = result.routes[0];
                  let legs  = route['legs']                 
                  let sum = 0
                  let time_sum = 0
                  legs.forEach((leg)=>{
                        sum += parseInt(leg['distance'].value)
                        time_sum += parseInt(leg['duration'].value)
                  
                  })
                  resolve([itr_write,sum,time_sum])               
               }
            })
      })

      let direct_prom = new Promise((resolve,reject)=>{
            let itr_write = itr
            let request = {
               origin: start_point, 
               destination: end_point, 
               travelMode: google.maps.TravelMode.DRIVING,
            }

            theWay.route(request,  (result, status) => {
               if (status === google.maps.DirectionsStatus.OK) {
                  let route   = result.routes[0];
                  let legs    = route['legs']
                  let endy    = legs[0]['end_address'].replaceAll(', USA','').trim()
                  let sum = 0
                  let time_sum = 0
                  legs.forEach((leg)=>{
                        sum += parseInt(leg['distance'].value)
                        time_sum += parseInt(leg['duration'].value)
                  })
                  resolve([itr_write,sum,stop,time_sum])              
               }
            })

      })

      Promise.all([stop_prom,direct_prom]).then((values)=>{
            dist_results.push({
               'leg_num' : values[0][0],
               'stop': values[0][1],
               'direct' : values[1][1],
               'stop_name':values[1][2]
            })
            time_results.push({
               'leg_num' : values[0][0],
               'stop': values[0][2],
               'direct' : values[1][3],
               'stop_name':values[1][2]                    
            })
            completed++
            if(completed == stops_arr.length){                    
               dist_results.sort((a,b)=>{
                  return a['leg_num'] - b['leg_num']
               })
               time_results.sort((a,b)=>{
                  return a['leg_num'] - b['leg_num']
               })
               
               //Now, write the values
               dist_results.forEach((el)=>{
                  let search_id = '#stop-' + el['leg_num'].toString() + ' .dist-calc'
                  let dist_difference = el['stop'] - el['direct']
                  let dist_diff_string = (dist_difference * .00062137).toFixed(1).toString().concat(' mi')
                  $(search_id).text(dist_diff_string)
               })
               //Now, write the values
               time_results.forEach((el)=>{
                  let search_id = '#stop-' + el['leg_num'].toString() + ' .dist-calc'
                  let time_difference = el['stop'] - el['direct']
                  let time_diff_string = '<br/>(+' + secondsToFormatted(time_difference) + ')'
                  $(search_id).append(time_diff_string)
               })
            }
      })
      itr++

   })
}

function makePath(start, end, speed = 40, color = '#ff0000', waypoints_raw = [], opacity = 0.7){

   let waypoints_formatted = waypoints_raw.map((entry)=>{
      return {location: entry}
   })
   
   let request = {
      origin: start, 
      destination: end, 
      travelMode: google.maps.TravelMode.DRIVING,
      waypoints: waypoints_formatted
   }

   let total_distance = 0;
   let out = ''
   let top_itr = 0

   theWay.route(request,  (result, status) => {

      if (status === google.maps.DirectionsStatus.OK) {
            let route = result.routes[0];
            let legs = route['legs']

            let leg_itr = 0
            let total_time = 0

            legs.forEach((leg)=>{
               leg_itr++
               let start   = leg['start_address'].replaceAll(', USA','').trim()
               let end     = leg['end_address'].replaceAll(', USA','').trim()
               let note    = ''
               let end_gps = leg['end_location']
               let start_gps = leg['start_location']
               let time    = secondsToFormatted(leg['duration']['value'])
               total_time += leg['duration']['value']
               
               //check for special messages
               special_stops.forEach((special)=>{
                  if(end.includes(special['search'])){
                        note += special['disp']
                  }
               })
               console.log(end)
               
               if(end.toLowerCase().includes('reagan')){
                  plantMarker(end_gps, 'airport',55, 20, 45)
               } else {
               plantMarker(end_gps, 'charger',45, 20, 35)
               }

               

               
               // Create a new marker
               // const marker = new google.maps.Marker({
               //   position: end_gps,
               //   map: map,
               //   icon: circleCustomIcon,
               //   title: end // Optional: Add a title to the marker
               // });                   

               let distance= leg['distance'].text
               total_distance += parseInt(leg['distance'].value)
               let difference = 'n/a'
               out += `<tr id="stop-${top_itr}" class="station-tr"><td>${leg_itr}</td><td>${start}</td><td>${end}</td><td>${note}</td><td>${distance}</td><td class="dist-calc"></td><td>${(total_distance * .00062137).toFixed(0)} mi<br/>(${secondsToFormatted(total_time)})</td></tr>`
               top_itr++
            })

            total_distance_converted = total_distance * .00062137 // Converting meters to miles (gmaps returns in meters)
            total_distance_converted = total_distance_converted.toFixed(1).toString().concat(' mi')

            //Write the total line
            out += `<tr class="station-tr"><td></td><td><strong>Total</strong></td><td>${total_distance_converted}</td><td></td><td></td></tr>`

            let polyline = route.overview_polyline;
            let path = google.maps.geometry.encoding.decodePath(polyline);
            //console.log('path length is ' + path.length)
            // Use the polyline to draw the route on the map
            let line_size = polylines.length
            polylines[line_size] = new google.maps.Polyline({
               path: path.slice(0,1),
               geodesic: true,
               strokeColor: color,
               strokeOpacity: opacity,
               strokeWeight: 5,
            });
            
            polylines[line_size].setMap(map);
            let itr = 1
            let lineDrawer = setInterval(function(){
               if(itr >= path.length){
                  clearInterval(lineDrawer)
                  return line_size
               } else {
                  itr++
                  polylines[line_size].setPath(path.slice(0,itr))
               }
            },speed)
            $('#station-list').append(out)
      } else {
            return 0
            console.dir(result)
      }                

   })

}

function makePathVid(start, end, speed = 40, color = '#ff0000', waypoints_raw = [],icon='charger',change_begin_icon = false){

   let waypoints_formatted = waypoints_raw.map((entry)=>{
      return {location: entry}
   })
   
   let request = {
      origin: start, 
      destination: end, 
      travelMode: google.maps.TravelMode.DRIVING,
      waypoints: waypoints_formatted
   }

   let total_distance = 0;
   let out = ''
   let top_itr = 0
   let origin_gps

   theWay.route(request,  (result, status) => {

      if (status === google.maps.DirectionsStatus.OK) {
         let route = result.routes[0];
         let legs = route['legs']

         let leg_itr = 0
         let total_time = 0
         let end_gps

         legs.forEach((leg)=>{
               leg_itr++
               if(leg_itr == 1){
                  origin_gps = leg['start_location']
               }
               let start   = leg['start_address'].replaceAll(', USA','').trim()
               let end     = leg['end_address'].replaceAll(', USA','').trim()
               let note    = ''
               end_gps = leg['end_location']
               let start_gps = leg['start_location']
               let time    = secondsToFormatted(leg['duration']['value'])
               total_time += leg['duration']['value']
               
               //check for special messages
               special_stops.forEach((special)=>{
                  if(end.includes(special['search'])){
                     note += special['disp']
                  }
               })              

               
               // Create a new marker
            // const marker = new google.maps.Marker({
            //   position: end_gps,
            //   map: map,
            //   icon: circleCustomIcon,
            //   title: end // Optional: Add a title to the marker
            // });                   

               let distance= leg['distance'].text
               total_distance += parseInt(leg['distance'].value)
               let difference = 'n/a'
               out += `<tr id="stop-${top_itr}" class="station-tr"><td>${leg_itr}</td><td>${start}</td><td>${end}</td><td>${note}</td><td>${distance}</td><td class="dist-calc"></td><td>${(total_distance * .00062137).toFixed(0)} mi<br/>(${secondsToFormatted(total_time)})</td></tr>`
               top_itr++
         })
         
         let icon_size = 56
         if(icon == 'hotel'){
               icon_size = 42
         } 
         
         if(icon == 'arch'){
               plantMarker({lat:38.635124396520816, lng:-90.18569078955196}, 'arch',42)
         } else {
               plantMarker(end_gps, icon,icon_size)
         }
         if(change_begin_icon == true){
               if(start.toLowerCase().includes('evanston')){
                  //plantMarker(origin_gps,'', 70)
               } else {
                  //Size, for zoomed in, use 60, for zoomed out user 48
                  plantMarker(origin_gps,'charged', 48)
               }
         }

         total_distance_converted = total_distance * .00062137 // Converting meters to miles (gmaps returns in meters)
         total_distance_converted = total_distance_converted.toFixed(1).toString().concat(' mi')

         //Write the total line
         out += `<tr class="station-tr"><td></td><td><strong>Total</strong></td><td>${total_distance_converted}</td><td></td><td></td></tr>`

         let polyline = route.overview_polyline;
         let path = google.maps.geometry.encoding.decodePath(polyline);
         //console.log('path length is ' + path.length)
         // Use the polyline to draw the route on the map
         let line_size = polylines.length
         polylines[line_size] = new google.maps.Polyline({
               path: path.slice(0,1),
               geodesic: true,
               strokeColor: color,
               strokeOpacity: 0.7,
               strokeWeight: 5,
         });
         
         polylines[line_size].setMap(map);
         let itr = 1
         let lineDrawer = setInterval(function(){
               if(itr >= path.length){
                  clearInterval(lineDrawer)
                  return line_size
               } else {
                  itr++
                  polylines[line_size].setPath(path.slice(0,itr))
               }
         },speed)
         $('#station-list').append(out)
      } else {
         return 0
         console.dir(result)
      }                

   })

}

function initMap() {
   // Set the coordinates for the center of the map
   
   var icon = { lat: 37.7749, lng: -122.4194 };

   // Create a new map instance
   map = new google.maps.Map(document.getElementById("map"), desertMapSettings);

   // // Add event listener for zoom change
      google.maps.event.addListener(map, 'zoom_changed', function() {
      const zoomLevel = map.getZoom(); // Get the current zoom level
      console.log('Zoom Level:', zoomLevel); // Log the zoom level to the console
   })
   
   google.maps.event.addListener(map, 'center_changed', function() {
      const mapCenter = map.getCenter(); // Get the current map center
      const lat = mapCenter.lat();
      const lng = mapCenter.lng();
      console.log('New Center:', lat, lng); // Log the new center coordinates to the console
   })
   
   //Initiate directions service
   theWay = new google.maps.DirectionsService()

      // Create a custom marker icon
      circleCustomIcon = {
      path: google.maps.SymbolPath.CIRCLE,
      fillColor: "#0033AA",
      fillOpacity: 0.7,
      strokeColor: "#EEEEFF",
      strokeWeight: 1,
      scale: 6
      };

   geocoder = new google.maps.Geocoder();

}