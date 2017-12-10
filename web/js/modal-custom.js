(function ($, Elementos){

$(document).ready(function(){

	eModal.setEModalOptions({
    loadingHtml: '<div class="jumbotron text-center bg-transparent margin-none"><span class="fa fa-fw fa-4x icon-refresh-heart-fill faa-burst animated text-danger"></span><h4>Aguarde</h4></div>',
});

 Elementos ={};
 pajaxid='';


	$(document).on('click','[data-openimg]',function(){
		bt = $(this);
		url = bt.data('openimg');

		$('#openimg').hide('slow',function(){
				$('#imgload').show();
				$('#imgload').attr('src',url);
		});

	});

	function salvar(){

		if(Elementos != ''){
			form = $('#'+Elementos);
			var commandeditor = [];
			var data_save = form.serializeArray();

			$.each(form.find('[data-ckecommand]'),function(index,value){
				commandeditor.push(eval($(this).data("ckecommand")));
			});

			data_save.push({ name: "editor", value: commandeditor });

			$.ajax({
				url:form.attr('action'),
				//data:form.serialize(),
				data:data_save,
				datatype:'json',
				method:'post',
				beforeSend:function(){
					//form.find('input').attr('disabled');
					form.find('.contentform').fadeOut('slow',function(){
							form.append('<div id="loadajaxmodal" class="jumbotron text-center bg-transparent margin-none"><span class="fa fa-spinner fa-pulse fa-3x fa-fw text-primary"></span><h4>Aplicando</h4></div>');
					});


				},
				success:function(data){

					if(data.type == 'success'){

						toastr.options.timeOut= 1500;

						toastr.options.onHidden = function()
					  {
					    location.reload();
					  }

							toastr[data.type](data.msg);
						 //$.pjax.reload({container:"#"+pajaxid});
							eModal.close();
						//$.notify(data.msn,data.type);
					}else{
						toastr.options.timeOut= 2000;
					form.children('#loadajaxmodal').remove();

						form.find('.contentform').fadeIn('slow');

						toastr.options.onHidden = function()
					  {
					    console.log("erro:"+data.msg);
					  }

 						toastr[data.type](data.msg);
						// var body = $(".modal");
						// body.stop().animate({scrollTop:0}, '1000', 'swing', function() {
						//
						//    form.find('#erros').html("<div class='alert alert-danger'>"+data.msn.message+"</div>").fadeIn('slow');
						// })
					}

				},
				error:function(xhr, ajaxOptions, thrownError){
					console.log('xhr:'+xhr+' ajaxoptions:'+ajaxOptions+' thrownError:'+thrownError);
				}

			});
		}else{
			console.log('nao foi detectado o elemento');
		}

	}


	function deletar(url,dados,idcategoria){

		$.ajax({
				url:url,
				data:{'del-list':dados},
				datatype:'json',
				method:'post',
				beforeSend:function(){

				},
				success:function(data){

					if(data.type == 'success'){

						toastr.options.timeOut= 1500;
					}else{
						toastr.options.timeOut= 2000;
					}

					toastr.options.onHidden = function()
					{
						location.reload();
					}

					toastr[data.type](data.msg);

				}

			});
	}

  $(document).on('click','[data-btedturl]',function(e){
    e.preventDefault();
    Elementos 	= $(this).data('formid');
    sizemd 		= eModal.size.md;
		sizemodalbt = $(this).data('modalsize');
		pajaxid     = $(this).data('pajaxid');
    if(sizemodalbt == 'lg'){
			sizemd = eModal.size.lg;
		}else if(sizemodalbt == 'sm'){
			sizemd = eModal.size.sm;
		}else if(sizemodalbt == 'xl'){
			sizemd = eModal.size.xl;
		}

    var options = {
	        url: $(this).data('btedturl'),
	        title:$(this).prop('title'),
	        size: sizemd,
	        //subtitle: 'smaller text header',
	        buttons: [
	            {text: 'Editar', style: 'info',   close: false, click: salvar },
	            {text: 'Fechar', style: 'danger', close: true}
	        ],
	    };

      eModal.ajax(options).then(setTimeout(function(){ $('select').select2(); }, 1000));
  });



  $(document).on('click','[data-btalert]',function(e){
    e.preventDefault();
    mensagems = $(this).data('btalert');
    titulo    = $(this).prop('title');

    var options = {
        message: mensagems,
        title: titulo,
        useBin: true,
        buttons: [
            {text: 'Fechar', style: 'danger', close: true}
        ],
    };


    eModal.alert(options);

  });



	$(document).on('click','[data-btaddurl]',function(e){
		e.preventDefault();

		Elementos 	= $(this).data('formid');
		sizemd 		= eModal.size.md;
		sizemodalbt = $(this).data('modalsize');
		pajaxid     = $(this).data('pajaxid');

		buttons = $(this).data('buttons');
		buttons_text = [
			{text: 'Salvar', style: 'info',   close: false, click: salvar },
	    	{text: 'Fechar', style: 'danger', close: true}
		];
		if(typeof buttons == 'string')
		{
			if(buttons == 'fechar')
			{
				buttons_text = [
			    	{text: 'Fechar', style: 'danger', close: true}
				];		
			}else if(buttons == 'salvar'){
				buttons_text = [
			    	{text: 'Salvar', style: 'info',   close: false, click: salvar },
				];		
			}

		}

		if(sizemodalbt == 'lg'){
			sizemd = eModal.size.lg;
		}else if(sizemodalbt == 'sm'){
			sizemd = eModal.size.sm;
		}else if(sizemodalbt == 'xl'){
			sizemd = eModal.size.xl;
		}

		$title = $(this).prop('title');

		if(typeof $title == 'undefined' || $title=='')
		{
			$title = $(this).attr('data-original-title');

		}

		

		var options = {
	        url: $(this).data('btaddurl'),
	        title:$title,
	        size: sizemd,
	        //subtitle: 'smaller text header',
	        buttons: buttons_text,
	    };
		
		// eModal.ajax(options).then(setTimeout(function(){ $('select').select2(); }, 1000));
		//var url = $(this).data('btaddurl');
	
		eModal.ajax(options).then(function(el){
			el.attr('tabindex', '');
		});
	});
	

	$(document).on('click','[data-btdelurl]',function(e){
		e.preventDefault();
		var gridid 		= $(this).data('gridid');
		var elementos 	= $('#'+gridid).yiiGridView('getSelectedRows');
		var confirmtext	= $(this).data('btconfirm');
		var url			= $(this).data('btdelurl');
		var ajaxid      = $(this).data('pajaxid');

		sizemd 		= eModal.size.md;
		sizemodalbt = $(this).data('modalsize');

		if(sizemodalbt == 'lg'){
			sizemd = eModal.size.lg;
		}else if(sizemodalbt == 'sm'){
			sizemd = eModal.size.sm;
		}else if(sizemodalbt == 'xl'){
			sizemd = eModal.size.xl;
		}

		var options = {
		        message: confirmtext,
		        title: 'Cuidado!',
		        size: sizemd,
		    };


		eModal.confirm(options)
		      .then(function(){
		      	deletar(url,elementos,ajaxid);
		      }, function(){
		      	return;
		      });
	});

});

} (jQuery));
