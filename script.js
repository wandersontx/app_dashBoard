$(document).ready(() => {
	
	$('#documentacao').on('click', ()=>{
		//Toda a logica por instancia o objeto XMLHttpRequest, realizar a requisição e controlar a resposta
		//esta encapusaldo dentro do jquery
		$('#pagina').load('documentacao.html')//load por padrão faz uma requisição GET
	})

	$('#suporte').on('click',()=>{
		$('#pagina').load('suporte.html')
	})
	
	/*
	--Metodos alternativos ao LOAD
	usando o metodo GET
	$('#suporte').on('click',()=>{
		$.get('suporte.html', data =>{
			$('#pagina').html(data)
		})
	})
	
	usando o metodo POST
	$('#suporte').on('click',()=>{
		$.post('suporte.html', data =>{
			$('#pagina').html(data)
		})
	})
	*/
})