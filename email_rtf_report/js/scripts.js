$(document).ready(function($){
  $('.email-report').on('click', '.send-email', function( e ){
    var $button = $(this);
    var emailAddresses = $('#email-addresses').val();

    if(emailAddresses.length === 0){
      $('#email-addresses').css('border', '2px solid red');
      return false;
    }
    else{
      
    }

    $button.width($button.width()).text('...').prop('disabled', true);

    var data = {
      'action' : 'send_rtf_report',
      'post_id' : $button.data('post_id'),
      'nonce' : $button.data('nonce'),
      //'report' : $('.test-email-message').val()
      'email-addresses' : emailAddresses
    };

    $.post(ralf_settings.ralf_ajaxurl, data, function(response){
      if(response.success == true){
        $button.remove();
        $('#email-addresses').remove();

        $('.email-response').html(response.data);
      }
      else{
        $('.email-response').html(ralf_settings.error);
      }

      //$button.width($button.width()).text('Send Email').prop('disabled', false);
    });
  });
});