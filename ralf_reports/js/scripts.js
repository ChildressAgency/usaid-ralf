$(document).ready(function($){
  //var articleId = $('.ralf-article').data('article_id');

  //reportIdArr, better var name
  
  
  $('.ralf-article').each(function(){

  })

  if(articleId){
    var reportIds = Cookies.get('STYXKEY_report_ids');
    var reportIdArr;

    $('.report-button').each(function () {
      $(this).html('<a href="#" class="btn-main btn-report save-to-report">Save To Report</a>');
    });

    if(reportIds){
      var reportIdArr = reportIds.split(',');

      if(reportIdArr.indexOf(articleId) > -1){
        //report_button = '<a href="#" id="removeFromReport" class="save-to-report">Remove From Report</a>';
        $('.report-button').each(function () {
          $(this).html('<a href="#" id="removeFromReport" class="btn-main btn-report">Remove From Report</a>');
        });
      }
    }
  }

  $('.report-button').on('click', '#saveToReport', function (e) {
    e.preventDefault();
    if(reportIdArr){
      if(reportIdArr.indexOf(articleId) < 0){
        reportIdArr.push(articleId);
      }

      reportIds = reportIdArr.toString();
    }
    else{
      reportIds = articleId;
    }

    Cookies.set('STYXKEY_report_ids', reportIds, { expires: 30 });
    $('.report-button').each(function () {
      $(this).html('<a href="#" id="removeFromReport" class="btn-main btn-report">Remove From Report</a><span><em>Added to report!</em></span>');
    });
  });

  $('.report-button').on('click', '#removeFromReport', function (e) {
    e.preventDefault();

    var idIndex = reportIdArr.indexOf(articleId);
    if(idIndex > -1){
      reportIdArr.splice(idIndex, 1);
    }

    reportIds = reportIdArr.toString();
    Cookies.set('STYXKEY_report_ids', reportIds, { expires: 30 });
    $('.report-button').each(function () {
      $(this).html('<a href="#" id="saveToReport" class="btn-main btn-report">Save To Report</a><span><em>Removed from report</em></span>');
    });
  });  

  
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