<?php
interface InterfaceWebService{
	/**
	 * Insere um registro no banco de dados 
	 * @param Array $dados
	 * @return 1 ou mensagem de erro
	 */
	public function insert($dados);
	/**
	 * Atualiza um resgitro no banco de dados
	 * @param Array $dados
	 * @return 1 ou mensagem de erro
	 */
	public function update($dados);
	/**
	 * Deleta um registro do banco de dados
	 * @param Integer $primaryKey
	 * @return 1 ou mensagem de erro
	 */
	public function delete($primaryKey);
	/**
	 * Faz uma pesquisa no banco de dados
	 * @param Array $dados
	 * @return um Array de dados
	 */	
	//public function hasThisPk($pk);
	public function search($dados);
	/**
	 * Executa uma query customizada
	 * @param String $query
	 * @return um Array de dados
	 */
	public function customQuery($query);
	/**
	 * Executa a operaчуo passada
	 * @param String $op
	 * @param String $xml
	 * @return Array $dados
	 */
	public function executeThisOperation($op, $dados);
}