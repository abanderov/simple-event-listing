<?php
/**
 * Template part for displaying the location, date, and time for an event
 *
 */
	$location_value = get_post_meta( get_the_ID(), '_sel_location', true );
	$date_value = get_post_meta( get_the_ID(), '_sel_date', true );
	$url_value = get_post_meta( get_the_ID(), '_sel_ext_url', true );
	$start_time = ! empty ( $date_value['sel_start_time'] ) ? $date_value['sel_start_time'] : '' ;
    $end_time = ! empty ( $date_value['sel_end_time'] ) ? $date_value['sel_end_time'] : '' ;

	if ( empty ( $date_value['sel_to'] ) ) {
	  $start_date_to_time = strtotime($date_value['sel_from'] . $start_time );
	  $event_start = date('Ymd\THis\Z', $start_date_to_time);
	} else {
	  $start_date_to_time = strtotime($date_value['sel_from'] . $start_time );
	  $end_date_to_time = strtotime($date_value['sel_to'] . $end_time );
	  $event_start = date('Ymd\THis\Z', $start_date_to_time);
	  $event_end = date('Ymd\THis\Z', $end_date_to_time);
	}
?>
 <?php if (  empty ( $date_value['sel_to'] ) ) { ?>
    Date:  <?php echo $date_value['sel_from']; ?> <b>from</b> <?php echo $start_time; ?> <b>to</b> <?php echo $end_time; ?>
    <br />
  <?php } else { ?>
    <b>From:</b> <?php echo $date_value['sel_from']; ?> <?php echo $start_time; ?> <b>To:</b> <?php echo $end_time; ?> <?php echo $date_value['sel_to']; ?>
<?php  } ?>
<?php if ( ! empty( $location_value['sel_location'] ) ){ ?>
    <br />
    <b>Location:</b> <?php echo $location_value['sel_location']; ?>
<?php } ?>
<?php if ( ! empty( $url_value ) ){ ?>
    <br />
    <b>URL:</b> <a href="<?php echo $url_value; ?>" target="_blank"><?php echo $url_value; ?></a>
<?php } ?>
