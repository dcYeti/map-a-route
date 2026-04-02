<div>
   <script src="/js/scripts.js"></script>
   <script>
   
   
   //Handling the button clicks
   const map_buttons = document.querySelectorAll('.map-do-stuff');

   const vid_do_buttons = document.querySelectorAll('.vid-do-stuff')  

   function markChargers(data_array){
        let itr = 0
        let itr_limit = data_array.length
        
        const plot_interval = setInterval(function(){

            geocoder.geocode( {address: data_array[itr]}, function(res, status){
                
                // console.log(`${itr} run is `)
                // console.dir(res)
                let coords = res[0].geometry.location
                plantMarker(coords,'charger',45, 30,22)

            })
            if(itr >= itr_limit){
                clearInterval(plot_interval)
            }
            itr++
        },100)
        

   }


   // Event handler function
   function handleClick(event) {
        const drop_selection = $('#which-list').val()
        const id = event.target.id;
        let data_array, origin, destination
        let total_waypoints = []

        switch(drop_selection){
            case 'ccs_north' :
                data_array = ccsListPart1
                origin = 'Red Ball Garage, New York, NY'
                destination = 'Electrify America Charging Station, 25 East Main Street, Green River, UT'
                total_waypoints = ['Omaha, NE']
                break
            case 'ccs_north_2':
                data_array = ccsListPart2
                origin = 'Electrify America Charging Station, 25 East Main Street, Green River, UT'
                destination = 'The Portofino Hotel and Marina, Redondo Beach, CA'
                total_waypoints = ['Omaha, NE']
                break
            case 'ccs_optimal':
                data_array = ccsListOptimal
                origin = 'Red Ball Garage, New York, NY'
                destination = 'The Portofino Hotel and Marina, Redondo Beach, CA'
                total_waypoints = ['Omaha, NE']
                break
            case 'tesla_part_1':
                data_array = TeslaListPart1
                origin = 'Red Ball Garage, New York, NY'
                destination = '14144 Lexington Ave, Parker, CO'
                break
            case 'tesla_part_2':
                data_array = TeslaListPart2
                origin = '14144 Lexington Ave, Parker, CO'
                destination = 'The Portofino Hotel and Marina, Redondo Beach, CA'
                break
            case 'tesla_optimized':
                data_array = TeslaListOptimized
                origin = 'Red Ball Garage, New York, NY'
                destination = 'The Portofino Hotel and Marina, Redondo Beach, CA'
                //total_waypoints = ['Omaha, NE']
                break
            case 'tesla_south':
                data_array = TeslaListSouth1
                origin = 'Red Ball Garage, New York, NY'
                destination = 'The Portofino Hotel and Marina, Redondo Beach, CA'
                total_waypoints = ['Tesla Supercharger, 1720 Harrisburg Pike, Carlisle, PA','Tesla Supercharger, 7624 W Reno Ave #380, Oklahoma City, OK 73127']
                break
            case 'tesla_andrew':
                data_array = TeslaListAndrew
                origin = 'Red Ball Garage, New York, NY'
                destination = 'The Portofino Hotel and Marina, Redondo Beach, CA'
                //total_waypoints = ['Omaha, NE']
                break
            case 'tesla_record':
                data_array = TeslaRecordRoute
                origin = 'Red Ball Garage, New York, NY'
                destination = 'The Portofino Hotel and Marina, Redondo Beach, CA'
                total_waypoints = ['Amarillo,TX']
                break
            case 'route_66':
                data_array = TeslaRoute66
                origin = '1550 Appian Way, Santa Monica, CA 90401'
                destination = 'Historic Route 66 Begin Sign, East Adams Street, Chicago, IL'
                total_waypoints = ['Amarillo, TX']
                break
            default:
                alert("data list doesn't exist")
                break
        }

        switch(id){
            case 'route-init':
                initCalc(data_array,origin,destination,total_waypoints)
                break;
            case 'route-calc':
                if(initialized === false){
                    alert('Please initialize the list first')
                } else {
                    analyzeDiffs(data_array,origin,destination) 
                }                 
                break;
            default:
                alert('button not mapped to anything')
                break;
        }
    
   }

   function handleVidClick(button) {
        let button_id = button.currentTarget.id
        // List the ids here
        // las-vegas-start-pan
        switch(button_id){
            case 'las-vegas-start-pan':
                map.setZoom(8)
                setTimeout(function(){
                    map.panTo({lat: 37.15037009721542, lng:  -115.40923014317703})
                }, 500)
                setTimeout(function(){
                    map.setZoom(9)
                }, 1200)
            break; 
            case 'overview':
                setTimeout(function(){
                    map.panTo({lat: 40.3457695883,lng: -96.10285979})
                    makePath('Red Ball Garage, New York, NY', 'Portofino Hotel, Redondo Beach, CA',36, '#FFA500', TeslaListOptimized)
                },500)                
                map.setZoom(5)

            break; 
            case 'route-66':
                setTimeout(function(){
                    map.setZoom(6)
                    makePath('Route 66 Sign, Santa Monica, CA', 'Route 66 Sign, Chicago, IL',36, '#8B3A2F', TeslaRoute66 )
                },500)    
                map.panTo({lat: 39.384478349238606, lng: -99.09114104})            
                

            break; 
            case 'zoomed-cannonball':
                setTimeout(function(){
                    map.panTo({lat: 41.759188871203385, lng: -84.44135497686402})
                    makePath('Red Ball Garage, New York, NY', 'Portofino Hotel, Redondo Beach, CA',140, '#DC143C', TeslaListOptimized,0.6)
                },500)                
                map.setZoom(7)

            break;
            case 'zoomed-overnight':
                setTimeout(function(){
                    map.setZoom(8)                    
                    makePath('Tesla Supercharger, Evanston, Illinois', 'Ronald Reagan Airport, Washington, DC',115, '#7D00FF',DCOvernightForButton ,0.6)
                },500)                
                map.panTo({lat: 41.528751834097214, lng: -83.30899284731055})

            break;  
            case 'record':
                setTimeout(function(){
                    map.panTo({lat: 40.3457695883,lng: -96.10285979})
                    makePath('Red Ball Garage, New York, NY', 'Portofino Hotel, Redondo Beach, CA',24, '#98FB98', TeslaRecordRoute)
                },500)                
                map.setZoom(5)

            break; 
            case 'indy-hot':
                setTimeout(function(){
                    map.panTo({lat: 40.29507077375357, lng: -85.67402337524449})
                    plantCircle({lat: 38.049023956600784, lng: -87.54290670483287}) //Evansville
                    
                    setTimeout(function(){
                        plantCircle({lat: 38.21159864126, lng: -85.71970987341278}) // Louisville
                        map.panTo({lat: 38.58128013008066, lng: -85.57514642211949})

                        setTimeout(function(){
                            map.panTo({lat: 42.186647956529605, lng:  -86.10249017211949})
                            plantCircle({lat: 41.577768833422866, lng: -87.27381420250863})


                        }, 3000)


                    },2500)
                },500)                
                map.setZoom(7)

            break; 
            case 'nashville-hot':
                setTimeout(function(){
                    map.panTo({lat: 35.046522479318895,lng: -85.32481791915309})
                    plantCircle({lat: 36.2249705529363,lng:  -86.78106733032229}) //Evansville
                    
                    // setTimeout(function(){
                    //     plantCircle({lat: 38.21159864126, lng: -85.71970987341278}) // Louisville
                    //     map.panTo({lat: 38.58128013008066, lng: -85.57514642211949})

                    //     setTimeout(function(){
                    //         map.panTo({lat: 42.186647956529605, lng:  -86.10249017211949})
                    //         plantCircle({lat: 41.577768833422866, lng: -87.27381420250863})


                    // }, 3000)

                },500)                
                map.setZoom(6)

                break; 
            case 'madison-hot':
                map.panTo({lat: 44.8045679181219,lng: -88.82108026003502})
                setTimeout(function(){
                    plantCircle({lat: 43.08650819974159, lng:-89.40438139831647})
                    map.setZoom(7)
                },300)
                break;
            case 'plot-chargers':
                markChargers(TeslaListOptimized)      

            break; 
            case 'colorado':
                plantMarker({lat: 39.97217430250442, lng:-104.74861327323549},'driver',60)      

            break;
        }
   }

   function plantCircle(coords = { lat: 38.02069120629188, lng:-87.63073295914795 }, fillColor = "#FF0000", fillOpacity = 0.3, baseRadius = 50000, maxRadius = 60000, pulseSpeed = 30){

        const circle = new google.maps.Circle({
            strokeColor: fillColor,
            strokeOpacity: 0.6,
            strokeWeight: 2,
            fillColor: fillColor,
            fillOpacity: fillOpacity,
            map,
            center: coords,
            radius: baseRadius,
        });

        let growing = true;
        function pulse() {
            if (growing) {
                circle.setRadius(circle.getRadius() + 600);
                if (circle.getRadius() >= maxRadius) growing = false;
            } else {
                circle.setRadius(circle.getRadius() - 600);
                if (circle.getRadius() <= baseRadius) growing = true;
            }
            setTimeout(pulse, pulseSpeed);
        }

        pulse(); // Start the animation
    }


   function handleMapLeg(button) {
     let route_color = route_colors[parseInt(button.currentTarget.dataset.leg)]
     let draw_speed = document.getElementById('draw-speed').value
     let local_waypoints = global_waypoints[parseInt(button.currentTarget.dataset.leg)]
     let map_icon = button.currentTarget.dataset.icon
     let begin_route_change = false

    //set it to just one color
     route_color = route_colors[0]
     let route_color_local = route_colors[1]

     let steps_w_charge = ['0','1','2','3','4','5','6','7','11','13','14','16','17','19','20','21','23','24','26','28','29']
     if(steps_w_charge.includes(button.currentTarget.dataset.leg)){
        begin_route_change = true
     }

    makePathVid(button.currentTarget.dataset.origin, button.currentTarget.dataset.dest, draw_speed, route_color, local_waypoints, map_icon,begin_route_change)

   }

   // Attach event listener to each button
   map_buttons.forEach(button => {
       button.addEventListener('click', handleClick);
   })

   vid_do_buttons.forEach(button => {
       button.addEventListener('click', handleVidClick);
   })

   function initCalc(places_list, origin, destination, total_way = []){
        $('#station-list .station-tr').remove()
        let draw_speed = document.getElementById('draw-speed').value
        makePath(origin,destination,draw_speed,'#00EEFF',places_list)
        setTimeout(function(){
            makePath(origin,destination,draw_speed,'#EEEE00',total_way)
        },200)        
        initialized = true
   }

   //makeLegButtons(TeslaRoute66)
   //makeLegButtons(DCOvernight)
   makeLegButtons(ETHighway)

   const map_leg_buttons = document.querySelectorAll('.leg-do-stuff')

   map_leg_buttons.forEach(button => {
       button.addEventListener('click', handleMapLeg);
   })


   //build style selector
   availableStylesDropdown = Object.keys(availableStyles)

   var style_options = ''

   availableStylesDropdown.forEach((style)=>{
        var selected_attr = style == selectedStyle ? 'selected' : ''
        style_options += `<option value = "${style}" ${selected_attr}>${style.replace(/_/g, " ")}</option>`
   })

   $("#styleSelector").append(style_options)

   $('#styleSelector').on('change', () => {
        selectedStyle = $('#styleSelector').val()
        url.searchParams.set('mapStyle', selectedStyle); // Add or update the query parameter
        window.location.href = url.toString(); // Reload with the new URL
   })

    // Uncomment if youw ant it to reset upon reload
    // if (window.location.search) {
    //     window.history.replaceState({}, document.title, window.location.pathname);
    // }
               
    </script>

   <!-- Call the initMap() function once the Google Maps API is loaded -->
   <?php global $g_maps_key; ?>
   <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $g_maps_key; ?>&callback=initMap"></script>
    
</div>
  </body>
</html>