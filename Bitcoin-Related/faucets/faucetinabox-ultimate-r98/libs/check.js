$(function() {
    function check() {
        if(!document.getElementById('tester')) {
            if(typeof disableButtonTimer == 'function') {
                disableButtonTimer();
            }
            $('.claim-button')
                .prop('disabled', true)
                .text('Please disable AdBlock and reload')
                .val('Please disable AdBlock and reload');
            if ($('.step1_in').length==0) {
                $('.step1').css('position', 'relative').css('padding', '5px');
                $('.step1 a').attr('tabindex', '-1');
                $('.step1').append('<div class="step1_in">Please disable your AdBlock plugin. Don\'t just whitelist our website, but completely pause/disable it while browsing! <a href=".">Click here when you are done</a>.</div>');
                $('#id_shortlink').html($('#id_shortlink a').html());
            }
        }
    }

    check();
    setInterval(check, 500);
});
