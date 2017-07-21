<?php

namespace app\database;

use \PDO;

class Bd extends Driver
{
    public function __construct()
    {
        $this->connect();
    }

    /**
     * @param string $tabela
     * @return object
     */
    public function getAll($tabela)
    {
        $dados = array();
        try {
            $sql = "SELECT * FROM $tabela";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            $dados = $stm->fetchAll(\PDO::FETCH_OBJ);
        } catch (PDOException $erro) {
            self::erro($erro);
        }
        return $dados;
    }

    /**
     * @param \PDOException $erro
     */
    public static function erro($erro)
    {
        echo "<script>alert('Erro: {$erro->getMessage()}')</script>";
    }

    /**
     * @param string $id
     * @param array $where array('campo' => id, 'busca' => 1)
     * @return object
     */
    public function get($tabela, $where)
    {
        $result = (object)array();
        try {
            $campo = $where['campo'];
            $busca = $where['busca'];
            $stmt = $this->pdo->prepare("SELECT * FROM " . $tabela . " WHERE " . $campo . " = ?");
            $stmt->bindParam(1, $busca, PDO::PARAM_INT);
            if ($stmt->execute()) {
                $result = $stmt->fetch(PDO::FETCH_OBJ);
            } else {
                throw new PDOException("<script>alert('Erro: Não foi possível executar a declaração sql')</script>");
            }
        } catch (PDOException $erro) {
            self::erro($erro);
        }
        return $result;
    }

    /**
     * @return int
     */
    public function countAll()
    {
        $find = $this->pdo->prepare('SELECT count(*) from vocabulario');
        $find->execute();
        return $find->fetchColumn();
    }

    /**
     * @param string $tabela
     * @param array $where array('campo' => id, 'busca' => 1)
     * @return boolean
     */
    public function delete($tabela, $where)
    {
        try {
            $sql = "DELETE FROM " . $tabela . " WHERE " . $where['campo'] . "=?";
            $stm = $this->pdo->prepare($sql);
            $stm->bindValue(1, $where['busca']);
            $stm->execute();
            return true;
        } catch (PDOException $erro) {
            echo self::erro($erro);
            return false;
        }
    }
}