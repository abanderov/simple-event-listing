
  <?php if (  empty ( $date_value['sel_to'] ) ) { ?>
    Date:  <?php echo $date_value['sel_from']; ?>
    <br />
    Start Time: <?php echo $date_value['sel_time']; ?>
  <?php } else { ?>
    From: <?php echo $date_value['sel_from']; ?>
    <br />
    To: <?php echo $date_value['sel_to']; ?>
    <br />
    Start Time: <?php echo $date_value['sel_time']; ?>
  <?php }	?>
