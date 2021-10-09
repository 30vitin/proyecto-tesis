
(function ($) {
    "use strict";


    /*==================================================================
    [ Focus input ]*/
    $('.input100').each(function(){
        $(this).on('blur', function(){
            if($(this).val().trim() != "") {
                $(this).addClass('has-val');
            }
            else {
                $(this).removeClass('has-val');
            }
        })
    })


  $('.bnt-login').on('click', function(event) {
    event.preventDefault();

    var input1 = $("#username");
    var input2 = $("#password");

      if(validate(input1)!=false && validate(input2)!=false){
            $("#ht-preloader").css("display", "block");
         var parametros = {
                  "a":"LOGIN",
                  "username":$("#username").val(),
                  "password":$("#password").val()
            };


           $.ajax({

                 data:  parametros, //datos que se envian a traves de ajax
                 url: "./?action=admin",
                 type: "POST",
                 dataType: "JSON",
                 cache: false,
                 success:function(data){
                      console.log(data)


                    if(data.type=='success'){
                            location.href=data.url;

                      }else{

                           $("#ht-preloader").css("display", "none");
                           $('.error-show').html(data.mens);

                      }


                    },
                  error: function(XMLHttpRequest, textStatus, errorThrown) {
                      $("#ht-preloader").css("display", "none");
                      console.log(textStatus)
                      console.log("Status: "+textStatus+" Error: "+XMLHttpRequest.responseText);

                }

                });

      }else{
          $('.error-show').html('Debe Completar ambos campos!');
      }

  });
    /*==================================================================
    [ Validate ]*/




    function validate (input) {
        if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
            if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                $('.error-show').html('Inserte un email valido!');
                return false;
            }
        }
        else {
            if($(input).val().trim() == ''){
                return false;
            }
        }
    }

    function showValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }

    /*==================================================================
    [ Show pass ]*/


})(jQuery);
