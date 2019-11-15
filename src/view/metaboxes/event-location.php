<div class="input-group">
    <label>Name</label>
    <br />
    <input type="text" placeholder="Enter location name (e.g. Sofia)" class="form-control" name="sel_location" value="<?php echo ! empty( $location_value['sel_location'] ) ? $location_value['sel_location'] : '' ?>" />
    <br />
    <label>Longitude</label>
    <br />
    <input type="text" placeholder="Enter location longitude" class="form-control" name="sel_longitude" value="<?php echo ! empty( $location_value['sel_longitude'] ) ? $location_value['sel_longitude'] : '' ?>" />
    <br />
    <label>Latitude</label>
    <br />
    <input type="text" placeholder="Enter location latitude" class="form-control" name="sel_latitude" value="<?php echo ! empty( $location_value['sel_latitude'] ) ? $location_value['sel_latitude'] : '' ?>" />
</div>
