jQuery(document).ready(function($) {

  $.datepicker.setDefaults( $.datepicker.regional[ "it" ] );

  $("#event_date").datepicker({
    dateFormat: 'dd/mm/yy'
  });
});
