Popover:

  showErrors: function(errorMap, errorList) {
    $.each(this.successList, function(index, value) {
      return $(value).popover("hide");
    });
    return $.each(errorList, function(index, value) {
      var _popover;
      _popover = $(value.element).popover({
        trigger: "manual",
        placement: "top",
        content: value.message,
        template: "<div class=\"popover\"><div class=\"arrow\"></div><div class=\"popover-inner\"><div class=\"popover-content\"><p></p></div></div></div>"
      });
      _popover.data("popover").options.content = value.message;
      return $(value.element).popover("show");
    });
  },


Form highlighting

debug: true,
errorClass:'error help-inline',
validClass:'success',
errorElement:'span',
highlight: function (element, errorClass, validClass) { 
$(element).parents("div.control-group").addClass(errorClass).removeClass(validClass); 

}, 
unhighlight: function (element, errorClass, validClass) { 
	  $(element).parents(".error").removeClass(errorClass).addClass(validClass); 
},
