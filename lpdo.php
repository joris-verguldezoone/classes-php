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
        $this->bdd = new mysqli($host, $username, $password, $db);
        echo 'Connexion avec succès...';
    }
    public function __destruct()
    {
        print "HARDJOJO has just Destroyed " . __CLASS__ . "\n"; // mmmmmmmmmmmmmmmmmmmmmmmmhhhhhh very good
    }
    public function close()
    {
        $this->bdd->close();
        echo('la connexion a ete fermée');

    }
    /*execute($query)
Exécute la requête $query et retourne un tableau contenant la réponse du
serveur SQL.*/
    public function execute($query){
        $this->mysqli_query = mysqli_query($this->bdd,$query);
        $mysqli_query = $this->mysqli_query;
        if($mysqli_query)
        echo '<br />';
        while($result = mysqli_fetch_assoc($mysqli_query)){
            echo "<table><tr><td  style='border: black 1px solid;'>".$result['id']."</td><td style='border: black 1px solid;'>".$result['login']. "</td><td style='border: black 1px solid;'>" .
            $result['password']. "</td><td style='border: black 1px solid;'>" .$result['email']. "</td><td style='border: black 1px solid;'>" .$result['firstname']. "</td><td style='border: black 1px solid;'>" .
            $result['lastname']. "</td></tr></table>";
        }
     
    
    }
    /*getLastQuery()
Retourne la dernière requête SQL ayant été exécutée, false si aucune
requête n’a été exécutée.*/
    public function getLastQuery(){
       $mysqli_query = $this->mysqli_query;
        if($mysqli_query) // true
        var_dump($mysqli_query);
        else //false
        echo'false';                  
        

    }
    /*- getLastResult()
Retourne le résultat de la dernière requête SQL exécutée, false si aucune
requête n’a été exécutée.*/
    public function getLastResult(){

           $lastResult = $this->result;
           var_dump($lastResult);
           $mysqli_query = $this->mysqli_query;
           var_dump($mysqli_query);
           if($lastResult)
           while($lastResult = mysqli_fetch_assoc($mysqli_query)){
              echo $lastResult['login'],$lastResult['password'],$lastResult['email'],$lastResult['firstname'],$lastResult['lastname'];
           }
        //    else
        //    echo 'false';



    }
    /*getTables()
Retourne un tableau contenant la liste des tables présentes dans la base
de données.*/
    public function getTables(){
        $bdd = $this->bdd;
        $sql = "SHOW TABLES FROM classes";
        $send = mysqli_query($bdd,$sql);
        $fetch = mysqli_fetch_all($send);

        var_dump($fetch);
    }

}
//--------------------------------------------------fin de la classe-------------------------------------------------------------
try
{
    $joris = new lpdo('localhost', 'root',   '', 'classes');
    var_dump($joris);
}
catch (Exception $e) // errorLog
{
    print ('Erreur : ' . $e -> getMessage() . '<br />');
}
// $joris->close();

$joris->execute("SELECT * FROM utilisateurs");

$joris->getLastQuery();
$joris->getLastResult();
$joris->getTables();
?>

