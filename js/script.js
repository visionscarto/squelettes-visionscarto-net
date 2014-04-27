$(function() {

  $(window).on('scroll resize',
    function() { $('#footer').css({height: $(window).height()+'px'}); }
  );
  
  $('.ui.accordion').accordion();

  $('.content a').filter(function() { return $('img', this).size(); }).addClass('aimg');
});