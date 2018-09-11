$(document).ready(function($){
  $('.email-report').on('click', '.send-email', function( e ){
    console.log('clicked');
    var $button = $(this);

    if($('.test-email-message').val().length === 0){
      $('.test-email-message').css('border', '2px solid red');
      return false;
    }
    else{
      
    }

    $button.width($button.width()).text('...').prop('disabled', true);

    var data = {
      'action' : 'send_rtf_report',
      'post_id' : $button.data('post_id'),
      'nonce' : $button.data('nonce'),
      'report' : $('.test-email-message').val()
    };

    $.post(ralf_settings.ralf_ajaxurl, data, function(response){
      if(response.success == true){
        $button.remove();
        $('test-email-message').remove();

        $('.email-response').html(response.data);
      }
      else{
        $('.email-response').html(ralf_settings.error);
      }

      $button.width($button.width()).text('Send Email').prop('disabled', false);
    });
  });
});