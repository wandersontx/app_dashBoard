<?php
class Dashboard{
	public $data_inicio;
	public $data_fim;
	public $numero_vendas;
	public $total_vendas;

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
		/*$st->bindValue(1,$dashboard->data_inicio);
		$st->bindValue(2,$dashboard->data_fim);*/
		$st->bindValue(1,$this->dashboard->__get('data_inicio'));
		$st->bindValue(2,$this->dashboard->__get('data_fim'));
		$st->execute();

		//retorna apenas o atributo numero_vendas da tb_vendas
		return $st->fetch(PDO::FETCH_OBJ)->total_vendas;
	}
}

$dashboard = new Dashboard();
$conexao = new Conexao();
$db = new Db($conexao, $dashboard);

$dashboard->__set('data_inicio','2018-10-01');
$dashboard->__set('data_fim','2018-10-31');


$dashboard->__set('numero_vendas',$db->getNumeroVendas());
$dashboard->__set('total_vendas',$db->getTotalVendas());

echo "<pre>";
print_r($dashboard);
echo "</pre>";



?>