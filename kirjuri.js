$(function() {
  var keepalive = function() {
    $.get('index.php');
    setTimeout(keepalive, 180000);
  }
  setTimeout(keepalive, 180000);

  if ($('#minutes').length == 1) {
    var parts = $('#workstarted').text().split(' ');
    var time = parts[0].split(':');
    var pvm = parts[1].split('.');

    var month = parseInt(pvm[1])-1;

    var startTime = new Date(pvm[2], month, pvm[0], time[0], time[1]);

    var updateMinutes = function() {
      var diff = new Date().valueOf() - startTime.valueOf();
      diff = Math.round(diff/60000);
      $('#minutes').text(diff);
      $('#minutesfield').val(diff);
      setTimeout(updateMinutes, 30000);
    }
    
    setTimeout(updateMinutes, 30000);

  }

});
