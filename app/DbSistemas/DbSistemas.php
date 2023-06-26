<?php

namespace App\DbSistemas;

use PDOException;
use PDO;


class DbSistemas
{
    //Constantes de conexão com Db
    const SERVER = "";
    const DATABASE_NAME = "";
    const USER = "";
    const PASSWORD = "";

    //Variavel tabela
    private $tabela;


    /**
     * Conexão db
     * @var PDO
     */
    private $conexao;


    /**
     * Função define a tabela e instancia de conexão
     * 1 Parametro
     * @var string tabela
     */
    public function __construct($tabela = null)
    {
        $this->tabela = $tabela;
        $this->setConnection();
    }


    /**
     * Função que efetua a conexão com Db
     * OBS: CONEXÃO COM SQLSERVER Necessita de drives pdo_sqlsrv
     * Link donwload: https://docs.microsoft.com/pt-br/sql/connect/php/download-drivers-php-sql-server?view=sql-server-ver16
     * Link video instalação: https://www.youtube.com/watch?v=7spsRgc6AtE 
     */
    private function setConnection()
    {
        try {
            $this->conexao = new PDO('sqlsrv:Server=' . self::SERVER . '; Database=' . self::DATABASE_NAME,  self::USER, self::PASSWORD);
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // die("ERRO DE CONEXÃO {$e->getMessage()}");
            die("ERRO: 405");
        }
    }


    /**
     * Função para execultar a query no Db
     * 2 Parametro 
     * @var string query
     * @var array parametros
     */
    public function exculte($values, $parametros = [])
    {
        try {
            $excQuery = $this->conexao->prepare($values);
            $excQuery->execute($parametros);
            return $excQuery;
        } catch (PDOException $e) {
            die("ERRO NA QUERY {$e->getMessage()}");
            // die("ERRO: 405");
        }
    }


    /**
     * Função Insert no Db
     * 1 Parametro 
     * @var array values insert
     */
    public function insert($values)
    {
        $fields = array_keys($values);
        $binds =  array_pad([], count($fields), '?');

        $query = 'INSERT INTO ' . $this->tabela . '(' . implode(',', $fields) . ') VALUES(' . implode(',', $binds) . ')';

        // var_dump($query);
        // die();

        $this->exculte($query, array_values($values));

        return $this->conexao->lastInsertId();
    }


    /**
     * Função Select no Db
     * 1 Parametro 
     * @var string where
     */
    public function select($where = null)
    {
        $query = 'SELECT * FROM ' . $this->tabela . ' WHERE ' . $where;
        return $this->exculte($query);
    }


    /**
     * Função delete no Db
     * 1 Parametro 
     * @var string where
     */
    public function delete($where)
    {
        $query = 'DELETE FROM ' . $this->tabela . ' WHERE ' . $where;
        // var_dump($query);
        // die();
        return $this->exculte($query);
    }


    /**
     * Função Select no Db
     * 2 Parametro 
     * @var array values @var string where
     */
    public function update($where = null, $values)
    {
        $binds = array_keys($values);

        $query = 'UPDATE ' . $this->tabela . ' SET ' . implode('=?,', $binds) . '=? WHERE ' . $where;
        $this->exculte($query, array_values($values));
        // var_dump($query);
        // die();
        return true;
        // echo $query;
    }
}
