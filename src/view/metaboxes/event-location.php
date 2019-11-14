<div class="input-group">
    <input type="text" placeholder="Enter location name (e.g. Sofia)" class="form-control" name="sel_location" value="<?php echo !empty($location_value['sel_location']) ? $location_value['sel_location'] : '' ?>" />
    <input type="text" placeholder="Enter location longitude" class="form-control" name="sel_longitude" value="<?php echo !empty($location_value['sel_longitude']) ? $location_value['sel_longitude'] : '' ?>" />
    <input type="text" placeholder="Enter location latitude" class="form-control" name="sel_latitude" value="<?php echo !empty($location_value['sel_latitude']) ? $location_value['sel_latitude'] : '' ?>" />
</div>

<!-- <iframe width="600" height="450" frameborder="0" style="border:0"
src="https://www.google.com/maps/embed/v1/search?key=AIzaSyAh3iLeZni-Sjf2rr3YsI9hboeLgl_NCRg&q=record+stores+in+Seattle" allowfullscreen></iframe> -->
<!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAh3iLeZni-Sjf2rr3YsI9hboeLgl_NCRg&callback=initMap"
type="text/javascript"></script> -->
