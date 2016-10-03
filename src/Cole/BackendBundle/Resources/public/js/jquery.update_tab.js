  function tab_is_open(ref){
    n_total= $('#tabs>ul:first').children("li").size();

    for (i = 0; i < n_total; i++) 
      {
        var ref2= $('#tabs>ul>li:eq('+i+') a').attr("ref");
        if(ref==ref2)
          return true;
      }
      return false;
  }

(function($){ 
  $.fn.extend({
    update_tab: function(){

        tab_open=tab_is_open($(this).attr("ref"));
        if(tab_open==true)
        {
          ref=$(this).attr("ref");
          href=$("#tabs ul li a[ref='"+ref+"']").attr("href");
          $(href).load($(this).attr("href"));
        }
     },
    close_tab: function(){

        tab_open=tab_is_open($(this).attr("ref"));
        if(tab_open==true)
        {
          ref=$(this).attr("ref");

          href=$("#tabs ul li a[ref='"+ref+"']").next("span").trigger("click");
        }
     }
  });
})(jQuery)