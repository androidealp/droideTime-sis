jQuery(function($){

 $('[data-toggle="tooltip"]').tooltip();

 $getleme = $('[data-switchdisable]');


 objectDip = {
    content:'',
    exec:function(){

      if(this.content == false)
      {
        $('[data-ajaxprocess]').attr('disabled',true);  
      }
      
    }
  };


 


 var Selet2_Events = function(getselector = '[data-qualquer]')
 {

    break_obj = getselector.split('data-');
    break_obj = break_obj[1].split(']');
    //var $eventSelect = $(break_obj[1]);

    var $eventSelect = $(getselector);

    var getUrl = $eventSelect.data(break_obj[0]);
    //console.log(break_obj);
    var seletor = $eventSelect.data('content');

    $eventSelect.on('select2:select',function(e){
      var getData = e.params.data;

      $.ajax({
        url:getUrl,
        data:{"text":getData.text,"value":getData.id},
        method:'post',
        datatype:'json',
        beforeSend:function()
        {
          $(seletor).empty();
          appyLoad(seletor);
          //console.log('sem load no momento');
        },
        success:function(data)
        {
          $(seletor).html(data);
        },
        error:function(request, status, error)
        {
           $(seletor).html('<div class="alert alert-danger">'+request.responseText+'</div>');
        }


      });

    });

 }


 function _sendAjax(StopExec, settings = {}, myObject)
  {
    toastr.options.onHidden = function()
    {
      console.log('sem load');
    }

    if(settings.step ==1)
    {
      settings.data.push({name: 'step', value: settings.step});  
    }else{
        $.each(settings.data, function(index, item) {
          if (item.name == 'step') {
              settings.data[index].value = settings.step;      
          }
      });
    }
    

    if(StopExec == 0){

      $.ajax({
        url:settings.url,
        data:settings.data,
        method:'POST',
        dataType:'JSON',
        timeout:settings.timeout,
        beforeSend:function(){

        },
        error:function(jqXHR, textStatus, errorThrown){
          if(textStatus == 'timeout'){
              myObject.html('<p>O tempo de execução expirou:</p>');
          }

            if(errorThrown != "Found")
            {
             // toastr['error']('Ocorreu um erro no processo de salvar, verifique no log');  
              setTimeout(function(){
                $("#ajax-process").fadeOut('slow',function(){
                    $(this).remove();
                });
              },1000);
            }

            

          
        },
        success:function(response){
          if(typeof response == 'object'){
            $("#ajax-process").html(response.processBar);
            settings.step = response.step;
            //settings.data = response.data;

            if(typeof response.error != 'undefined' && response.error != false)
            {
              toastr['error'](response.error);
              $.each(response.data_error,function(i,e){
                var mod = i;
                $.each(e,function(index,erro){
                   $('body').find(".field-"+mod+"-"+index).addClass('has-error');
                   $('body').find(".field-"+mod+"-"+index+" .help-block-error").text(erro);
                });

              });

              setTimeout(function(){
                $("#ajax-process").fadeOut('slow',function(){
                    $(this).remove();
                });
              },800);

            }else{
               _sendAjax(response.StopExec,settings,myObject);  
            }

            

          }// detecto o tipo
        }
      });

    }else{
      setTimeout(function(){
        $("#ajax-process").fadeOut('slow',function(){
            $(this).remove();
        });
      },800);
      
    } // fim do if

  }

  $(document).on('change','[data-ajaxselect]',function(e){

      if(typeof $(this).attr('disabled') != 'undefined')
      {
          return ;
      }

      obj = $(this);
      geturl = obj.data('ajaxselect');
      content = $(obj.data('content'));
      botaoenable = $(obj.data('btnenable'));

      $.ajax({
        url:geturl,
        data:{selected:obj.val()},
        method:'POST',
        dataType:'JSON',
        beforeSend:function(){
            appyLoad(content);
        },
        success:function(response){
          content.html(response.conteudo);
            botaoenable.attr('disabled',response.enable);
            if(typeof window.posajax == 'function')
            {
              window.posajax();
              
              window.posajax = null;
             
            }
        }
      });

  });

  $(document).on('click','[data-seletor]',function(e){
    e.preventDefault();
    btn = $(this);

    seletor = btn.data('seletor');



     var node = $(seletor)[0];

     if ( document.selection ) {
            var range = document.body.createTextRange();
            range.moveToElementText( node  );
            range.select();
        } else if ( window.getSelection ) {
            var range = document.createRange();
            range.selectNodeContents( node );
            window.getSelection().removeAllRanges();
            window.getSelection().addRange( range );
        }

  });


 $(document).on('click','[data-ajaxprocess]',function(e){
    e.preventDefault();

    if(typeof $(this).attr('disabled') != 'undefined')
    {
        return ;
    }

    var bt = $(this);
    var geturl = bt.prop('href');

    formdata = $(bt.data('ajaxprocess'));

    serialize = formdata.serializeArray();


    var options = {
            message: bt.data('message'),
            title: bt.attr('title'),
            size: eModal.size.md,
        };


        eModal.confirm(options)
          .then(function(){
              $('html body').append('<div class="panel-body load-pg-next4" style="display:block;" id="ajax-process"><div class="proccess"><p class="info-proccess"><i class="fa fa-refresh fa-spin fa-fw"></i> 0% - Iniciando o Processo de autorização....</p><div class="progress"><div class="progress-bar progress-bar-warning" data-transitiongoal="0" aria-valuenow="0" style="width: 0%;"></div></div></div></div>');

              settings = {
                url:geturl,
                data:serialize,
                step:1,
                //timeout:4500,
              };

              _sendAjax(0,settings, bt);


          }, function(){
            return;
          });

 });




if ($(".nice_check")[0]) {

  var list_switchery = [];

        var elems = Array.prototype.slice.call(document.querySelectorAll('.nice_check'));
        elems.forEach(function (html) {

            check = $(html);

            options = {color: '#26B99A'};
            checkoptions = check.data('action');

            if(typeof checkoptions != 'undefined')
            {
              //options.push(check.data('actions'));
              options = $.extend(options, checkoptions);
            }

            var switchery = new Switchery(html, options);

            if(typeof checkoptions != 'undefined' && typeof checkoptions.disablefunction != 'undefined')
            {
              switchery.disable();
            }

            if(typeof checkoptions != 'undefined' && typeof checkoptions.enablefunction != 'undefined')
            {
              switchery.enable();
            }

            if(typeof check.data('switchdisable') == 'undefined')
            {
              list_switchery.push(switchery);
            }


        });
    }


 $.each($getleme, function(i,e){

  bt = $(this);

      e.onclick = function()
       {
        if(e.checked)
        {
          $.each(list_switchery,function(index, val){

            val.disable();

          });

        }else{

          $.each(list_switchery,function(index, val){
            val.enable();

          });

        }
    }
 });



  var endereco = {};
  $(document).on('keydown','[data-endereco]',function(e){
    var code = e.keyCode || e.which;
    element = $(this);

    if(typeof $(this).attr('disabled') != 'undefined')
    {
        return ;
    }

    if(typeof endereco.viewmsn == 'undefined')
    {
      element.parent()
      .prepend('<p class="text-warning"><strong>Coloque o CEP ex: <code>05833210</code> e aperte TAB para o sistema buscar o endereço completo</strong></p>')
      .find('p.text-warning').delay(3000).hide('slow');


      endereco.viewmsn = 'informado';
    }

    valor = element.val();
    if(code == 9 &&  (valor.length == 8 || valor.length == 9) )
    {
      element.attr('disabled','disabled');
      $.get('https://viacep.com.br/ws/'+element.val()+'/json/',function(data){

        if(typeof data.logradouro != 'undefined')
        {
          element.val(data.logradouro+', 0, Bairro: '+data.bairro+', Cidade: '+data.localidade+' - '+data.uf+' CEP: '+data.cep);
        }

        element.attr('disabled',false);

      });

    }// fim dp if

  });

invoqueForm = function($list){

  if(typeof $list.endereco != 'undefined')
  {


    $(document).on('keydown','[data-endereco]',function(e){
      var code = e.keyCode || e.which;
      element = $(this);

        if(typeof $(this).attr('disabled') != 'undefined')
      {
          return ;
      }

      if(typeof endereco.viewmsn == 'undefined')
      {
        element.parent()
        .prepend('<p class="text-warning"><strong>Coloque o CEP ex: <code>05833210</code> e aperte TAB para o sistema buscar o endereço completo</strong></p>')
        .find('p.text-warning').delay(3000).hide('slow');


        endereco.viewmsn = 'informado';
      }

      valor = element.val();
      if(code == 9 &&  (valor.length == 8 || valor.length == 9) )
      {
        element.attr('disabled','disabled');
        $.get('https://viacep.com.br/ws/'+element.val()+'/json/',function(data){

          if(typeof data.logradouro != 'undefined')
          {
            element.val(data.logradouro+', 0, Bairro: '+data.bairro+', Cidade: '+data.localidade+' - '+data.uf+' CEP: '+data.cep);
          }

          element.attr('disabled',false);

        }).fail(function(){
             element.attr('disabled',false);
            $.each(fields, function(i, e){
              $(e).attr('disabled',false);

            });

             toastr.options.onHidden = function()
              {
                console.log('sem load');
              }

            toastr['error']('CEP Inválido, verifique corretamente');

          });;

      }// fim dp if

    });
  }

  if(typeof $list.select2 != 'undefined')
  {

    function format(state) {

          Search = ">>>";

          if (!state.id) return state.text; // optgroup

          if(state.text.indexOf(Search) !== -1)
          {
            state.text = state.text.replace(">>>", "");

            var $state = $(
              '<span><strong><i class="fa fa-arrow-circle-o-right icon-select"></i> </strong>' + state.text + '</span>'
            );

          }else{
            var $state = $(
              '<span>' + state.text + '</span>'
            );
          }

          return $state;
      }

    $('select').select2({
        formatResult: format,
        formatSelection: format,
        escapeMarkup: function(m) { return m; }
      });

    $('select.notselect2').select2('destroy');


  }


  if(typeof $list.select_effect != 'undefined')
  {
    Selet2_Events($list.select_effect);
  }

  if(typeof $list.truefalse != 'undefined')
  {

     if ($(".nice_check")[0]) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.nice_check'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html, {
                color: '#26B99A'
            });
        });
    }

  }


  if(typeof $list.truefalsemodal != 'undefined')
  {

     if ($(".nice_check_modal")[0]) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.nice_check_modal'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html, {
                color: '#26B99A'
            });
        });
    }

  }

  if(typeof $list.truefalseajax != 'undefined')
  {

     if ($(".nice_check_ajax")[0]) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.nice_check_ajax'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html, {
                color: '#26B99A'
            });
        });
    }

  }

   if(typeof $list.upfield != 'undefined')
  {

     $(document).on('click','[data-ajaxupfield]',function(e){
        e.preventDefault();

          if(typeof $(this).attr('disabled') != 'undefined')
          {
              return ;
          }

        bt = $(this);
        geturl = bt.data('ajaxupfield');
        $.get(geturl,function(datajson){

              if(typeof datajson.fields != 'undefined')
              {
                $.each(datajson.fields, function(i,v){
                  $(v.id).val(v.val);
                });
              }else{

                if(typeof datajson.msg == 'undefined')
                {
                  $(datajson.id).val(datajson.val);
                }else{

                  toastr.options.onHidden = function()
                  {
                    console.log('sem load');
                  }

                  toastr[datajson.type](datajson.msg);
                }

              }

          });

     });

  }


  if ( typeof $list.buscacep != 'undefined') {

    $(document).on('focusout','[data-droidecep]',function(e){
        e.preventDefault();

          if(typeof $(this).attr('disabled') != 'undefined')
          {
              return ;
          }

        fieldcep = $(this);
        data = fieldcep.data('droidecep');

        fields = data.fieldssearch;

        if(typeof fieldcep != 'undefined' && fieldcep.val().length == 9)
        {
          fieldtratada = fieldcep.val().replace('-','');
          fieldcep.attr('disabled',true);
          $.each(fields, function(i, e){

            $(e).attr('disabled',true);

          });


          $.get('https://viacep.com.br/ws/'+fieldtratada+'/json/',function(datajson){
            fieldcep.attr('disabled',false);
            objectmark = {};
            $.each(fields, function(i, e){
              objectmark[e] = i;
              $(e).attr('disabled',false);

            });

            $.each(objectmark, function(i, e){
              if(typeof datajson[e] != 'undefined' ){
                 
                  if(e == 'uf')
                    {

                      $(i).find('option:contains('+datajson[e]+')').attr('selected',true);
                    }else{
                      $(i).val(datajson[e]);  
                    }

              }
            });

          }).fail(function(){
            fieldcep.attr('disabled',false);
            $.each(fields, function(i, e){
              $(e).attr('disabled',false);

            });

             toastr.options.onHidden = function()
              {
                console.log('sem load');
              }

            toastr['error']('CEP Inválido, verifique corretamente');

          });

        }

     });

  }

  $('textarea').keydown(function(e){
    if(e.keyCode === 9) {
      var $this = $(this);
      var value = $this.val();

      if(value == 'lorem')
      {
        $this.val('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
      }

    }
  });
};


invoqueForm({
  'select_effect':'[data-selectpage]',
  'select2':1,
  //'truefalse':1,
  'buscacep':1,
  'upfield':1,
});



  toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": false,
  "progressBar": true,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "1000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut",
  "onHidden":function()
  {
    location.reload();
  }
}


/**
    object = é o seletor do campo exemplo #teste
    sethtml = quando vazio retorna o padrão caso contrário montar o html próprio para isso
    theme = padrõa white, é a classe que aplica a cor do thema pode ser black também
  **/

  appyLoad = function(object, sethtml = '', theme='white')
  {
    if(!sethtml){
      html = '<div data-boxload="'+object+'" class="box-load load-'+theme+'"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></div>';
    }else{
      html = sethtml;
    }

    $(object).append(html);

  };

  appyRemoveLoad = function(object)
  {
    $('[data-boxload="'+object+'"]').remove();
  }
  //gera load para página
  objectLoad = function(listUrl = [])
  {
    $.each(listUrl,function(i, item){
        $.ajax({
          url:item.url,
          data:item.data,
          beforeSend(){
            appyLoad(item.selector);
          },
          success:function(data)
          {

            $(item.selector).html(data);
            //appyRemoveLoad(item.selector);
          }

        });
    });
  };


  function execAjax(boxid, getUrl,form=0, before = 0, getSuccess=0)
  {
    getData = "";
    if(typeof form == 'object'){
      getData = form.serializeArray();
    }

    if(!before){
      before = function()
      {

      }
    }

    if(!getSuccess){
      getSuccess = function(data)
      {
        boxid.appendTo(data);
      }
    }

    $.ajax({
      url:getUrl,
      data:getData,
      beforeSend:before,
      success:getSuccess
    });
  }


  function ajaxDel(bt,getUrl)
  {

    $.ajax({
        url:getUrl,
        data:{},
        datatype:'json',
        method:'post',
        beforeSend:function(){
          bt.html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
        },
        success:function(data){

          if(data.type == 'success'){
            toastr[data.type](data.msg);

          }else{

            toastr.options.onHidden = function()
            {
              console.log('sem load');
            }

            toastr[data.type](data.msg);
          }

        }

      });

  }




  function aplicarAjaxSingle(bt,getUrl, serializado,IconTrue,IconFalse )
  {
    $.ajax({
        url:getUrl,
        data:serializado,
        datatype:'json',
        method:'post',
        beforeSend:function(){
          bt.html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
        },
        success:function(data){

           if(typeof data.loadpg == 'undefined')
           {

              toastr.options.onHidden = function()
              {
                console.log('sem load');
              }

           }else{

            toastr.options.onHidden = function()
              {
                location.reload();
              }

           }          

          if(data.type != 'success'){
            toastr.options.timeOut= 3000;
            toastr[data.type](data.msg);
          }else{
            if(typeof data.msg != 'undefined')
            {

              toastr[data.type](data.msg);
            }

          }


          if(data.bttruefalse){
            bt.html(IconTrue);
          }else{
              bt.html(IconFalse);
          }

        }

      });
  }

  

  $(document).on('click','[data-ajaxmarkitem]',function(e){

    e.preventDefault();
    
     if(typeof $(this).attr('disabled') != 'undefined')
     {
         return ;
     } 

    
    var bt = $(this);
    var item_id = bt.data('ajaxmarkitem');
    var field = bt.data('field');
    var content = bt.data('content');
    var geturl = bt.attr('href');

    $(field).val(item_id);
    // object, sethtml = '', theme='white'
    appyLoad('itemajax');

    $.get(geturl, function(data){
        $(field).val(item_id);
        $(content).html(data);
        appyRemoveLoad('itemajax');
    });

  });


  $(document).on('click','[data-delajax]',function(e){
    e.preventDefault();
    var bt = $(this);
    var url = bt.data('delajax');
    var confirm = bt.prop('title');
    if(typeof confirm == 'undefined')
    {
      
      confirm = bt.prop('data-original-title');

    }

    var options = {
            message: confirm,
            title: 'Cuidado!',
        };


    eModal.confirm(options)
          .then(function(){
            ajaxDel(bt,url);
          }, function(){
            return;
          });



  });




  $(document).on('click','[data-ajaxcopy]',function(e){
    e.preventDefault();

         if(typeof $(this).attr('disabled') != 'undefined')
          {
              return ;
          } 
    var bt = $(this);
    getUrl = bt.data('ajaxcopy');
    confirm = bt.data('confirmacao');

    appyAjax =function(getUrl)
    {
      $.get(getUrl,function(data){

        if(data.type != 'success'){

          toastr.options.onHidden = function()
          {
            console.log('sem load');
          }

          toastr[data.type](data.msg);
        }else{


          toastr[data.type](data.msg);

        }

      });
    };

    if(typeof confirm == 'string')
    {
      var options = {
              message: confirm,
              title: 'Cuidado!',
          };


      eModal.confirm(options)
            .then(function(){
                appyAjax(getUrl);
            }, function(){
              return;
            });

    }else{
      appyAjax(getUrl);
    }


  });



  $(document).on('click','[data-btajaxsingle]',function(e){
    e.preventDefault();


           if(typeof $(this).attr('disabled') != 'undefined')
     {
         return ;
     } 

    var bt = $(this);
    getUrl = bt.data('btajaxsingle');
    IconTrue =  bt.data('icontrue');
    IconFalse  =  bt.data('iconfalse');
    getdata = $(bt.data('serialize'));
    confirm = bt.data('comfirm');
    var serializado = {};
    if(typeof getdata == 'object')
    {
        serializado = getdata.serializeArray();
    }
    if(typeof confirm == 'string')
    {
      var options = {
              message: confirm,
              title: 'Cuidado!',
          };


      eModal.confirm(options)
            .then(function(){
              aplicarAjaxSingle(bt,getUrl, serializado,IconTrue,IconFalse );
            }, function(){
              return;
            });

    }else{
      aplicarAjaxSingle(bt,getUrl, serializado,IconTrue,IconFalse );
    }


  });
  // fim btajassingle


  $(document).on('click','[data-ajaxtratarinput]',function(e){

  e.preventDefault();

         if(typeof $(this).attr('disabled') != 'undefined')
        {
            return ;
        } 

  var bt = $(this);
  resgateicontext = bt.html();
  
  datainput = $(bt.data('input'));
  inputupdate = bt.data('inputupdate');
  contentview = $(bt.data('contentview'));

  dataserialize = {item:datainput.val()};
 
 if (datainput.val() == "") {
    toastr['error']('Consulta não pode ser vazio');
 }else{

    urlto = bt.data('ajaxtratarinput');

    $.ajax({
            url:urlto,
            datatype:'json',
            method:'post',
            data:dataserialize,
            beforeSend:function()
            {
              bt.html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
            },
            success:function(data){

              if(typeof data != 'undefined')
              {

                if(typeof data.reload == 'undefined')
                {
                  toastr.options.onHidden = function()
                  {
                    console.log('sem load');
                  }  
                }else{

                   toastr.options.onHidden = function()
                  {
                    location.reload();
                  }  
                }
                

                bt.html(resgateicontext);

                $.each(inputupdate,function(i,e){
                    $.each(data.inputval,function(index, val){
                      if(i == index)
                      {
                        $(e).val(val);
                      }

                    });
                });

                //inputupdate.val(data.inputval);

                if(typeof data.dataview == 'object')
                {
                  $html = "<div class='alert alert-info'>";
                  $html += "<p><strong>Dados retornados da consulta:</strong></p>";
                  $.each(data.dataview, function(i,e){
                    $html += "<strong>"+i+"</strong>:"+e+" <br />";
                  }); 

                   $html += "</div>";

                  contentview.html($html);
                }else{
                  contentview.html("<div class='alert alert-danger'>Nenhum registro encontrado</div>");  
                  
                  $.each(inputupdate,function(i,e){
                    $.each(data.inputval,function(index, val){
                      if(i == index)
                      {
                        $(e).val(e);
                      }

                    });
                });

                }

                contentview.show('slow');

                toastr[data.type](data.msg);

              }else{
                console.log('erro encontrado');
                console.log(data);
              }
            }
          });
 }



  

  
});

  $(document).on('click','[data-ajaxsave]',function(e){
        e.preventDefault();

         if(typeof $(this).attr('disabled') != 'undefined')
        {
            return ;
        } 

        var button = $(this);
        var To_url = button.data('ajaxsave');
        var resgatehtmlbt = button.html();
        var content = $(button.data('content'));
        serialize = content.find('input,select,textarea').serializeArray();

      var commandeditor = [];
      
      $.each(content.find('[data-ckecommand]'),function(index,value){
        commandeditor.push(eval($(this).data("ckecommand")));
      });

      serialize.push({ name: "editor", value: commandeditor });


        $.ajax({
          url:To_url,
          datatype:'json',
          method:'post',
          data:serialize,
          beforeSend:function()
          {
            $.each(content.find('input'),function(i,v){
                $(this).attr('disabled',true);
            });
            button.attr('disabled',true);

            button.html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw" style="color:#fff;"></i>');
          },
          success:function(data){

            if(typeof data != 'undefined')
            {

              if(typeof data.reload == 'undefined')
              {
                toastr.options.onHidden = function()
                {
                  console.log('sem load');
                }  
              }else{

                 toastr.options.onHidden = function()
                {
                  location.reload();
                }  
              }
              

              if(typeof data.conteudo != 'undefined')
              {
                toastr[data.type](data.msg);
                content.html(data.conteudo);

                return;
              }

              if(data.type != 'success'){
                toastr.options.timeOut= 2000;

                $.each(content.find('input'),function(i,v){
                    $(this).attr('disabled',false);
                });

                button.html(resgatehtmlbt);
                button.attr('disabled',false);

              }else{
                if(typeof data.reload != 'undefined')
                {
                  toastr.options.onHidden = function()
                  {
                    location.reload();
                  }
                    
                }else{
                  

                  $.each(content.find('input'),function(i,v){
                      $(this).attr('disabled',false);
                  });
                  button.html(resgatehtmlbt);
                  button.attr('disabled',false);

                }


              }

              toastr[data.type](data.msg);

            }else{
              console.log('erro encontrado');
              console.log(data);
            }
          }
        })

  });

  $(document).on('click','[data-openimg]',function(e){

       if(typeof $(this).attr('disabled') != 'undefined')
        {
            return ;
        } 

    var src = $(this).data('openimg');
    $('#imgload').attr('src', src);

    $('#showImageModal').modal('show',function(e){
      $('#openimg').hide('slow',function(e){
        $('#imgload').show();
      });
    });

  });


  $(document).on('click','[data-ajaxrender]',function(e){
    e.preventDefault();

       if(typeof $(this).attr('disabled') != 'undefined')
        {
            return ;
        } 

    var button = $(this);
    var url = button.data('ajaxrender');
    var content = $(button.data('content'));
    resgateicontext = button.html();
    button.html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');

    $.get(url,function(data){
      content.html(data);
      button.html(resgateicontext);
    });

  });





  // responsavel por ordenar elementos
  $(document).on('change','[data-orderchange]',function(e){

       if(typeof $(this).attr('disabled') != 'undefined')
        {
            return ;
        } 

        $seletor = $(this);
        $item_id = $seletor.data('getitem');
        $valor = $seletor.val();
        $url = $seletor.data('orderchange');

        $first = $(this).children('option').first().val();
        $last = $(this).children('option').last().val();

        $.ajax({
          url:$url,
          method:'post',
          dataType:'json',
          data:{"select_id":$valor,"item_id":$item_id,"first":$first,"last":$last},
          beforeSend:function(){
            $seletor.attr('disable',true);
          },
          success:function(data)
          {
            if(data.status != 0)
            {
              $seletor.remove();

              //console.log(data.status);
              location.reload();
            }

            //console.log(data.status);

          }

        });

      });


$(document).on('click','[data-clone]',function(e){
  e.preventDefault();


     if(typeof $(this).attr('disabled') != 'undefined')
        {
            return ;
        } 

  num = 0;
  var bt = $(this);
  var element = $(bt.data('clone'));
  var getClone = element.clone();
  var content = $(bt.attr('href'));
  var cacheidclone = getClone.attr('id');
  var countelementsClone = content.find(getClone.get(0).tagName).length;
  var modelname = bt.data('model');

  getClone.prop('id',cacheidclone+ countelementsClone);
  getClone.prop('name', modelname+"["+countelementsClone+"]");
  getClone.addClass('itemclone');

  $('<div class="form-group input-group">'+getClone[0].outerHTML+'<a href="'+bt.attr('href')+'" class="input-group-addon" data-cloneremove=".input-group"><i class="fa fa-minus text-danger"></i></a></div>').appendTo(content);

});


$(document).on('click','[data-multipleclone]',function(e){
    e.preventDefault(); 

       if(typeof $(this).attr('disabled') != 'undefined')
        {
            return ;
        } 

    var count = 1;
    var item_clone =  $('#panel-clone .item-clone');
    var bt = $(this);  

    if (item_clone.length > 1) {
      last_item = item_clone.last().find('input').attr('name');
        get_numeros = last_item.match(/[0-9]/);
        count = parseInt(get_numeros[0]) + 1;
        console.log(count);

    }
    

    $("#panel-clone .item-clone:last")
      .clone()
      .find('[data-clone-item]')
      .each(function(){
        this.value = '';           
            this.name = this.name.replace(/[0-9]/, count);
        })
    .end()
    .appendTo("#panel-clone")
});



$(document).on('click', '[data-remover]', function(e){
  e.preventDefault();

   if(typeof $(this).attr('disabled') != 'undefined')
        {
            return ;
        } 
    
    var bt = $(this);
    var content = bt.attr('href');
    var find = $(bt.data('remover'));
    var item_clone =  $('#panel-clone .item-clone');
    var total_item = item_clone.length - 1;

    bt.parent(find).remove();
    console.log(total_item);
    if ( total_item < 1) {      
        $("#panel-clone").find('input:text, input:password, input:file, select, textarea').val('');                     
    } else{        
      console.log(total_item);
    }   



});



$(document).on('click','[data-cloneremove]',function(e){
  e.preventDefault();

     if(typeof $(this).attr('disabled') != 'undefined')
        {
            return ;
        } 

  var bt = $(this);
  var content = bt.attr('href');
  var find = $(bt.data('cloneremove'));
  //var modelname = bt.data('model');
 bt.parent(find).remove();

 $(content+' .itemclone').each(function(index,val){
     var object = $(this);
     object.prop('id', object.attr('id').replace(/\d+/g,++index));
     object.prop('name', object.attr('name').replace(/\d+/g,index));
 });

});





});
