/* ---------------------------------------------------------------- */
/* Mobile Menu
/* ---------------------------------------------------------------- */
jQuery('#mobile-navigation-btn').on("click",function(){
	"use strict";
	//jQuery('.mobile-nav').addClass('show').animate({ right: '0'}, 250, 'easeInCubic');
	//jQuery('body').addClass('expanded').animate({ left: '-300'}, 250, 'easeInCubic');
	jQuery('#mobile-navigation').stop(true,true).slideToggle(250, 'easeOutBack'); //easeInOutSine works also nice at 200ms
		return false;
});

jQuery('.close-mobile-nav').on("click",function(){
	"use strict";
	jQuery('.mobile-nav').removeClass('show').animate({ right: '-300'}, 200, 'easeOutCubic');
	//jQuery('body').removeClass('expanded').animate({ left: '0'}, 200, 'easeOutCubic');
});

jQuery('#mobile-navigation .container ul li').each(function(){
	if(jQuery(this).find('> ul').length > 0) {
		 jQuery(this).addClass('has-ul');
		 jQuery(this).find('> a').append('<i class="fa fa-angle-down"></i>');
	}
});
	
jQuery('#mobile-navigation ul li:has(">ul") > a i').click(function(){
	jQuery(this).parent().parent().toggleClass('open');
	jQuery(this).parent().parent().find('> ul').stop(true,true).slideToggle(300, 'easeOutBack');
	return false;
});

/* ---------------------------------------------------------------- */
/* Document Ready
/* ---------------------------------------------------------------- */

//Variables for sticky navigation
var leadx_scrollHeight = 500,
    leadx_nav,
    leadx_navOuterHeight,
    leadx_navScrolled = false,
    leadx_navFixed = false,
    leadx_outOfSight = false,
    leadx_scrollTop = 0;

jQuery(document).ready(function($) {
	"use strict";
	
	// Update scroll variable for scrolling functions
    addEventListener('scroll', function() {
        leadx_scrollTop = window.pageYOffset;
    }, false);
    
        
	// Fix nav to top while scrolling
    leadx_nav = $('body .sticky-nav:first');
    leadx_navOuterHeight = $('body header:first').outerHeight();
    window.addEventListener("scroll", stickyNav, false);
    
	// Dropdown Nav Menu
	$('.nav-menu ul li').on({
        mouseenter: function () {
            //stuff to do on mouse enter
            var sub = $(this).find('.second-lvl');
    		if(sub.length > 0 && $(window).width() > 979) {
    			sub.stop().fadeIn(300, 'easeOutCubic');
    		}
        },
        mouseleave: function () {
            //stuff to do on mouse leave
            var sub = $(this).find('.second-lvl');
    		if(sub.length > 0 && $(window).width() > 979) {
    			sub.stop().fadeOut(150, 'easeOutCubic');
    		}
        }
    });
    

	// Dropdown Nav Menu
	$(".responsive-menu .nav-menu").hide();
    $(".toggle-menu").on("click",function() {
        $(".responsive-menu .nav-menu").slideToggle(500);
    });
    
	// Mega Menu Background Image
	$('.mega-menu').each(function(){
		var menuBackground = ($(this).attr("data-background-image")) ? ($(this).attr("data-background-image")) : '',
			menuBackgroundPos = ($(this).attr("data-background-pos")) ? ($(this).attr("data-background-pos")) : '';
		$(this).find('.second-lvl').css({
			'background-image' : 'url('+menuBackground+')',
			'background-position' : menuBackgroundPos,
		});
	});
	
	flexsliderLoad();
	
	//Masonry Blog
	var $blogcontainer = $('.blog-masonry');

	$blogcontainer.imagesLoaded( function() {
			setTimeout(function(){
		        $blogcontainer.isotope({ layoutMode : 'masonry', itemSelector: '.masonry-item' });
				$blogcontainer.animate({'opacity' : 1}, 600);
				flexsliderLoad();
		    }, 500);
	    });
	    
	// Append .background-image-holder <img>'s as CSS backgrounds
    $('.background-image-holder').each(function() {
        var imgSrc = $(this).children('img').attr('src');
        $(this).css('background', 'url("' + imgSrc + '")');
        $(this).children('img').hide();
        $(this).css('background-position', 'initial');
    });
    
    // Fade in background images
    setTimeout(function() {
        $('.background-image-holder').each(function() {
            $(this).addClass('fadeIn');
        });
    }, 200);
    
    // Number Field Stepper
  	jQuery('.qty').bootstrapNumber({
		upClass: 'plus',
		downClass: 'minus'
	});
	
	jQuery('a.lightbox').nivoLightbox({
		effect: 'fade',                             // The effect to use when showing the lightbox
		theme: 'default',                           // The lightbox theme to use
		keyboardNav: true,                          // Enable/Disable keyboard navigation (left/right/escape)
		clickOverlayToClose: true,                  // If false clicking the "close" button will be the only way to close the lightbox
	});
	
	/*---------------------------------------------- 
				   	 P A R A L L A X
	------------------------------------------------*/
	if(jQuery().parallax) { 
		jQuery('.parallax-section').parallax();
	}
    
});
/* ---------------------------------------------------------------- */
/* Top of Page Link
/* ---------------------------------------------------------------- */

jQuery(window).load(function() {
    animateOnScroll();
});

jQuery(window).scroll(function () {
	"use strict";
	if (jQuery(this).scrollTop() > 300) {
		jQuery('#go-top').fadeIn();
	} else {
		jQuery('#go-top').fadeOut();
	}
	
	animateOnScroll();
});

jQuery('#go-top').on("click",function () {
	"use strict";
	jQuery("html, body").animate({ scrollTop: 0 }, 600, "easeInOutExpo");
	return false;
});

/* ---------------------------------------------------------------- */
/* Smooth scroll to inner links
/* ---------------------------------------------------------------- */
jQuery('nav a[href^="#"]:not(a[href="#"]), a.btn[href^="#"], .ttbase-intro-header a[href^="#"]').smoothScroll({
    offset: -350,
    speed: 800
});
	
/* ---------------------------------------------------------------- */
/* Forms
/* ---------------------------------------------------------------- */
jQuery('.wpcf7-checkbox .wpcf7-list-item').addClass('checkbox-option').prepend('<div class="outer"><div class="inner" /></div>');
jQuery('.gfield_checkbox li').addClass('checkbox-option').prepend('<div class="outer"><div class="inner" /></div>');
jQuery('.wpcf7-acceptance').parent().addClass('checkbox-option acceptance').prepend('<div class="outer"><div class="inner" /></div>');
jQuery('.wpcf7-radio .wpcf7-list-item').addClass('radio-option').prepend('<div class="outer"><div class="inner" /></div>');
jQuery('.gfield_radio li').addClass('radio-option').prepend('<div class="outer"><div class="inner" /></div>');
jQuery('select').wrap('<div class="select-option" />').parent().prepend('<i class="fa fa-angle-down"></i>');
jQuery(":file").filestyle({buttonName: "btn-primary", icon: false});

	
// Checkboxes
jQuery('.checkbox-option').click(function() {
    jQuery(this).toggleClass('checked');
    var checkbox = jQuery(this).find('input');
    if (checkbox.prop('checked') === false) {
        checkbox.prop('checked', true);
    } else {
        checkbox.prop('checked', false);
    }
});

// Radio Buttons
jQuery('.radio-option').click(function() {
    jQuery(this).closest('form').find('.radio-option').removeClass('checked');
    jQuery(this).addClass('checked');
    jQuery(this).find('input').prop('checked', true);
});

/* do animations if element is visible
------------------------------------------------*/
function animateOnScroll() {
	
	/* has-animation elements */
	jQuery('.has-animation').each(function() {
		var thisItem = jQuery(this);
		if (jQuery(window).width() > 1024) {
			var visible = thisItem.visible(true);
			var delay = thisItem.attr("data-delay");
			if (!delay) { delay = 0; }
			if (thisItem.hasClass( "animated" )) {} 
			else if (visible) {
				thisItem.delay(delay).queue(function(){thisItem.addClass('animated');});
			}
		} else {
			thisItem.addClass('animated');	
		}
	});
		
}
/* do animations function
------------------------------------------------*/

//Function for sticky nav
function stickyNav() {

    var scrollY = leadx_scrollTop;

    if (scrollY <= 0) {
        if (leadx_navFixed) {
            leadx_navFixed = false;
            leadx_nav.removeClass('fixed');
        }
        if (leadx_outOfSight) {
            leadx_outOfSight = false;
            leadx_nav.removeClass('nav-hide');
        }
        if (leadx_navScrolled) {
            leadx_navScrolled = false;
            leadx_nav.removeClass('scrolled');
        }
        return;
    }

    if (scrollY > leadx_scrollHeight) {
        if (!leadx_navScrolled) {
            leadx_nav.addClass('scrolled');
            leadx_navScrolled = true;
            return;
        }
    } else {
        if (scrollY > leadx_navOuterHeight) {
            if (!leadx_navFixed) {
                leadx_nav.addClass('fixed');
                leadx_navFixed = true;
            }

            if (scrollY > leadx_navOuterHeight * 2) {
                if (!leadx_outOfSight) {
                    leadx_nav.addClass('nav-hide');
                    leadx_outOfSight = true;
                }
            } else {
                if (leadx_outOfSight) {
                    leadx_outOfSight = false;
                    leadx_nav.removeClass('nav-hide');
                }
            }
        } else {
            if (leadx_navFixed) {
                leadx_navFixed = false;
                leadx_nav.removeClass('fixed');
            }
            if (leadx_outOfSight) {
                leadx_outOfSight = false;
                leadx_nav.removeClass('nav-hide');
            }
        }

        if (leadx_navScrolled) {
            leadx_navScrolled = false;
            leadx_nav.removeClass('scrolled');
        }

    }
}

//Function for blog flexslider
function flexsliderLoad() {

     jQuery('.flexslider').flexslider({
        selector: ".slides > li",
        animation: "slide", 
        prevText: "",
        nextText: "",
        easing: "easeOutQuad", 
        smoothHeight: true,
        pauseOnHover: true,
        animationSpeed: 300
    });

}



//Disabled because input fields are not working on touch devices

// jQuery('body').on('touchstart', function(e) {
//     jQuery('input, textarea').css("pointer-events","auto");
// });
// jQuery('body').on('touchmove', function(e) {
//     jQuery('input, textarea').css("pointer-events","none");
// });
// jQuery('body').on('touchend', function(e) {
//     setTimeout(function() {
//         jQuery('input, textarea').css("pointer-events", "none");
//     },0);
// });

