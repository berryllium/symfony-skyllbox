(function($) {
  "use strict";
  
  $("#menu-toggle").on('click', function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled").hasClass("toggled") ? $(this).text('Показать меню') : $(this).text('Скрыть меню');
  });

})(jQuery);
