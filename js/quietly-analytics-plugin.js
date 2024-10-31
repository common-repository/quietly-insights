var logPrefix = '[Quietly Insights Plugin]',
    quietlyAnalytics = {};

jQuery(function($) {
    // function for entering PIN
    $.fn.pinForm = function() {
        var $context = $(this),
            $input = $('.quietly-analytics__module--input', $context),
            $button = $('.quietly-analytics__module--button', $context),
            $spinner = $('.quietly-analytics__module--spinner', $context),
            $success = $('.result-success', $context),
            $fail = $('.result-fail', $context),
            $successMessage = $('.quietly-analytics__module--message.-success'),
            $failMessage = $('.quietly-analytics__module--message.-fail'),
            $reenter = $('.quietly-analytics__module--re-enter');

        console.log('pinForm initialized', $context);
        console.log($input.val());
        console.log($reenter);

        if ($input.val() !== '') {
            $success.show();
            $successMessage.show();
            $button.hide();
            $input.prop('disabled', true);
        }

        $reenter.click(function(e){
            e.preventDefault();
            $success.hide();
            $successMessage.hide();
            $button.show();
            $input.prop('disabled', false);
        })

        var sendRequest = function() {
            var $action = 'verify_pin',
                $option = 'qap_id',
                $value = $input.val(),
                $domain = window.location.hostname;

            $spinner.css('display','inline-block');
            $button.hide();
            $input.prop('disabled', true);
            // setTimeout(function() {
            //     $spinner.hide();
            //     $success.show();
            //     $message.show();
            // }, 2000);

            console.log($value);
            $.ajax({
                type:   'POST',
                url:    'admin-ajax.php',
                data:   {
                    action    : $action,
                    option    : $option, // your option variable
                    domain    : $domain, // users domain
                    pid       : $value // users propertyId
                },
                dataType: 'json'
            }).done(function( json ) {
                console.log(logPrefix, json);
                if (json.status !== 200) {
                    $spinner.hide();
                    $fail.show();
                    $failMessage.show();
                } else {
                    $spinner.hide();
                    $success.show();
                    $successMessage.show();
                }
            }).fail(function( error ) {
                console.log(logPrefix, error);
                $spinner.hide();
                $fail.show();
                $failMessage.show();
            });
        };

        $button.click(function(e) {
            e.preventDefault();
            // send request
            sendRequest();
        });
    };

    $(document).ready(function() {
        console.log(logPrefix+' initialized');
        $('#quietly-analytics__pin-form').pinForm();
    });
});
