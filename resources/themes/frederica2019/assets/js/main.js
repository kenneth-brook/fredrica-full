function scrollSticky() {
    $(window).scroll(function() {
        var i = $("header");
        60 <= $(window).scrollTop() && !$(".menu").hasClass("open") ? i.addClass("sticky") : i.removeClass("sticky")
    })
}
$(document).ready(function() {
    /*sizeVideo(),*/ hamburger_mobile(), scrollSticky(), $(window).resize(function() {
        /*sizeVideo(),*/ hamburger_mobile()
    })
     //External URLs  
    //$('a[href^="http://"]')
    //    .not('a[href*="'+ location.hostname +'"]')
    //    .attr('target','_blank'); 

    //$('a[href^="https://"]')
    //    .not('a[href*="'+ location.hostname +'"]')
    //    .attr('target','_blank');
});
var introiHeight = $(".inner-intro").height();
1200 < $(window).width() && 400 < introiHeight && !($(".inner-intro").hasClass('no-shorter')) && ($(".inner-intro").css({
    height: "405px",
    overflow: "hidden"
}), $(".inner-intro").closest("section").append('<div class = "button-center pd-top-30"><a href = "javascript:void(0)" class = "btn btn-blue open-intro">Read more</a></div>'), $(".inner-intro").addClass("shorter")), $(".open-intro").on("click", function() {
    $(this).hide(), $(".inner-intro").css({
        height: "initial",
        overflow: "auto"
    }), $(".inner-intro").removeClass("shorter")
}), $('[class*= "btn"],.info div,.title-background').each(function() {
    $(this).append('<span><svg id="arrow-right" style = "width:30px;margin-top:-4px;margin-left:5px;"data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 15"><polygon fill="#fff" points="21.6 3.91 20.92 4.64 23.99 7.5 4 7.5 4 8.5 23.99 8.5 20.92 11.35 21.6 12.09 26 8 21.6 3.91"/></svg></span>')
}), $(".arrow-bottom").append('<div class = "icon-arrow"></div>'), $(".icon-arrow").on("click", function() {
    var i = $(this).closest("section").next();
    //console.log(i);
    $("html, body").animate({
        scrollTop: i.offset().top - $("#header").height() - 100
    }, 1e3)
});
var swiper = new Swiper(".swiper-container", {
    direction: 'horizontal',
    speed: 1000,
    autoplay: {
        delay: 9000,
    },
    loop: true,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev"
    },
    on: {
        slideChangeTransitionStart: function() {
            $(".slide-title").hide(0), $(".slide-title").removeClass("aos-init").removeClass("aos-animate"), $(".slide-intro").hide(0), $(".slide-intro").removeClass("aos-init").removeClass("aos-animate"), $(".slide-button").hide(0), $(".slide-button").removeClass("aos-init").removeClass("aos-animate")
        },
        slideChangeTransitionEnd: function() {
            $(".slide-title").show(0), $(".slide-intro").show(0), $(".slide-button").show(0), AOS.init()
        }
    }
});
AOS.init();
$(".video-foreground").css({'background': 'transparent'});
var video = $(".video-foreground iframe"),
    video_id = $("#id-video").data("id");
    var flag = /^\d+$/.test(video_id);
       if(!flag){
           var url = "https://www.youtube.com/embed/" + video_id + "?autoplay=1&controls=0&showinfo=0&rel=0";
       }else {
            var url = "https://player.vimeo.com/video/" + video_id + "?autoplay=1&showinfo=0&rel=0";
       }
    var seconds = 1e3 * $("#id-video").data("seconds");

function sizeVideo() {
    var i = 1.78 * video.outerHeight(),
        e = i / 2;
    video.css({
        width: i + "px",
        left: "50%",
        "margin-left": "-" + e + "px"
    })
}
video.attr("src", ""), $(".play").on("click", function() {
    $(".video-foreground").css({'background':'#fff'});
    $(this).hide(), video.attr("src", url), setTimeout(function() {
        //video.attr("src", ""), $(".play").show()
    }, seconds)
});
var image = $(".hero-image ").find("img").attr("src");

function hamburger_mobile() {
    if ($(window).width() <= 1024) {
        $("#header");
        var i = $("#logo"),
            e = $("#tel").find("a").attr("href"),
            o = $("#menu");
        $(".inner-close").on("click", function() {
            o.removeClass("open")
        }), $(".ham_menu").on("click", function() {
            o.addClass("open")
        }), $(".has_children").children('a').on("click", function(e) {
            e.preventDefault();
            let submenu = $(this).closest('.has_children').children('ul');
            console.log(submenu);
            if(submenu.hasClass('open')){
                 window.location.href = $(this).attr("href");
                 //console.log( $(this).attr("href"));
            }
            submenu.addClass("open");  

        })
        //$('.has_children ul a').click(function() {
            //window.location.href = $(this).find("a").attr("href");
           // console.log( $(this).attr("href"));
        //})
    }
}
$(".hero-image ").css({
    'background': "url(" + image + ")",
    "background-position": "center center"
});

lightbox.option({
    'resizeDuration': 100,
    'wrapAround': true
})

$('.title-background').on('click', function(e){
    let url = $(e.currentTarget).data('href');  
    //console.log(url); 
    if (url){
      if(url.includes('https://') || url.includes('http://')){
        setTimeout(function() {
             $('#lightboxOverlay,#lightbox').css({ 'display': "none" });
             e.preventDefault();
            window.open(url,'_blank');
        }, 100);
      } else{
        setTimeout(function() {
             $('#lightboxOverlay,#lightbox').css({ 'display': "none" });
             e.preventDefault();
             window.location.href = url;
             
        }, 100)
      }
        
    }
})
scrollTop();
$(".button-up").click(function(){
    $("html,body").animate({
        scrollTop:$("body").offset().top},"1000");
    return false});


function scrollTop() {
    $(window).scroll(function() {
        var i = $(".button-up");
        900 >= $(window).scrollTop() ? i.addClass("hidden") : i.removeClass("hidden")
    })
}    

//lazy loading
let lazy_images = $('.lazy').toArray();
let height_before = 400;
function lazyLoad(){
        lazy_images.forEach(function(el) {
            //console.log($(el));
            if($(el).offset().top < (window.innerHeight + window.pageYOffset + height_before)){
                if($(el).hasClass('image-background') || $(el).hasClass('video')){
                    $(el).css('background-image', 'url(' + $(el).attr('data-src') + ')');
                }
                $(el).attr('src', $(el).attr('data-src'));
                
                //console.log('success', $(el).offset().top, window.innerHeight + window.pageYOffset + height_see);
            }            
        });          
}
lazyLoad()
window.addEventListener('scroll', lazyLoad);
window.addEventListener('resize', lazyLoad);