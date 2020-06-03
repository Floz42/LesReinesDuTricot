class Effects {
    constructor() {
        this.scrollTo_anchor();
        this.navbar_onScroll();
        this.reduce_navbar();
    }

    /** 
     * @description To scroll to each anchor when user click on navbar links
    */
   scrollTo_anchor() {
        $('.scrollTo').on('click', function() {
            let anchor = $(this).attr('href');
            $('html, body').animate({ scrollTop: $(anchor).offset().top - 80 }, 1000 );
            return false;
        });
    }
    
    /**
     * @description Change navbar on scroll
     */
    navbar_onScroll() {
        $(window).scroll(function() {
            if (document.location.pathname === '/') {
                if ($(this).scrollTop() > 200) {
                    $('#navbar').addClass('navbar_scroll');
                    $('#navbar').removeClass('navbar_top');  
                    $('#navbar div ul li a').css({'font-size': '1em', 'transition': '0.5s'});  
                } else {
                    $('#navbar').removeClass('navbar_scroll');
                    $('#navbar').addClass('navbar_top');    
                    $('#navbar div ul li a').css('font-size', '1.3em');  
                }
            }
        });
    }

    reduce_navbar() {
        if (document.location.pathname !== '/') {
            $('#navbar').addClass('navbar_scroll');
            $('#navbar').removeClass('navbar_top');  
            $('#navbar div ul li a').css({'font-size': '1em'});  
        }
    }
}

export default Effects;