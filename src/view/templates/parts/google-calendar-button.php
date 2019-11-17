<?php
/**
 * Template part for displaying Google Calendar for an event
 *
 */
$calendar_url =
    'https://www.google.com/calendar/render?action=TEMPLATE'.
    '&text=' . str_replace( ' ', '+', $post->post_title ) .
    '&dates=' . $event_start . '/' . $event_end .
    '&details=' . strip_tags ( html_entity_decode ( str_replace( ' ', '+',  $post->post_content ) ) ) .
    '&location=' .  str_replace( ' ', '+',  $location_value['sel_location'] ) .
    '&trp=false'
;
?>
<button id="gcal">
    <a href="<?php echo $calendar_url; ?>" target="_blank" rel="nofollow">Add to my Google Calendar</a>
</button>
