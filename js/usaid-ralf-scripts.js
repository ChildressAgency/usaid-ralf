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
});