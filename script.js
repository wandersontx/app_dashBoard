$(document).ready(() => {
	
	$('#documentacao').on('click', ()=>{
		//Toda a logica por instancia o objeto XMLHttpRequest, realizar a requisição e controlar a resposta
		//esta encapusaldo dentro do jquery
		$('#pagina').load('documentacao.html')//load por padrão faz uma requisição GET
	})

	$('#logo').on('click', ()=>{
		$('body').load('index.html');
	})

	$('#suporte').on('click',()=>{
		$('#pagina').load('suporte.html')
	})


	$('#competencia').on('change', e => {
		$.ajax({
			type: 'GET',//requisição passando um GET
			url: 'app.php',//para o arquivo php.php
			data: 'competencia='+$(e.target).val(),///setando variaveis e valores
			dataType:'json',//tipo de retorno
			success: dados =>{//caso tenha sucesso, terá acesso aos dados				
				$('#numVendas').html(dados.numero_vendas)
				$('#totVendas').html(dados.total_vendas)
				$('#totDespesas').html(dados.total_despesa)
				$('#clienteAtivo').html(dados.cliente_ativo)
				$('#clienteInativo').html(dados.cliente_inativo)
				
			},
			error: erro =>{console.log(erro)}//caso de erro, exibirá uma mensagem de erro
		})
	})
})