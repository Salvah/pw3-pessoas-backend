<?php

require dirname(__FILE__) . "/../config.php";

class Pessoa {
    private $id;
    private $nome = "";
    private $sobrenome = "";
    private $dtnasc = "";

   function __toString(){
       return json_encode([
           "id" => $this->id,
           "nome" => $this->nome,
           "sobrenome" => $this->sobrenome,
           "dtnasc" => $this->dtnasc,
           "nomeCompleto" => "{$this->nome} {$this->sobrenome}"
       ]);
   }

    static function findByPk($id){
        global $_DATABASE;
        $db = new PDO("mysql:host={$_DATABASE['HOSTNAME']};dbname={$_DATABASE['DBNAME']}", $_DATABASE['USER'], $_DATABASE['PWD']);
        $consulta = $db->prepare("SELECT * FROM pessoas WHERE id=:id");
        $consulta->execute([':id' => $id]);
        $consulta->setFetchMode(PDO::FETCH_CLASS, 'Pessoa');
        return $consulta->fetch();
    }

    function setNome($v) {$this->nome = $v;}
    function setSobrenome($v){$this->sobrenome = $v;}
    function setDtNasc($v){$this->dtnasc = $v;}
    function getNome(){return $this->nome;}
    function getSobremome(){return $this->sobrenome;}
    function getDtNasc(){return $this->dtnasc;}

    function inserir(){
        global $_DATABASE;
        try {
            $db = new PDO("mysql:host={$_DATABASE['HOSTNAME']};dbname={$_DATABASE['DBNAME']}", $_DATABASE['USER'], $_DATABASE['PWD']);
            $consulta = $db->prepare("INSERT INTO pessoas(nome, sobrenome, dtnasc) VALUES(:nome,:sobrenome,:dtnasc)");
            $consulta->execute([
                ':nome' => $this->nome,
                ':sobrenome' => $this->sobrenome,
                ':dtnasc' => $this->dtnasc
            ]);
            $consulta = $db->prepare("SELECT id FROM pessoas ORDER BY id DESC LIMIT 1");
            $consulta->execute();
            $data = $consulta->fetch(PDO::FETCH_ASSOC);
            $this->id = $data['id'];

        }catch(PDOException $e){
            throw new Exception("Ocorreu um erro interno!");
            
        }
    }

    function alterar(){
        global $_DATABASE;
        try {
            $db = new PDO("mysql:host={$_DATABASE['HOSTNAME']};dbname={$_DATABASE['DBNAME']}", $_DATABASE['USER'], $_DATABASE['PWD']);
            $consulta = $db->prepare("UPDATE pessoas SET nome = :nome, sobrenome = :sobrenome, dtnasc = :dtnasc WHERE id= :id");
            $consulta->execute([
                ':id' => $this->id,
                ':nome' => $this->nome,
                ':sobrenome' => $this->sobrenome,
                ':dtnasc' => $this->dtnasc
            ]);
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }

    function remover(){
        global $_DATABASE;
        try {
            $db = new PDO("mysql:host={$_DATABASE['HOSTNAME']};dbname={$_DATABASE['DBNAME']}", $_DATABASE['USER'], $_DATABASE['PWD']);
            $consulta = $db->prepare("DELETE FROM pessoas WHERE id= :id");
            $consulta->execute([':id' => $this->id]);
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
}