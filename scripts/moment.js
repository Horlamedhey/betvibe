window.onload = function(e){
  var $clock = $('#time'),
      eventTime = moment('30-09-2018 00:00:00', 'DD-MM-YYYY HH:mm:ss').unix(),
      currentTime = moment().unix(),
      diffTime = eventTime - currentTime,
      duration = moment.duration(diffTime * 1000, 'milliseconds'),
      interval = 1000;

    // if time to countdown
    if(diffTime > 0) {
      setInterval(function(){
        duration = moment.duration(duration.asMilliseconds() - interval, 
                   'milliseconds');
        var d = moment.duration(duration).days(),
            h = moment.duration(duration).hours(),
            m = moment.duration(duration).minutes(),
            s = moment.duration(duration).seconds();

            d = $.trim(d).length === 1 ? '0' + d : d;
            h = $.trim(h).length === 1 ? '0' + h : h;
            m = $.trim(m).length === 1 ? '0' + m : m;
            s = $.trim(s).length === 1 ? '0' + s : s;

            // show how many hours, minutes and seconds are left
            $('#time').text(d + ':' + h + ':' + m + ':' + s);

      }, interval);
    }
};
