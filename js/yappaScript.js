jQuery(function($) {
  var trueSrc, $widgetInstance;
  var eventMethod = window.addEventListener ? 'addEventListener' : 'attachEvent';

  var eventer = window[eventMethod];

  var messageEvent = eventMethod === 'attachEvent' ? 'onmessage' : 'message';

  eventer(messageEvent, function(e) {
    if (e.data === 'DOMAIN_NOT_REGISTERED' || e.message === 'DOMAIN_NOT_REGISTERED') {
      $('#form-iframe').fadeIn();
      $('.title-register').fadeIn();
      $('.spinner-container').hide();
      $widgetInstance = $('.yappa-comments-iframe-instance').first();
      trueSrc = $('.yappa-comments-iframe-instance')
        .first()
        .attr('src');
      $widgetInstance.attr('src', '');
    }

    if (e.data === 'DOMAIN_REGISTERED' || e.message === 'DOMAIN_REGISTERED') {
      $('.yappa-comments-iframe-instance')
        .first()
        .attr('src', trueSrc);
      $('#checkboxes-container').fadeIn();
      $('#put-yappa-comments-here').fadeIn();
      $('.spinner-container').hide();
      $('#form-iframe').hide();
    }
  });
});
