<?php if ( ! empty ( $location_value['sel_latitude'] ) && ! empty ( $location_value['sel_longitude'] ) ) { ?>
  <iframe width="300" height="225" frameborder="0" style="border:0"
  src="https://www.google.com/maps/embed/v1/view?key=AIzaSyAh3iLeZni-Sjf2rr3YsI9hboeLgl_NCRg&center=<?php echo $location_value['sel_latitude'].", ". $location_value['sel_longitude']; ?>&zoom=8"></iframe>
<?php } else { ?>
  <p> No map available </p>
<?php } ?>
