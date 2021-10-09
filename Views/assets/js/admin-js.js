"use strict";


$(document).ready(function() {

$('.remove-image').on('click',function(event){

  $("#ht-preloader").css("display", "block");
  var id = this.id;
  event.preventDefault();


  var parametros = {
           "a":"DELETE-IMAGE-PRODUCT-SINGLE",
           "id":id
     };

  $.ajax({

     data: parametros,
     url: "./?action=admin",
     type: "POST",
     dataType: "JSON",
     success:function(data){
       console.log(data)
       if(data.type=='success'){
            location.reload();

         }else{
              $("#ht-preloader").css("display", "none");
              Swal.fire(data.mens)

           }
        //Swal.fire(data.mens)

  },
  error: function(XMLHttpRequest, textStatus, errorThrown) {
   console.log("Status: "+textStatus+" Error: "+XMLHttpRequest.responseText);

  }

  });

})

$('.btn-send').on('click', function(event) {
  event.preventDefault();

  if(documentValidate()){

       $("#ht-preloader").css("display", "block");
         $.ajax({

            url: "./?action=admin",
            type: "POST",
            data:$("#form1").serialize(),
            dataType: "JSON",
            success:function(data){

              $("#ht-preloader").css("display", "none");

               Swal.fire(data.mens)

         },
         error: function(XMLHttpRequest, textStatus, errorThrown) {
          console.log("Status: "+textStatus+" Error: "+XMLHttpRequest.responseText);

         }

         });


  }


});

$('.btn-send-order').on('click', function(event) {
  event.preventDefault();

    if($("#order_id").val()!==''){
         $("#ht-preloader").css("display", "block");
         var parametros = {
                  "a":"SEND-READY-ORDER",
                  "order_id":$("#order_id").val(),
                  "comment":$("#comentario").val()
            };
         $.ajax({

                 data:  parametros, //datos que se envian a traves de ajax
                 url: "./?action=admin",
                 type: "POST",
                 dataType: "JSON",
                  success:function(data){

                    console.log(data);

                    if(data.type=='success'){
                           location.href=data.url;

                      }else{
                           $("#ht-preloader").css("display", "none");
                           Swal.fire(data.mens)

                        }


                    },
                  error: function(XMLHttpRequest, textStatus, errorThrown) {
                  console.log("Status: "+textStatus+" Error: "+XMLHttpRequest.responseText);

                }

                });
    }

});

$('.prev-page').on('click', function(event) {
    var page=Number($("#page").val())-1;
     $("#page").val(page);
    $("#form-filter").submit();
});

$('.next-page').on('click', function(event) {
    var page=Number($("#page").val())+1;

     $("#page").val(page);

    $("#form-filter").submit();
});

$('.btn-add-talla').on('click', function(event) {
  event.preventDefault();

Swal.fire({
text: "Escriba la talla:",
input: 'text',
showCancelButton: true ,
confirmButtonColor: 'blue'
}).then((result) => {


if (result.value) {
     $("#ht-preloader").css("display", "block");
    var parametros = {
                  "a":"ADD-TALLA",
                  "talla":result.value,
                  "product_id":$("#product_id").val()
            };

    $.ajax({

                 data:  parametros, //datos que se envian a traves de ajax
                 url: "./?action=admin",
                 type: "POST",
                 dataType: "JSON",
                  success:function(data){

                    //console.log(data);

                    if(data.type=='success'){

                           location.reload();

                      }else{
                             $("#ht-preloader").css("display", "none");
                           Swal.fire(data.mens)

                        }


                    },
                  error: function(XMLHttpRequest, textStatus, errorThrown) {
                  console.log("Status: "+textStatus+" Error: "+XMLHttpRequest.responseText);

                }

                });

}});


});

$('.add-glosary-links').on('click', function(event) {
  event.preventDefault();

    var lines = Number($("#rowsGlosario").val())+1;

    $("#rowsGlosario").val(lines);
    Swal.fire({
      title: 'Complete Formulario',
      showCancelButton: true,

      html:'<input type="text" id="swal-input1" class="swal2-input" placeholder="Nombre"><span id="mens" style="color:red;font-size:15px;"></span>' +
           '<input type="text" id="swal-input2" class="swal2-input" placeholder="Link de video">',

      preConfirm: function () {
         return new Promise(function (resolve) {
           let todoListsFormatted = []
           if ($('#swal-input1').val() == '' || $('#swal-input2').val() == '') {
                          document.getElementById('mens').innerHTML="Los campos son obligatorios";
                          $(".swal2-confirm").prop('disabled', false);

              } else {

                  todoListsFormatted.push({
                     name: $('#swal-input1').val(),
                     link: $('#swal-input2').val()
                   })
                  resolve(todoListsFormatted);
            }
         })
       },
       onOpen: function () {
        $('#swal-input1').focus()
      }
    }).then((result) => {


          var myJSON = JSON.parse(JSON.stringify(result));
          $( ".gloasaryContent" ).append('<tr id="ln-'+lines+'">'+
                     '<td><input type="hidden" name="idGlosary[]" value="">'+
                     '<input type="hidden" name="namesGlosary[]" value="'+myJSON['value'][0].name+'" >'+myJSON['value'][0].name+'</td>'+
                    '<td><input type="hidden" name="linksGlosary[]" value="'+myJSON['value'][0].link+'" >'+myJSON['value'][0].link+'</td>'+
                    '<td><button type="button" class="btn btn-danger pull-right" onclick=deleteLine("ln-'+lines+'")><i class="material-icons">clear</i></button></td>'+
                    '</tr>' );


    });


});

$('.btn-add-libras').on('click', function(event) {
  event.preventDefault();

Swal.fire({
text: "Escriba la cantidad en libras:",
input: 'number',
showCancelButton: true ,
confirmButtonColor: 'blue'
}).then((result) => {


if (result.value) {
     $("#ht-preloader").css("display", "block");
    var parametros = {
                  "a":"ADD-LIBRAS",
                  "libras":result.value,
                  "product_id":$("#product_id").val()
            };

    $.ajax({

                 data:  parametros, //datos que se envian a traves de ajax
                 url: "./?action=admin",
                 type: "POST",
                 dataType: "JSON",
                  success:function(data){

                    //console.log(data);

                    if(data.type=='success'){

                           location.reload();

                      }else{
                             $("#ht-preloader").css("display", "none");
                           Swal.fire(data.mens)

                        }


                    },
                  error: function(XMLHttpRequest, textStatus, errorThrown) {
                  console.log("Status: "+textStatus+" Error: "+XMLHttpRequest.responseText);

                }

                });

}});


});

$('#typeproduct').on('change', function(event) {
  event.preventDefault();
  var value=$('#typeproduct').val();
  if(value!=""){

      var parametros = {
                  "a":"GET-CAT-TYPE",
                  "value":value
            };

         $.ajax({

                 data:  parametros, //datos que se envian a traves de ajax
                 url: "./?action=admin",
                 type: "POST",
                 dataType: "JSON",
                  success:function(data){

                     $('#category_createpr').html(data.html);


                    },
                  error: function(XMLHttpRequest, textStatus, errorThrown) {
                  console.log("Status: "+textStatus+" Error: "+XMLHttpRequest.responseText);

                }

                });
  }


});

$('.btn-update-product').on('click', function(event) {
  event.preventDefault();
  $('#btn-sect1-1').attr("disabled", true);

  //$( ".clear-loader" ).append('<div id="myProgress"><div id="myBar"></div></div>' );
    if(document.getElementById('myBar')){

      document.getElementById('myBar').style.display="block";

    }
  if(documentValidate()){

          $("#ht-preloader").css("display", "block");
         var form = $('#formprofile')[0];

	     var formData = new FormData(form);



         $.ajax({

                 url: "./?action=admin",
                 type: "POST",
                 processData: false,
			     contentType: false,
                 dataType: "JSON",
                 data: formData,
                  success:function(data){

                      if(data.type=='success'){


                           if(data.url!=''){
                               location.href=data.url;
                           }else{
                               location.reload();
                           }

                      }else{
                        $('#btn-sect1-1').attr("disabled", false);

                             $("#ht-preloader").css("display", "none");
                           Swal.fire(data.mens)

                        }

                    },
                  error: function(XMLHttpRequest, textStatus, errorThrown) {
                  console.log("Status: "+textStatus+" Error: "+XMLHttpRequest.responseText);

                },
                xhr: function() {
                   var xhr = new window.XMLHttpRequest();

                   xhr.upload.addEventListener("progress", function(evt) {
                     if (evt.lengthComputable) {
                       var percentComplete = evt.loaded / evt.total;
                       percentComplete = parseInt(percentComplete * 100);
                       console.log("Progreso: "+ percentComplete +"%")
                       //var elem = document.getElementById("myBar");
                      // elem.append=percentComplete +"%";

                       move(percentComplete)

                       if (percentComplete === 100) {

                       }

                     }
                   }, false);

                   return xhr;
                 },

                });


  }


});

$('.btn-modal-form').on('click', function(event) {
  event.preventDefault();

        var id = this.id;
          $("#ht-preloader").css("display", "block");



         $.ajax({


                 url: "./?action=admin",
                 type: "POST",
                 data:$("#form-"+id).serialize(),
                 dataType: "JSON",
                 success:function(data){
                    console.log(data)
                      if(data.type=='success'){


                           if(data.url!=''){
                               location.href=data.url;
                           }else{
                               location.reload();
                           }

                      }else{
                        $('#btn-sect1-1').attr("disabled", false);

                             $("#ht-preloader").css("display", "none");
                           Swal.fire(data.mens)

                        }

                    },
                  error: function(XMLHttpRequest, textStatus, errorThrown) {
                  console.log("Status: "+textStatus+" Error: "+XMLHttpRequest.responseText);

                },
                xhr: function() {
                   var xhr = new window.XMLHttpRequest();

                   xhr.upload.addEventListener("progress", function(evt) {
                     if (evt.lengthComputable) {
                       var percentComplete = evt.loaded / evt.total;
                       percentComplete = parseInt(percentComplete * 100);
                       console.log("Progreso: "+ percentComplete +"%")
                       //var elem = document.getElementById("myBar");
                      // elem.append=percentComplete +"%";


                       if (percentComplete === 100) {

                       }

                     }
                   }, false);

                   return xhr;
                 },

                });





});

function move(width) {
  console.log(width)

    var elem = document.getElementById("myBar");
    elem.innerHTML=width +"%";
    elem.style.width = width + "%";



}


function documentValidate(){

        var check=true;
     $.each($('.validate'), function(index, value) {

         //console.log($(value).val()+' '+this.id);


         if($(value).val()==''){
             $("."+this.id+'-error').html("This field is required!");
             check=false;
         }else{
             $("."+this.id+'-error').html("");
         }
         if($(value).attr("name")=='email'){

            if(!validarEmail($(value).val())){
               $("."+this.id+'-error').html("Insert a valid email!");
               check=false;
            }else{

            }
         }

    });
    return check;
}

});
