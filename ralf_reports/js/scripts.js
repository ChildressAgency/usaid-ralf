$(document).ready(function($){
  //define the save/remove buttons
  var saveToReportButton = '<a href="#" class="btn-main btn-report save-to-report">Save To Report</a>';
  var removeFromReportButton = '<a href="#" class="btn-main btn-report remove-from-report">Remove From Report</a>';
  var reportIdsCookieName = 'STYXKEY_report_ids'; //STYXKEY is required by pantheon.io for some reason

  //get the report ids saved so far from the cookie
  var savedReportIds_cookie = Cookies.get(reportIdsCookieName);

  if(savedReportIds_cookie){
    //save the report ids from the cookie into an array
    var savedReportIds = savedReportIds_cookie.split(',').map(Number);
  
    //loop through each article and see if its already been saved, then update the button
    $('.ralf-article').each(function(){
      var $articleReportButton = $(this).find('.report-button');
      var articleId = $articleReportButton.data('article_id');

      if(savedReportIds.indexOf(articleId) < 0){
        //this article id has not been saved
        $articleReportButton.html(saveToReportButton);

        //setup sidebar report button
        $('.results-sidebar').find('.report-button').html(saveToReportButton);
        //if($('#sidebar-report-button')){
        //  $('#sidebar-report-button').removeClass('remove-from-report').addClass('save-to-report').text('Save To Report');
        //}
      }
      else{
        //this article id has been saved
        $articleReportButton.html(removeFromReportButton);

        //setup sidebar report button
        $('.results-sidebar').find('.report-button').html(removeFromReportButton);
        //if($('#sidebar-report-button')){
        //  $('#sidebar-report-button').removeClass('save-to-report').addClass('remove-from-report').text('Remove From Report');
        //}
      }
    });
  }
  else{
    //if no report ids have been saved to the cookie so far, set all buttons as savers
    $('.report-button').html(saveToReportButton);
    
    //setup sidebar report button
    //if($('#sidebar-report-button')){
    //  $('#sidebar-report-button').removeClass('remove-from-report').addClass('save-to-report').text('Save To Report');
    //}
  }
  
  //save report button clicked
  $('.report-button').on('click', '.save-to-report', function(e){
    e.preventDefault();
    $clickedButtonParent = $(this).parent('.report-button');
    
    //this will hold the string of ids to put back into the cookie
    var reportIds = '';
    //get the article id for the button
    var articleId =$clickedButtonParent.data('article_id');
    //get fresh cookie
    var savedReportIds_cookie = Cookies.get(reportIdsCookieName);

    if(savedReportIds_cookie){
      //save the report ids from the cookie into an array
      var savedReportIds = savedReportIds_cookie.split(',').map(Number);

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
    $('.report-button').html(removeFromReportButton);
    $clickedButtonParent.append('<span><em>Added to report!</em></span>');
    //$(this).parent('.report-button').html(removeFromReportButton + '<span><em>Added to report!</em></span>');

    //change the sidebar button to remove
    //$('.results-sidebar').find('.report-button').html(removeFromReportButton + '<span><em>Added to report!</em></span>');
    //if($('#sidebar-report-button')){
    //  $('#sidebar-report-button').removeClass('save-to-report').addClass('remove-from-report').text('Remove From Report');
    //}
  });

  //remove report button clicked
  $('.report-button').on('click', '.remove-from-report', function(e){
    e.preventDefault();
    $clickedButtonParent = $(this).parent('.report-button');

    //this will hold the string of ids to put back into the cookie
    var reportIds = '';
    //get the article id for the button
    var articleId = $clickedButtonParent.data('article_id');
    
    //get fresh cookie
    var savedReportIds_cookie = Cookies.get(reportIdsCookieName);

    if(savedReportIds_cookie){
      //save the report ids from the cookie into an array
      var savedReportIds = savedReportIds_cookie.split(',').map(Number);
      
      //find the index of the article id in the cookie array
      var articleIdIndex = savedReportIds.indexOf(articleId);

      if(articleIdIndex > -1){
        //the article id is in the cookie so remove it
        savedReportIds.splice(articleIdIndex, 1);
        reportIds = savedReportIds.toString();
        //console.log(reportIds);
        //save cookie here, because if there wasn't a cookie before it doesn't matter
        Cookies.set(reportIdsCookieName, reportIds, { expires:30 });
      }
    }

    $('.report-button').html(saveToReportButton);
    $clickedButtonParent.append('<span><em>Removed from report</em></span>');
    //$(this).parent('.report-button').html(saveToReportButton + '<span><em>Removed from report</em></span>');

    //change sidebar button to save
    //$('.results-sidebar').find('.report-button').html(saveToReportButton + '<span><em>Removed from report</em></span>');
    //if($('#sidebar-report-button')){
    //  $('#sidebar-report-button').removeClass('remove-from-report').addClass('save-to-report').text('Save To Report');
    //}
  });
  
  //email report functions
  $('.email-report').on('click', '.send-email', function( e ){
    var $button = $(this);
    //get the entered email addresses
    var emailAddresses = $('#email-addresses').val();

    var validEmailAddresses = validateEmailAddresses(emailAddresses);

    if(emailAddresses.length == 0 || validEmailAddresses == false){
      //email addresses field was empty
      $('#email-addresses').css('border', '2px solid red');
      $('.email-response').text('Please enter only valid email addresses.');
      return false;
    }

    //disable button and show placeholder so user knows something is happening
    $button.width($button.width()).text('...').prop('disabled', true);

    var data = {
      'action' : 'send_rtf_report',
      'report_ids' : $button.data('report_ids'),
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
        //console.log(response);
        $('.email-response').html();
      }

      //$button.width($button.width()).text('Send Email').prop('disabled', false);
    });
  });

  function validateEmailAddresses(emailAddresses){
    //var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var re = /\S+@\S+\.\S+/;

    var emails = emailAddresses.split(',');
    //emails.forEach(function(email){
    //  if(re.test(email) == false){
    //    return false;
    //  }
    //});

    for(var i=0; i<emails.length; i++){
      //console.log(re.test(emails[i]));
      if(re.test(emails[i]) == false){
        return false;
      }
    }

    return true;
  }
});