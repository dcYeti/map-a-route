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
'start'     : 'icons/start.png',
'finish'    : 'icons/finish.png',
'charger'   : 'icons/charger.png',
'charged'   : 'icons/charged.png',
'driver'    : 'icons/Racer.png',
'hiker'     : 'icons/hiker.png',
'hotel'     : 'icons/hotel.png',
'camera'    : 'icons/camera.png',
'dune'      : 'icons/dune.png',
'sign'      : 'icons/route_sign.png',
'mountain'  : 'icons/mountain.png',
'canyon'    : 'icons/canyon.png',
'arch'      : 'icons/arch.png',
'whale'     : 'icons/whale.png',
'car'       : 'icons/car.png',
'hole'      : 'icons/hole.png',
'airport'   : 'icons/airport.png',
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