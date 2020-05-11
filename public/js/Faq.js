class Faq {
    constructor() {
        this.hide_answer();
        this.accordeon();
    }
    
    /**
     * @description Hide by default answer to questions
     */
    hide_answer() {
        return $('.answer_faq').each(function() {
            $(this).hide();
        });
    }

    /**
     * @description show answer when user click on function and toggle arrow class
     */
    accordeon() {
        $('.question_faq').each(function() {
            $(this).click(function() {
                $(this).next().toggle('fast');
                $(this).children().children('.arrow_question').toggleClass('fa-arrow-down, fa-arrow-up')
        
            })
        })
    }



}

export default Faq;