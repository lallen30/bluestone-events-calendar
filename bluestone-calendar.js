jQuery(document).ready(function($) {
  // Function to bind calendar navigation button click events
  function bindCalendarButtons() {
    $('.calendar-prev').off('click').on('click', function() {
      console.log("Prev button clicked");
      // Get the current displayed month and year
      let currentMonthYear = $('.calendar-month-year').text().split(' ');
      let month = getMonthNumber(currentMonthYear[0]);
      let year = parseInt(currentMonthYear[1]);

      // Decrement month and adjust year if necessary
      month -= 1;
      if (month < 1) {
        month = 12;
        year -= 1;
      }
      
      // AJAX call
      displayCalendar(month, year);
    });

    $('.calendar-next').off('click').on('click', function() {
      console.log("Next button clicked");
      // Get the current displayed month and year
      let currentMonthYear = $('.calendar-month-year').text().split(' ');
      let month = getMonthNumber(currentMonthYear[0]);
      let year = parseInt(currentMonthYear[1]);

      // Increment month and adjust year if necessary
      month += 1;
      if (month > 12) {
        month = 1;
        year += 1;
      }
      
      // AJAX call
      displayCalendar(month, year);
    });
  }

  // Function to return month number from month name
  function getMonthNumber(monthName) {
    let monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    return monthNames.indexOf(monthName) + 1;
  }

  // Function to send AJAX request to update the calendar
  // Function to send AJAX request to update the calendar
function displayCalendar(month, year) {
  $.ajax({
    url: bluestone_calendar_ajax.ajax_url,
    type: 'POST',
    data: { 
      action: 'change_month',
      month: month,
      year: year
    },    
    success: function(response) {
      console.log("AJAX Response: " + response);
      $("#bluestone_calendar").replaceWith(response);
      bindCalendarButtons();  // rebind the click events for the new elements
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
    }
  });
}


  // Initial bind of calendar navigation button click events
  bindCalendarButtons();
});
