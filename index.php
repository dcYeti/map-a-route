<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>ET Highway</title>
    <meta name="description" content="${2}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.7.0.slim.js"></script>
    <script>
    // Put in your constants and JSON here
    const queryParams = new URLSearchParams(window.location.search)
    const url = new URL(window.location.href)
    var preSelectedStyle = queryParams.get('mapStyle')

    var selectedStyle = preSelectedStyle === null ? 'muted_blue' : preSelectedStyle

    //Google Map Settings    
    
    var global_waypoints = [] //This gets accessed 

    //For Las Vegas Zoom
    var center = {lat: 36.11912002470413, lng:  -115.13057673396307}
    var init_zoom = 10   //Las Vegas Zoom
    

    var route_colors = [
          '#FF5733', '#FFC300', '#C70039',
          '#581845',  '#FF5733', '#FFD700',
          '#003f5c',  '#a05195', '#ff7c43', '#ffa600', '#ff6300', '#E63946','#900C3F',
           '#f95d6a', '#A8D5BA', '#457B9D', '#1D3557', '#d45087','#17202A','#F4A261','#374c80', '#7a506f',
          '#2A9D8F', '#F4A261', '#FF7A5A', '#8D99AE', '#A8D5BA', '#457B9D', '#1D3557', '#d45087'
        ];
    var map_icons = {
        'start'     : 'icons/cannonball/start.png',
        'finish'    : 'icons/cannonball/finish.png',
        'charger'   : 'icons/cannonball/charger.png',
        'charged'   : 'icons/cannonball/charged.png',
        'driver'    : 'icons/cannonball/Racer.png',
        'hiker'     : 'icons/cannonball/hiker.png',
        'hotel'     : 'icons/cannonball/hotel.png',
        'camera'    : 'icons/cannonball/camera.png',
        'dune'      : 'icons/cannonball/dune.png',
        'sign'      : 'icons/cannonball/route_sign.png',
        'mountain'  : 'icons/cannonball/mountain.png',
        'canyon'    : 'icons/cannonball/canyon.png',
        'arch'      : 'icons/cannonball/arch.png',
        'whale'     : 'icons/cannonball/whale.png',
        'car'       : 'icons/cannonball/car.png',
        'hole'      : 'icons/cannonball/hole.png',
        'airport'   : 'icons/cannonball/airport.png',
    }

   //Format = [Destination, Icon, Waypoints]
    var ETHighway = [
        ['Tikaboo Peak Trailhead, Nevada','charger',[]],
        ['ParkMGM Las Vegas, Ohio Tpke, Genoa, OH 43430','charger',[]],
        ['Tesla Supercharger, Towpath Service Plaza, 10037 Broadview Rd, Broadview Heights, OH','charger',[]],
        ['Tesla Supercharger, Rte 19 #20111, Cranberry Twp, PA','charger',[]],
        ['Tesla Supercharger, Lincoln Hwy, Breezewood, PA','charger',[]],
        ['Tesla Supercharger, Watkins Mill Rd #690, Gaithersburg, MD','charger',[]],
    ];




    //Put in special conditions by using a string search.  Object should be {search: <string>, disp: <message> }
    var special_stops = [
        
    ]


    var min_distance = 70;  //Minimum distance car needs to go
    var max_distance = 150; //Longest leg for driving

    var initialized = false

var style_blue_orange = [
    {
        "featureType": "administrative",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#6195a0"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [
            {
                "color": "#f2f2f2"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#e6f3d6"
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "all",
        "stylers": [
            {
                "saturation": -100
            },
            {
                "lightness": 45
            },
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#f4d2c5"
            },
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "labels.text",
        "stylers": [
            {
                "color": "#4e4e4e"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [
            {
                "hue": "#bdc5b6"
            },
            {
                "saturation": -89
            },
            {
                "lightness": -3
            },
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {
                "color": "#eaf6f8"
            },
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#eaf6f8"
            }
        ]
    },
    {
        "featureType": "administrative.neighborhood",
        "elementType": "all",
        "stylers": [
            {
                "hue": "#ffffff"
            },
            {
                "saturation": 0
            },
            {
                "lightness": 100
            },
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "administrative.locality",
        "elementType": "labels",
        "stylers": [
            {
                "hue": "#ffffff"
            },
            {
                "saturation": 0
            },
            {
                "lightness": 100
            },
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "administrative.land_parcel",
        "elementType": "all",
        "stylers": [
            {
                "hue": "#ffffff"
            },
            {
                "saturation": 0
            },
            {
                "lightness": 100
            },
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "all",
        "stylers": [
            {
                "hue": "#3a3935"
            },
            {
                "saturation": 5
            },
            {
                "lightness": -57
            },
            {
                "visibility": "off"
            }
        ]
    }
]
var dawn_style = [
    {
        "featureType": "administrative",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "lightness": 33
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [
            {
                "color": "#f2e5d4"
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#c5dac6"
            }
        ]
    },
    {
        "featureType": "poi.park",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "all",
        "stylers": [
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#c5c6c6"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#e4d7c6"
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#fbfaf7"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#acbcc9"
            }
        ]
    }
]
var dark_style = [
{
    "featureType": "all",
    "elementType": "all",
    "stylers": [
        {
            "invert_lightness": true
        },
        {
            "saturation": 10
        },
        {
            "lightness": 30
        },
        {
            "gamma": 0.5
        },
        {
            "hue": "#435158"
        },
    ]
},
{
    featureType: "administrative.locality",
    elementType: "geometry",
    stylers: [
        { visibility: "off" } // Turn off smaller city boundaries
    ]
},
{
    featureType: "poi",
    elementType: "labels",
    stylers: [
        { visibility: "off" } // Hide points of interest (optional)
    ]
},
{
    "featureType": "road",
    "elementType": "all",
    "stylers": [
        {
            "visibility": "simplified"
        }
    ]
},
{
    featureType: "administrative.locality",
    elementType: "labels",
    stylers: [
        { visibility: "simplified" } // Simplifies city labels
    ]
},
]
var muted_blue = [
    {
        "featureType": "all",
        "stylers": [
            {
                "saturation": 0
            },
            {
                "hue": "#e7ecf0"
            }
        ]
    },
    {
        "featureType": "road",
        "stylers": [
            {
                "saturation": -70
            }
        ]
    },
    {
        "featureType": "transit",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "poi",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "water",
        "stylers": [
            {
                "visibility": "simplified"
            },
            {
                "saturation": -60
            }
        ]
    }
]
var deep_blue = [
    {
        "featureType": "administrative",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "labels",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "on"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#12608d"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    }
]
var rainy_style = [ { "featureType": "administrative", "elementType": "labels.text.fill", "stylers": [ { "color": "#444444" } ] }, { "featureType": "administrative.land_parcel", "elementType": "all", "stylers": [ { "visibility": "off" } ] }, { "featureType": "landscape", "elementType": "all", "stylers": [ { "color": "#f2f2f2" } ] }, { "featureType": "landscape.natural", "elementType": "all", "stylers": [ { "visibility": "off" } ] }, { "featureType": "poi", "elementType": "all", "stylers": [ { "visibility": "on" }, { "color": "#052366" }, { "saturation": "-70" }, { "lightness": "85" } ] }, { "featureType": "poi", "elementType": "labels", "stylers": [ { "visibility": "simplified" }, { "lightness": "-53" }, { "weight": "1.00" }, { "gamma": "0.98" } ] }, { "featureType": "poi", "elementType": "labels.icon", "stylers": [ { "visibility": "simplified" } ] }, { "featureType": "road", "elementType": "all", "stylers": [ { "saturation": -100 }, { "lightness": 45 }, { "visibility": "on" } ] }, { "featureType": "road", "elementType": "geometry", "stylers": [ { "saturation": "-18" } ] }, { "featureType": "road", "elementType": "labels", "stylers": [ { "visibility": "off" } ] }, { "featureType": "road.highway", "elementType": "all", "stylers": [ { "visibility": "on" } ] }, { "featureType": "road.arterial", "elementType": "all", "stylers": [ { "visibility": "on" } ] }, { "featureType": "road.arterial", "elementType": "labels.icon", "stylers": [ { "visibility": "off" } ] }, { "featureType": "road.local", "elementType": "all", "stylers": [ { "visibility": "on" } ] }, { "featureType": "transit", "elementType": "all", "stylers": [ { "visibility": "off" } ] }, { "featureType": "water", "elementType": "all", "stylers": [ { "color": "#57677a" }, { "visibility": "on" } ] } ]


var availableStyles = {
    'muted_blue' : muted_blue,
    'dawn_style' : dawn_style,
    'dark_style' : dark_style,
    'style_blue_orange' : style_blue_orange,
    'deep_blue' : deep_blue,
    'rainy_style' : rainy_style
}

var desertMapSettings = {
        center: center,
        zoom: init_zoom,
        styles: availableStyles[selectedStyle]
        //styles: dawn_style
        //styles: dark_style
        //styles: retro_style
    };
    </script>
</head> 
<?php
define('GMAPS_KEY', '');
?>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-10 col-md-11">
                <div id="map" style="width:100%;height:1210px;"></div>  
                <br/><br/>
                <div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-5">
                                <select id = "which-list">
                                    <option value = "et_highway">ET Highway</option>
                                </select>
                                <button id="route-init" class="map-do-stuff">Initialize</button>
                                <button id="route-calc" class="map-do-stuff">Calculate Distances</button>
                                <button onclick="window.location.reload()">RESET</button>
                            </div>
                            <div class="col-2"></div>
                            <div class="col-5">
                            </div>
                        </div>
                    </div>
                </div>             
            </div>
            <div class="col-12 col-sm-2 col-md-1">
                <div style="max-height: 900px; overflow: auto;">   
                    <button id="las-vegas-start-pan" class="vid-do-stuff">Las Vegas to Tikaboo</button>
                    <hr/>
                    <div id="legs"></div>
                </div>
                <div>
                    <br/><label for="draw-speed">Draw Speed (lower is faster)</label>
                    <input id="draw-speed" type="number" step="1" min="1" value="15" />
                </div>
                <table id="station-list" class="table">
                    <tr>
                        <th>Leg</th>
                        <th>Start</th>
                        <th>Charger</th>
                        <th>Note</th>
                        <th>Distance</th>
                        <th>Adds to Route</th>
                        <th>Cumulative</th>
                    </tr>
                </table>
                <select id="styleSelector">
                </select>          
            </div>
        </div>
    </div>
    <!-- Create a container for the map -->
    

    <div>
   <script>
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
   <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo GMAPS_KEY; ?>&callback=initMap"></script>
    
</div>
  </body>
</html>