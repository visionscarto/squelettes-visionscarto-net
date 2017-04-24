$(function() {

/*
  $(window).on('scroll resize',
    function() { $('#footer').css({height: $(window).height()+'px'}); }
  );
  */
  
  
	if (typeof load_seenthis != 'undefined') $('#seenthis').load(load_seenthis);

  $('.content a').filter(function() { return $('img', this).size(); }).addClass('aimg');

  // resize embedded videos
  $('article').fitVids();

});