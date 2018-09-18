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

  $(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

  $('.impact-by-sector>h2').on('click', '.dashicons-excerpt-view', function(){
    $(this).removeClass('dashicons-excerpt-view').addClass('dashicons-list-view');
    $(this).attr('data-original-title', 'Contract All');
    $('#impacts-accordion .collapse').collapse('show');
  });
  $('.impact-by-sector>h2').on('click', '.dashicons-list-view', function(){
    $(this).removeClass('dashicons-list-view').addClass('dashicons-excerpt-view');
    $(this).attr('data-original-title', 'Expand All');
    $('#impacts-accordion .collapse').collapse('hide');
  });

  //clear search history
  $('#clear-search-history').on('click', function(e){
    e.preventDefault();

    Cookies.remove('STYXKEY_usaidralf_search_history', { path:'/' });
    $(this).parent().remove();
  });
});