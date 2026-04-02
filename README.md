Very simply, this is a framework-free PHP/Javascript app where you can map a Google Maps Route with waypoints along with some routes customization

JS Libraries used: Bootstrap 5, JQuery 3.7, Font Awesome 6, Google Maps Javascript API

This is optimized for PHP 8.5 and newish Javascript

This uses SQLite3 as a database to keep things simple... make sure your web server owns it and the parent directory... 
DB Location is db/map_routes.db

You need to have your own Google Maps API key for this to work and it should be saved in a .env file (env.example provided)

You must get your own icons if you want to use them on your map... I have purchased mine but don't have a right to distribute

****** SETUP ********** 
Run in Terminal
"php setup.php" for normal setup (creating database tables)
"php setup.php reset" to drop and re-create tables from afresh