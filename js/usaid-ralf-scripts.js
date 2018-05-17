jQuery(document).ready(function($){
  $('.factor-grid').on('change', 'input[type="checkbox"]', function(){
    if(this.checked){
      $(this).parent().addClass('active');
    }
    else{
      $(this).parent().removeClass('active');
    }
  })
});