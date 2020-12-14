<?php

class lpdo{
    private $host = '';
    private $username = '';
    private $password = '';
    private $db = '';
    
    private $query = '';
    private $mysqli_query;
    private $result;
    private $bdd;

    public function __construct($host, $username, $password, $db)
    {
        $this->bdd = mysqli_connect($host, $username, $password, $db);
        echo 'Connexion avec succès...<br/ >';
    }
    public function __destruct()
    {
        print "<br/ >HARDJOJO has just Destroyed " . __CLASS__ . "\n"; // mmmmmmmmmmmmmmmmmmmmmmmmhhhhhh very good
    }
    public function close()
    {
        $this->bdd->close();
        echo('<br/ >la connexion a ete fermée<br/ >');

    }
    /*execute($query)
Exécute la requête $query et retourne un tableau contenant la réponse du
serveur SQL.*/
    public function execute($query){
        $bdd = $this->bdd;
        $this ->query = $query;
        
        $mysqli_query = mysqli_query($bdd,$query);
        $fetch = mysqli_fetch_assoc($mysqli_query);
        print_r($fetch);
        echo '<br/ >reponse execute<br/ >';

        return $fetch;
     
    
    }
    /*getLastQuery()
Retourne la dernière requête SQL ayant été exécutée, false si aucune
requête n’a été exécutée.*/
    public function getLastQuery(){
       $query = $this->query;
        if($query) // true
        var_dump($query);
        else //false
        echo'false';                  
        

    }
    /*- getLastResult()
Retourne le résultat de la dernière requête SQL exécutée, false si aucune
requête n’a été exécutée.*/
    public function getLastResult(){
        $bdd = $this->bdd;
        $getLastResult = $this->query;
        $lastResult = $this->execute($getLastResult);
        return $lastResult;
    }
    /*getTables()
Retourne un tableau contenant la liste des tables présentes dans la base
de données.*/
    public function getTables(){
        
        $bdd = $this->bdd;
        $appelfonction = $this->execute("SHOW TABLES"); 
    if($appelfonction){
        // foreach pour que ça retourne toutes les column;
    
        print_r($appelfonction);
        return $appelfonction;
    }
    else
    echo 'ahah niqué';
    }

   /* - getFields($table)
Retourne un tableau contenant la liste des champs présents dans la table
passée en paramètre, false en cas d’erreur.*/
    public function getFields($table){
        $tableInfo = $this->execute($table);

        if($tableInfo) {
            return($tableInfo);
        }           
        
    }
}
//--------------------------------------------------fin de la classe-------------------------------------------------------------
try
{
    $joris = new lpdo('localhost', 'root',   '', 'classes');
    $joris->execute("SELECT * FROM utilisateurs");

$joris->getLastQuery();
echo '<br/ >getLastQuery <br/ >';
$joris->getLastResult(); //marche pas
echo '<br/ >getLastResult <br/ >';
$joris->getTables();
echo '<br/ >getTable <br/ >';
$joris->getFields("SHOW COLUMNS FROM utilisateurs");
echo 'getFields deuhman';
$joris->close();
}
catch (Exception $e) // errorLog
{
    print ('Erreur : ' . $e -> getMessage() . '<br />');
}



?>

