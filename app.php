<?php
class Dashboard{
	public $data_inicio;
	public $data_fim;
	public $numero_vendas;
	public $total_vendas;
	public $total_despesa;
	public $cliente_ativo;
	public $cliente_inativo;

	public function __get($atributo){
		return $this->$atributo;
	}

	public function __set($atributo, $valor){
		$this->$atributo = $valor;
		return $this;
	}

}

class Conexao{
	private $host = 'localhost';
	private $dbname = 'dashboard';
	private $user = 'root';
	private $pass = '';


	public function conectar(){
		try{
			$conexao = new PDO(
				"mysql:host=$this->host;dbname=$this->dbname",
				 "$this->user",
				 "$this->pass"
				);
			//Faz com que a instancia de conexão trabalhe com UTF-8
			$conexao->exec('set charset set utf8');
			return $conexao;

		}
		catch(PDOException $e){
			echo '<p>'.$e->getMessage().'</p>';
	
		}
	}

}



class Db{
	private $conexao;
	private $dashboard;

	public function __construct(Conexao $conexao, Dashboard $dashboard){
		$this->conexao = $conexao->conectar();
		$this->dashboard = $dashboard;
	}

	public function getNumeroVendas(){
		$query ='
		select
			 count(*) as numero_vendas
		from
			tb_vendas
		where
			data_venda between ? and ?';
		$st = $this->conexao->prepare($query);
		/*$st->bindValue(1,$dashboard->data_inicio);
		$st->bindValue(2,$dashboard->data_fim);*/
		$st->bindValue(1,$this->dashboard->__get('data_inicio'));
		$st->bindValue(2,$this->dashboard->__get('data_fim'));
		$st->execute();

		//retorna apenas o atributo numero_vendas da tb_vendas
		return $st->fetch(PDO::FETCH_OBJ)->numero_vendas;
	}

		public function getTotalVendas(){
		$query ='
		select
			 sum(total) as total_vendas
		from
			tb_vendas
		where
			data_venda between ? and ?';
		$st = $this->conexao->prepare($query);		
		$st->bindValue(1,$this->dashboard->__get('data_inicio'));
		$st->bindValue(2,$this->dashboard->__get('data_fim'));
		$st->execute();

		//total_vendas ->mesmo nome do alias
		return $st->fetch(PDO::FETCH_OBJ)->total_vendas;
	}

	public function getTotalDespesas(){
		$query = '
		select 
			sum(total) as total_despesa
		from 
			tb_despesas
		where
			data_despesa between ? and ?';
		$st = $this->conexao->prepare($query);
		$st->bindValue(1, $this->dashboard->__get('data_inicio'));
		$st->bindValue(2, $this->dashboard->__get('data_fim'));
		$st->execute();
		return $st->fetch(PDO::FETCH_OBJ)->total_despesa;
	}

	public function getClienteAtivo(){
		$query ='
		select
			count(cliente_ativo) as cliente_ativo
		from
			tb_clientes
		where
			cliente_ativo = 1';
		$st = $this->conexao->prepare($query);
		$st->execute();
		return $st->fetch(PDO::FETCH_OBJ)->cliente_ativo;
	}

	public function getClienteInativo(){
		$query ='
		select
			count(cliente_ativo) as cliente_inativo
		from
			tb_clientes
		where
			cliente_ativo = 0';
		$st = $this->conexao->prepare($query);
		$st->execute();
		return $st->fetch(PDO::FETCH_OBJ)->cliente_inativo;
	}
}


$dashboard = new Dashboard();
$conexao = new Conexao();
$db = new Db($conexao, $dashboard);



$competencia = explode('-',$_GET['competencia']);
$ano = $competencia[0];
$mes = $competencia[1];
$diasMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

$dashboard->__set('data_inicio',$ano.'-'.$mes.'-01');
$dashboard->__set('data_fim',$ano.'-'.$mes.'-'.$diasMes);


$dashboard->__set('numero_vendas',$db->getNumeroVendas());
$dashboard->__set('total_vendas',$db->getTotalVendas());
$dashboard->__set('total_despesa',$db->getTotalDespesas());
$dashboard->__set('cliente_ativo',$db->getClienteAtivo());
$dashboard->__set('cliente_inativo',$db->getClienteInativo());

echo json_encode($dashboard);



?>