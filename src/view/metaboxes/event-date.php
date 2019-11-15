<label>From</label>
<br/>
<input type="text" id="from" name="sel_from" value="<?php echo !empty($date_value['sel_from']) ? $date_value['sel_from'] : '' ?>">
<br />
<label>To</label>
<br />
<input type="text" id="to" name="sel_to" value="<?php echo !empty($date_value['sel_to']) ? $date_value['sel_to'] : '' ?>">
<br />
<label> Start Time</label>
<br />
<input type="text" id="start_time" name="sel_start_time" value="<?php echo !empty($date_value['sel_start_time']) ? $date_value['sel_start_time'] : '' ?>">
<br />
<label> End Time</label>
<br />
<input type="text" id="end_time" name="sel_end_time" value="<?php echo !empty($date_value['sel_end_time']) ? $date_value['sel_end_time'] : '' ?>">

<script type="text/javascript">
    jQuery(document).ready(function($) {
        var dateFormat = "dd-mm-yy";
        var from = $( "#from" ).datepicker({
                      dateFormat: dateFormat,
                      defaultDate: "+1w",
                      changeMonth: true,
                      numberOfMonths: 2
                    }).on( "change", function() {
                      to.datepicker( "option", "minDate", $( this ).val() );
                    });
        var to = $( "#to" ).datepicker({
                    dateFormat: dateFormat,
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 2
                }).on( "change", function() {
                    from.datepicker( "option", "maxDate", $( this ).val() );
                });
    });
</script>
