/**
* CFMPT My Post Time Plugin by Ammar Ilays
*/
(function ($) {
    var cfmpt_init = false;
    $(window).load(function () {
        $(window).scroll(function () {
            if (!cfmpt_init) {
                cfmptPageProgressBar(cfmpt_mpt.progressbar_content_selector);
                cfmpt_init = true;
            }
        });
    });
    function cfmptPageProgressBar(target) {
        var target_set = true;
        if (!target) {
            target = document;
            target_set = false;
        }
        var winHeight = $(window).height(),
        offsetTop = (target_set ? $(target).first().offset().top : 0),
        max = Math.max(Math.floor($(target).first().height() - winHeight), 0),
        progressBar = $('progress.reading-progress');
        progressBar.attr('max', max);

        $(document).on('scroll', function () {
            value = $(window).scrollTop() - offsetTop;
            if (value > max) value = max;
            else if (value < 0) value = 0;
            progressBar.attr('value', Math.floor(value));
        });
    }
})(jQuery);