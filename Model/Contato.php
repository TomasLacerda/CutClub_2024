<?php
class Contato
{
    // Atributos da classe
    private $id;
    private $nome;
    private $sobrenome;
    private $email;
    private $telefone;
    private $senha;
    private $barbeiro;

    // GET && SET
    public function __get($valor)
    {
        return $this->$valor;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }
}