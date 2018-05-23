jQuery(document).ready(function($){
  $('.factor-grid').on('change', 'input[type="checkbox"]', function(){
    if(this.checked){
      $(this).parent().addClass('active');
    }
    else{
      $(this).parent().removeClass('active');
    }
  });

  var headerHeight = $('#header-nav').outerHeight(true);
  var footerHeight = $('#footer').outerHeight(true);
  var $resultsSidebar = $('.results-sidebar');

  $resultsSidebar.on('affix.bs.affix', function(){
    $(this).css({'top': 25, 'margin-top': 0});
  });
  $resultsSidebar.on('affix-top.bs.affix', function(){
    $(this).css({'margin-top':80});
  });

  $resultsSidebar.affix({
    offset:{
      //top:headerHeight,
      top: function(){
        return headerHeight + 55;
      },
      bottom:function(){
        return footerHeight + 40;
      }
    }
  });

  //add to report
  if(typeof articleId !== 'undefined'){
    var reportIds = Cookies.get('report_ids');
    var reportIdArr;
    //var report_button = '<a href="#" id="saveToReport" class="save-to-report">Save To Report</a>';
    $('.report-button').each(function () {
      $(this).html('<a href="#" id="saveToReport" class="btn-main btn-report">Save To Report</a>');
    });

    if (reportIds) {
      var reportIdArr = reportIds.split(',');

      if (reportIdArr.indexOf(articleId) > -1) {
        //report_button = '<a href="#" id="removeFromReport" class="save-to-report">Remove From Report</a>';
        $('.report-button').each(function () {
          $(this).html('<a href="#" id="removeFromReport" class="btn-main btn-report">Remove From Report</a>');
        });
      }
    }
  }

  $('.report-button').on('click', '#saveToReport', function (e) {
    e.preventDefault();
    if (reportIdArr) {
      if (reportIdArr.indexOf(articleId) < 0) {
        reportIdArr.push(articleId);
      }

      reportIds = reportIdArr.toString();
    }
    else {
      reportIds = articleId;
    }

    Cookies.set('report_ids', reportIds, { expires: 30 });
    $('.report-button').each(function () {
      $(this).html('<span><em>Added to report</em></span><a href="#" id="removeFromReport" class="btn-main btn-report">Remove From Report</a>');
    });
  });

  $('.report-button').on('click', '#removeFromReport', function (e) {
    e.preventDefault();

    var idIndex = reportIdArr.indexOf(articleId);
    if (idIndex > -1) {
      reportIdArr.splice(idIndex, 1);
    }

    reportIds = reportIdArr.toString();
    Cookies.set('report_ids', reportIds, { expires: 30 });
    $('.report-button').each(function () {
      $(this).html('<span><em>Removed from report</em></span><a href="#" id="saveToReport" class="btn-main btn-report">Save To Report</a>');
    });
  });  
});