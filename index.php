<?php
require_once 'token.php';
require_once 'class/nusoap/nusoap.php';
require_once 'class/xmlBuilder.class.php';
require_once 'class/mysql.class.php';
$servidor = new nusoap_server();
$servidor->configureWSDL('SETES.webservice', 'urn:SETES.webservice');
$servidor->wsdl->schemaTargetNamespace = 'urn:SETES.webservice';

//registra o servico
$servidor->register('servico',
					array('token'=>'xsd:string', 'area'=>'xsd:string' ,'op'=>'xsd:string','xml'=>'xsd:string'),
					array('return' => 'xsd:string'),
					'urn:SETES.webservice',
					'urn:SETES.webservice#servico',
					'rpc',
					'encoded',
					'WEBSERVICE'
					);
//cria o servico
function servico($token, $area, $op, $xml){
	validaToken($token);	//valida o token
	$execute = true;
	$area = strtoupper($area);	//joga tudo pra maiusculo
	$op = strtoupper($op);	//joga tudo pra maiusculo
	if(!empty($xml)){ //verifica se o xml nao esta vazio
		$dados = xmlBuilder::transformToArray($xml); //converte o $xml em vetor
	}else{
		//se tiver vazio dados tambem � vazio
		$dados = array();
	}
	switch($area){	//descobre a area e instancia o objeto
		default: 
			return 'area invalida';
		break;
		case 'ESTABELECIMENTO':
			require_once 'class/Estabelecimento.php';
			$obj = new Estabelecimento();
		break;
		case 'PROJETO':
			require_once 'class/Projeto.php';
			$obj = new Projeto();
		break;
		case 'SCRIPT':
			require_once 'class/Script.php';
			$obj = new Script();
		break;
		case 'SCRIPTX':
			require_once 'class/Scriptx.php';
			$obj = new Scriptx();
		break;
		case 'LICENCA':
			require_once 'class/Licenca.php';
			$obj = new Licenca();
		break;
		case 'INTERFACE':
			require_once 'class/Interface.php';
			$obj = new InterfaceS();
		break;
		case 'OPERACAOINTERFACE':
			require_once 'class/OperacaoInterface.php';
			$obj = new OperacaoInterface();
		break;
		case 'ENTIDADE':
			require_once 'class/Entidade.php';
			$obj = new Entidade();
		break;
		case 'CLIENTE':
			require_once 'class/Cliente.php';
			$obj = new Cliente();
		break;
		case 'ENDERECO':
			require_once 'class/Endereco.php';
			$obj = new Endereco();
		break;
		case 'FONE':
			require_once 'class/Fone.php';
			$obj = new Fone();
		break;
		case 'CATEGORIA':
			require_once 'class/Categoria.php';
			$obj = new Categoria();
		break;
		case 'PRODUTO':
			require_once 'class/Produto.php';
			$obj = new Produto();
		break;
		case 'MEDIDA':
			require_once 'class/Medida.php';
			$obj = new Medida();
		break;
		case 'EMBALAGEM':
			require_once 'class/Embalagem.php';
			$obj = new Embalagem();
		break;
		case 'MARCAPRODUTO':
			require_once 'class/Marca.php';
			$obj = new Marca();
		break;
		case 'BANNER':
			require_once 'class/Banner.php';
			$obj = new Banner();
		break;
		case 'IMAGEM':
			require_once 'class/Imagens.php';
			$obj = new Imagens(); //ESTA CLASSE NAO POSSUI AS OPERACOES PADROES!!!!!!
		break;
		case 'ESTOQUES':
			require_once 'class/Estoques.php';
			$obj = new Estoques();
		break;
		case 'ESTOQUE':
			require_once 'class/Estoque.php';
			$obj = new Estoque();
		break;
		case 'TABELAPRECO':
			require_once 'class/TabelaPreco.php';
			$obj = new TabelaPreco();
		break;
		case 'PRECO':
			require_once 'class/Preco.php';
			$obj = new Preco();
		break;
		case 'DADOSSITE':
			require_once 'class/DadosSite.php';
			$obj = new DadosSite();
		break;
		case 'INFORMACOES':
			require_once 'class/Informacoes.php';
			$obj = new Informacoes();
		break;
		case 'INSTITUCIONAL':
			require_once 'class/Institucional.php';
			$obj = new Institucional();
		break;
		case 'USUARIO':
			require_once 'class/Usuario.php';
			$obj = new Usuario();
		break;
		case 'PAGAMENTODIGITAL':
			require_once 'class/PagamentoDigital.php';
			$obj = new PagamentoDigital();
		break;
		case 'PAGAMENTODIGITALITENS':
			require_once 'class/PagamentoDigitalItens.php';
			$obj = new PagamentoDigitalItens();
		break;
		case 'PEDIDO':
			require_once 'class/Pedido.php';
			$obj =  new Pedido();
		break;
		case 'ITENSNFL':
			require_once 'class/ItensNFL.php';
			$obj =  new ItensNFL();
		break;
		case 'VISITANTESONLINE':
			require_once 'class/VisitantesOnline.php';
			$obj = new VisitantesOnline(false); // false no construtor para nao registrar esse acesso como visita
			$r['quantidade'] = $obj->getUsersOnline();
			$execute = false; //nao executa operacoes
		break;
		case 'PERGUNTAPRODUTO':
			require_once 'class/PerguntaProduto.php';
			$obj =  new PerguntaProduto();
		break;
		case 'VEICULO':
			require_once 'class/Veiculo.php';
			$obj =  new Veiculo();
		break;
		case 'TIPOVEICULO':
			require_once 'class/TipoVeiculo.php';
			$obj =  new TipoVeiculo(); 
		break;
		case 'CLIENTEINTERFACE':
			require_once 'class/CliInterface.php';
			$obj =  new CliInterface();
		break;
		case 'ITENSIFC':
			require_once 'class/ItensIFC.php';
			$obj =  new ItensIFC();
		break;
		case 'MARCAVEICULO':
			require_once 'class/MarcaVeiculo.php';
			$obj =  new MarcaVeiculo(); 
		break;
		case 'MODELOVEICULO':
			require_once 'class/ModeloVeiculo.php';
			$obj =  new ModeloVeiculo();
		break;
		case 'CORVEICULO':
			require_once 'class/CorVeiculo.php';
			$obj =  new CorVeiculo();
		break;
		case 'PROJETOCLIENTE':
			require_once 'class/ProjCliente.php';
			$obj =  new ProjetoCliente();
		break;
		case 'CHECKLIST':
			require_once 'class/CheckList.php';
			$obj =  new CheckList();
		break;
		case 'ORDEMSERVICO':
			require_once 'class/OrdemServico.php';
			$obj =  new OrdemServico();
		break;
		case 'COTACAO':
			require_once 'class/Cotacao.php';
			$obj =  new Cotacao();
		break;
		case 'ITENSCTC':
			require_once 'class/ItensCtc.php';
			$obj =  new ItensCtc();
		break;
		case 'EMAIL':
			require_once 'class/Email.php';
			$email = new Email($dados);
			if($email->enviaEmail()){
				$r = "E-mail enviado com sucesso!";
			}else{
				$r = "E-mail não enviado.";
			}
			$execute = false;
		break;
		case 'ALBUM':
			require_once 'class/Album.php';
			$obj =  new Album();
		break;
		case 'GALERIAFOTOS':
			require_once 'class/GaleriaFotos.php';
			$obj =  new GaleriaFotos();
		break;
		case 'BANCO':
			require_once 'class/Banco.php';
			$obj =  new Banco();
		break;
		case 'BOLETO':
			require_once 'class/Boleto.php';
			$obj =  new Boleto();
		break;
		case 'CONTABANCARIA':
			require_once 'class/ContaBancaria.php';
			$obj =  new ContaBancaria();
		break;
		case 'FINANCEIRO':
			require_once 'class/Financeiro.php';
			$obj =  new Financeiro();
		break;
		case 'FORMAPAGTO':
			require_once 'class/FormaPagto.php';
			$obj =  new FormaPagto();
		break;
		case 'MODALFRETE':
			require_once 'class/ModalFrete.php';
			$obj =  new ModalFrete();
		break;
		case 'NATUREZA':
			require_once 'class/Natureza.php';
			$obj =  new Natureza();
		break;
		case 'NOTAFISCAL':
			require_once 'class/NotaFiscal.php';
			$obj =  new NotaFiscal();
		break;
		case 'OBSNFE':
			require_once 'class/ObsNfe.php';
			$obj =  new ObsNfe();
		break;
		case 'BAIRRO':
			require_once 'class/Bairro.php';
			$obj =  new Bairro();
		break;
		case 'CEP':
			require_once 'class/Cep.php';
			$obj =  new Cep();
		break;
		case 'CIDADE':
			require_once 'class/Cidade.php';
			$obj =  new Cidade();
		break;
		case 'ESTADO':
			require_once 'class/Estado.php';
			$obj =  new Estado();
		break;
		case 'FRETE':
			require_once 'class/Frete.php';
			$obj =  new Frete();
		break;
		case 'FRETEDESTINO':
			require_once 'class/FreteDestino.php';
			$obj =  new FreteDestino();
		break;
		case 'FRETEPESO':
			require_once 'class/FretePeso.php';
			$obj =  new FretePeso();
		break;
		case 'FRETEDESTINOPESO':
			require_once 'class/FreteDestinoPeso.php';
			$obj =  new FreteDestinoPeso();
		break;
		case 'CUPOM':
			require_once 'class/Cupom.php';
			$obj =  new Cupom();
		break;
	}
	if($execute){
		$r = $obj->executeThisOperation($op, $dados); //contem o switch com as opera��es
	}	
	$xr = xmlBuilder::createFromArray($r, $area, 'dados'); //cria o xml 
	//return var_dump($r[0]["ENDERECO"]);
	return $xr;
}


/** 
 * cep.php 
 * RETORNA UM ENDERECO PELO CEP
 * CONTEM O SERVICO: cep(cep) 
 * @param String cep : somente uma string com o cep no formato 99999-999 ou 99999999
 * @return String xml : xml com os dados.
 */
include 'cep.php';
include 'frete.php';
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$servidor->service($HTTP_RAW_POST_DATA);