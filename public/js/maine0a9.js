$(document).ready(function(){
	floatHeader();
	floatTopbar();
	backtotop();
	panelTool();
	changeFloatHeader();
	changeLayoutMode();
	changeHeaderStyle();
});
//Float Menu
function processFloatHeader(headerAdd, scroolAction){
	if(headerAdd){
		$("#header").addClass( "fixed" );
	    $("main").css( "padding-top", $("#header").height());
	    var hideheight =  $("#header").height()+120;
	    var pos = $(window).scrollTop();
	    if( scroolAction && pos >= hideheight ){
	        $("#topbar").addClass('hide-bar');
	        $(".hide-bar").css( "margin-top", - $("#topbar").height() );
	    }else {
	        $("#topbar").css( "margin-top", 0 );
	    }
	}else{
		$("#header").removeClass( "fixed" );
	    $("main").css( "padding-top", '');
        $(".header-main").removeClass('hide');
        $("#topbar").css( "margin-top", 0 );

	}
	
}
function processFloatTopbar(topbarAdd, scroolAction){
	if(topbarAdd){
		$("#header").addClass( "fixed" );
	    $("main").css( "padding-top", $("#header").height());
	    var hideheightBar =  $("#header").height()+120;
	    var pos = $(window).scrollTop();
	    if( scroolAction && pos >= hideheightBar ){
	        $(".header-main").addClass('hide');
	    }else {
	        $(".header-main").removeClass('hide');
	    }
	}else{
		$("#header").removeClass("fixed");
	    $("main").css( "padding-top", '');
	    $(".header-main").removeClass('hide');
        $("#topbar").css( "margin-top", 0 );
	}

}
function floatHeader(){
	$(window).resize(function(){
		if (!$("body").hasClass("keep-header") || $(window).width() <= 990){
			return;
		}
		if ($(window).width() <= 990)
		{
			processFloatHeader(0,0);
		}
		else if ($(window).width() > 990)
		{
			if ($("body").hasClass("keep-header"))
				processFloatHeader(1,1);
		}
	});
    $(window).scroll(function() {
    	if (!$("body").hasClass("keep-header")) return;
    	if($(window).width() > 990){
	         processFloatHeader(1,1);

    	}
    });
}
function floatTopbar(){
	$(window).resize(function(){
		if (!$("body").hasClass("keep-topbar") || $(window).width() <= 990){
			return;
		}
		if ($(window).width() <= 990)
		{
			processFloatTopbar(0,0);
		}
		else if ($(window).width() > 990)
		{
			if ($("body").hasClass("keep-topbar"))
				processFloatTopbar(1,1);
		}
	});
    $(window).scroll(function() {
    	if (!$("body").hasClass("keep-topbar")) return;
    	if($(window).width() > 990){
	         processFloatTopbar(1,1);

    	}
    });
}
// Back to top
function backtotop(){
	// Hide #back-top first
	$("#back-top").hide();
	// Fade in #back-top
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				$('#back-top').fadeIn();
			} else {
				$('#back-top').fadeOut();
			}
		});
		// Scroll body to 0px on click
		$('#back-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});
}
function panelTool(){
	$('#paneltool .panelbutton').click(function(){
		$('#paneltool .paneltool').toggleClass('active');
	});
}
function changeFloatHeader(){
	$('#floatHeader').click(function(){
		if ($('body').hasClass('keep-header')) {
			$('body').removeClass('keep-header');
			processFloatHeader(0,0);
		}
		else{
			processFloatTopbar(0,0);
			$('body').addClass('keep-header');
			$('body').removeClass('keep-topbar');
			$("#floatTopbar").prop("checked", false);
		};
	});
	$('#floatTopbar').click(function(){
		if ($('body').hasClass('keep-topbar')) {
			$('body').removeClass('keep-topbar');
			processFloatTopbar(0,0);
		}
		else{
			processFloatHeader(0,0);
			$('body').addClass('keep-topbar');
			$('body').removeClass('keep-header');
			$("#floatHeader").prop("checked", false);
		};
	});
}
function changeLayoutMode(){
    $('.dynamic-update-layout').click(function(){
        var currentLayoutMode = $('.dynamic-update-layout.selected').data('layout-mode');
        if(!$(this).hasClass('selected'))
        {
            $('.dynamic-update-layout').removeClass('selected');
            $(this).addClass('selected');
            selectedLayout = $(this).data('layout-mode');
            $('body').removeClass(currentLayoutMode);
            $('body').addClass(selectedLayout);
        }
    });
}
function changeHeaderStyle(){
    $('.dynamic-update-header').click(function(){
        var currentHeaderMode = $('.dynamic-update-header.selected').data('header-style');
        if(!$(this).hasClass('selected'))
        {
            $('.dynamic-update-header').removeClass('selected');
            $(this).addClass('selected');
            selectedHeaderStyle = $(this).data('header-style');
            $('body').removeClass(currentHeaderMode);
            $('body').addClass(selectedHeaderStyle);
        }
    });
}