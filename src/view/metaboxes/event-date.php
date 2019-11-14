<!-- <input type="text" id="datetimepicker1" class="form-control" name="sel_date" value="<?php echo !empty($date_value) ? $date_value : '' ?>"/> -->
<label for="from">From</label>
<input type="text" id="from" name="sel_from" value="<?php echo !empty($date_value['sel_from']) ? $date_value['sel_from'] : '' ?>">
<br />
<label for="to">To</label>
<input type="text" id="to" name="sel_to" value="<?php echo !empty($date_value['sel_to']) ? $date_value['sel_to'] : '' ?>">
<br />
<label for="to">Time</label>
<input type="text" id="time" name="sel_time" value="<?php echo !empty($date_value['sel_time']) ? $date_value['sel_time'] : '' ?>">>

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
