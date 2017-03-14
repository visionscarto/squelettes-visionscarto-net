$(function() {

  $(window).on('scroll resize',
    function() { $('#footer').css({height: $(window).height()+'px'}); }
  );
  

  $('.content a').filter(function() { return $('img', this).size(); }).addClass('aimg');

  // resize embedded videos
  $('article').fitVids();

});