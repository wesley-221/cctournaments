<?php
/**
 * Class Db
 */
class Db {
    private $db;

    /**
     * Réalise la connexion à la base de données
     * @param $dbhost
     * @param $dbname
     * @param $dbuser
     * @param $dbpswd
     */
    public function __construct($dbhost, $dbname, $dbuser, $dbpswd) {
        $this->db = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', $dbuser, $dbpswd);
    }
    /**
     * Execute une requête SQL
     * @param $request string La requête SQL
     * @param null $values array Des valeurs optionnels
     * @return PDOStatement
     */
    private function exec($request, $values = null){
        $req = $this->db->prepare($request);
        $req->execute($values);
        return $req;
    }

    /**
     * Définis le fetchMode
     * @param $fetchMode Le fetchMode
     */
    public function setFetchMode($fetchMode){
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $fetchMode);
    }

    /**
     * @param $request
     * @param array $values
     * @return bool
     */
    public function execute($request, $values = array()){
        $results = self::exec($request, $values);
        return ($results) ? true : false;
    }

    /**
     * @param $request
     * @param null $values
     * @param bool $all
     * @return array|mixed
     */
    public function fetch($request, $values = null, $all = false) {
        $results = self::exec($request, $values);
        return ($all) ? $results->fetchAll() : $results->fetch();
    }
}
