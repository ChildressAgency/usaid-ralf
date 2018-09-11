$(document).ready(function($){
  //define the save/remove buttons
  var saveToReportButton = '<a href="#" class="btn-main btn-report save-to-report">Save To Report</a>';
  var removeFromReportButton = '<a href="#" class="btn-main btn-report remove-from-report">Remove From Report</a>';
  var reportIdsCookieName = 'STYXKEY_report_ids'; //STYXKEY is required by pantheon for some reason

  //get the report ids saved so far from the cookie
  var savedReportIds_cookie = Cookies.get(reportIdsCookieName);

  if(savedReportIds_cookie){
    //save the report ids from the cookie into an array
    var savedReportIds = savedReportIds_cookie.split(',');
  
    //loop through each article and see if its already been saved, then update the button
    $('.ralf-article').each(function(){
      var $article = $(this);
      var articleId = $article.data('article_id');

      if(savedReportIds.indexOf(articleId) > -1){
        //this article id has not been saved
        $article.find('.report-button').html(saveToReportButton);
      }
      else{
        //this article id has been saved
        $article.find('.report-button').html(removeFromReportButton);
      }
    });
  }
  else{
    //if no report ids have been saved to the cookie so far, set all buttons as savers
    $('.report-button').each(function(){
      $(this).html(saveToReportButton);
    });
  }
  
  //save report button clicked
  $('.report-button').on('click', '.save-to-report', function(e){
    e.preventDefault();
    
    //this will hold the string of ids to put back into the cookie
    var reportIds = '';
    //get the article id for the button
    var articleId = $(this).parents('.ralf-article').data('article_id');
    //get fresh cookie
    var savedReportIds_cookie = Cookies.get(reportIdsCookieName);

    if(savedReportIds_cookie){
      //save the report ids from the cookie into an array
      var savedReportIds = savedReportIds_cookie.split(',');

      if(savedReportIds.indexOf(articleId) < 0){
        //this article id is not already in the cookie
        savedReportIds.push(articleId);
        reportIds = savedReportIds.toString();
      }
    }
    else{
      //there aren't any saved reports so far
      reportIds = articleId;
    }

    //put the report ids into the cookie
    Cookies.set(reportIdsCookieName, reportIds, { expires:30 });
    //change the save button to remove
    $(this).html(removeFromReportButton . '<span><em>Added to report!</em></span>');
  });

  //remove report button clicked
  $('.report-button').on('click', '.remove-from-report', function(e){
    e.preventDefault();

    //this will hold the string of ids to put back into the cookie
    var reportIds = '';
    //get the article id for the button
    var articleId = $(this).parents('.ralf-article').data('article_id');
    //get fresh cookie
    var savedReportIds_cookie = Cookies.get(reportIdsCookieName);

    if(savedReportIds_cookie){
      //save the report ids from the cookie into an array
      var savedReportIds = savedReportIds_cookie.split(',');

      //find the index of the article id in the cookie array
      var articleIdIndex = savedReportIds.indexOf(articleId);

      if(articleIdIndex > -1){
        //the article id is in the cookie so remove it
        savedReportIds.splice(articleIdIndex, 1);
        reportIds = savedReportIds.toString();
        //save cookie here, because if there wasn't a cookie before it doesn't matter
        Cookies.set(reportIdsCookieName, reportIds, { expires:30 });
      }
    }

    //change the button save, even there was no cookie
    $(this).html(saveToReportButton . '<span><em>Removed from report</em></span>');
  });
  
  //email report functions
  $('.email-report').on('click', '.send-email', function( e ){
    var $button = $(this);
    //get the entered email addresses
    var emailAddresses = $('#email-addresses').val();

    if(emailAddresses.length === 0){
      //email addresses field was empty
      $('#email-addresses').css('border', '2px solid red');
      return false;
    }

    //disable button and show placeholder so user knows something is happening
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
        //get rid of button and email address field since we're done with them
        $button.remove();
        $('#email-addresses').remove();

        //let the user know its done
        $('.email-response').html(response.data);
      }
      else{
        //there was an error, button and email field are still there for them to try again
        $('.email-response').html(ralf_settings.error);
      }

      //$button.width($button.width()).text('Send Email').prop('disabled', false);
    });
  });
});