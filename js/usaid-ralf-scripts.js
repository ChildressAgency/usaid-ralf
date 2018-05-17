jQuery(document).ready(function($){
/*$('.factor-grid').masonry({
    itemSelector: '.grid-item',
    gutter: 5,
    //horizontalOrder:true,
    fitWidth:true
  });*/

  $('.factor-grid').on('change', 'input[type="checkbox"]', function(){
    if(this.checked){
      $(this).parent().addClass('active');
    }
    else{
      $(this).parent().removeClass('active');
    }
  })
});