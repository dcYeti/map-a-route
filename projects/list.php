<body>
<h1>Projects List</h1>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-10 col-md-11">
                <div id="projects-list"></div>  
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
    <div>
    <script>
    $(document).ready(function(){
        




    })    
    </script>
    </div>
    <!-- Footer.php will be mostly javascript and will close all the HTML-->