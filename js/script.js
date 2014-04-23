$(function() {

  $(window).on('scroll resize',
    function() { $('#footer').css({height: $(window).height()+'px'}); }
  );
  
});