class Effects {
    constructor() {
        this.scrollTo_anchor();
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
}

export default Effects;