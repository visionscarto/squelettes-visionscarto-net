/* piké ici https://github.com/jean-emmanuel/spip-fat/blob/master/js/scripts.js */
/*========== MEDIAS FRAMES ==========*/
$(function() {
$('.vimeo, .youtube, .dailymotion').each(function(){
	var container = $(this);
	var caption = container.find('.top-left');
	var frame = document.createElement('iframe');
	var id = $(this).attr('id');

	if ($(this).hasClass('vimeo')) {
	frame.src = '//player.vimeo.com/video/'+id+'?portrait=0&color=eeeeee&badge=0&byline=0&autoplay=1';	
	$.ajax({
		url: 'http://vimeo.com/api/v2/video/'+id+'.json?callback=?',
		dataType:'jsonp',
		success: function(data){
			caption.html(data[0].title);
			container.css({'background-image':'url('+data[0].thumbnail_large+')','background-size':'cover'});
		}
	});
	}

	if ($(this).hasClass('youtube')) {
	frame.src = 'http://www.youtube-nocookie.com/embed/'+id+'?rel=0&autoplay=1&theme=light&color=red';	
	$.ajax({

		url: 'http://gdata.youtube.com/feeds/api/videos/'+id+'?v=2&alt=json&field=title',
		dataType:'jsonp',
		success: function(data){
			caption.html(data.entry.title.$t);
			container.css({'background-image':'url(http://img.youtube.com/vi/'+id+'/hqdefault.jpg)','background-size':'cover'});
		}
	});
	}

	if ($(this).hasClass('dailymotion')) {
	frame.src = 'http://www.dailymotion.com/embed/video/'+id+'?related=0&autoplay=1';	
	$.ajax({

		url: 'https://api.dailymotion.com/video/'+id+'?fields=title,thumbnail_360_url',
		dataType:'jsonp',
		success: function(data){
			caption.html(data.title);
			container.css({'background-image':'url('+data.thumbnail_360_url+')','background-size':'cover'});
		}
	});
	}

	container.find('.play-button').click(function(e){
		e.preventDefault();
		$(this).find('i').removeClass('fa-play').addClass('fa-cog fa-spin');
		caption.hide();
		container[0].appendChild(frame);
		container.find('iframe').load(function(){container.find('.play-button').hide();});
	});
});
$('.bandcamp').each(function(){
	var bandcamp = document.createElement('iframe');
	bandcamp.src = 'http://bandcamp.com/EmbeddedPlayer/album='+$(this).attr('id')+'/size=medium/bgcol=ffffff/linkcol=333333/transparent=true/';
	$(this)[0].appendChild(bandcamp);
});
/*========== /MEDIAS FRAMES ==========*/
});