<?php

/*Créez un fichier nommé “user-pdo.php”. Dans ce fichier, créez une classe
“userpdo” en vous basant sur la classe user que vous avez créé dans le
job1. Vos requêtes SQL doivent maintenant être faites avec pdo.*/

class userpdo{

    private $id;
    public $login = '';
    public $password = '';
    public $email = '';
    public $firstname = '';
    public $lastname = '';

    
    function userpdo_register($login, $password, $email,$firstname, $lastname){

        $bdd = new PDO('mysql:host=localhost;dbname=classes', 'root', '');

        $login =  trim($login);
        $password = trim($password);
        $email = trim($email);
        $firstname = trim($firstname);
        $lastname =  trim($lastname);

        $errorLog = null;

        $sql = "SELECT COUNT(*) AS nbr FROM utilisateurs WHERE login = ?";// ? fait référence a l'attribut reel que l'on veut utiliser
        echo $sql;
        $count = $bdd->prepare($sql);
        
        // $count->bindvalue(':login',$login,PDO::PARAM_STR); // on initialise le :login de la requete prepare
        $count->execute(array($login));  // on execute la requete en remplaçant ? par $login
        
        $req = $count->fetch(PDO::FETCH_ASSOC); // on fetch $count, c'est un fetch assoc classique en pdo , etape finale
        var_dump($req);


        if(isset($login, $password, $email,$firstname, $lastname)){
            if($req['nbr'] == 0){

                $cryptedpass = password_hash($password,PASSWORD_BCRYPT);
                
                $sql2 = "INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES (:login, :password, :email, :firstname, :lastname)";
                // echo $sql2;
                $insert = $bdd->prepare($sql2);

                $insert->bindvalue(':login',$login,PDO::PARAM_STR);
                $insert->bindvalue(':password',$cryptedpass,PDO::PARAM_STR);
                $insert->bindvalue(':email',$email,PDO::PARAM_STR);
                $insert->bindvalue(':firstname', $firstname,PDO::PARAM_STR);
                $insert->bindvalue(':lastname', $lastname, PDO::PARAM_STR);

                $insert->execute();
                
                echo 'good';
                    // $insert_result = $insert->fetch(PDO::FETCH_ASSOC);
                    // return $insert_result;

            }else $errorLog = "Cet identifiant existe déjà";
        }
    
        return var_dump($errorLog);
    }


    function userpdo_connect($login,$password){

        $bdd = new PDO('mysql:host=localhost;dbname=classes', 'root', '');

        $this->login = $login;
        $this->password = $password;
        
        $sql = "SELECT COUNT(*) AS nbr FROM utilisateurs WHERE login = ?"; // on vérifie si l'utilisateur est bien inscrit 
        $count = $bdd->prepare($sql);
        $count->execute(array($login));  // on execute la requete en remplaçant ? par $login
        $fetch = $count->fetch(PDO::FETCH_ASSOC);
        
        var_dump($fetch);
        //------------deuxieme requete

        $sql2 ="SELECT * FROM utilisateurs WHERE login = ?";
        $verif = $bdd->prepare($sql2);
        $verif->execute(array($login));
        $utilisateur = $verif->fetch(PDO::FETCH_OBJ);

        var_dump($utilisateur);

        if(!$fetch['nbr'] == 0){
            echo'okokokoko';

            if(password_verify($password, $utilisateur->password)){
            
                $this->id = $utilisateur->id;
                $this->email = $utilisateur->email;       // on maintient le flux de donnée a la connexion 
                $this->firstname = $utilisateur->firstname;
                $this->lastname = $utilisateur->lastname;
                echo 'yeah baby';
                return $utilisateur;
            }
        }
    }
    public function userpdo_disconnect(){ // si ça fonctionne, -> undefined index utilisateur 
        $this->login = null;    
        $this->password = null;    
        $this->email = null;    
        $this->firstname = null;    // on aurait pu utiliser un foreach keys values
        $this->lastname = null; 
    }
    public function userpdo_delete(){
        $bdd = new PDO('mysql:host=localhost;dbname=classes', 'root', '');

        $login = $this->login;
        
        $delete = "DELETE FROM utilisateurs WHERE login =:login ";
        $query = $bdd->prepare($delete);
        $query->bindvalue(':login',$login,PDO::PARAM_STR);
        $query->execute();
        echo'deleted';
    }
    public function userpdo_update($login, $password, $email, $firstname, $lastname){

        $bdd = new PDO('mysql:host=localhost;dbname=classes', 'root', '');
        $previousLogin = $this->login;
    
        $login = trim($login);
        $password = trim($password);
        $email = trim($email);
        $firstname = trim($firstname);
        $lastname = trim($lastname); // on enleve les espace, les \n -> string et caractere non affichable 
    
        $update = "UPDATE utilisateurs SET  login = '$login', password = '$password',
        email = '$email', firstname = '$firstname', lastname = '$lastname' WHERE login = '$previousLogin'";
        $query= $bdd->prepare($update);

        $query->bindvalue(':login', $login,PDO::PARAM_STR);
        $query->bindvalue(':password', $password, PDO::PARAM_STR);
        $query->bindvalue(':email', $email, PDO::PARAM_STR);
        $query->bindvalue(':firstname', $firstname, PDO::PARAM_STR);
        $query->bindvalue(':lastname',$lastname,PDO::PARAM_STR);

        $query->execute();
        echo'dadadadadadaupdatedadadadadada';
        
    }
    public function userpdo_isConnected(){
        $login = $this->login;
        if($login){
        echo 'you are connected to the matrix';
        return true;
        }
    }
    public function userpdo_getAllInfos(){
        $login = $this->login;
        $password = $this->password;
        $email = $this->email;
        $firstname = $this->firstname;
        $lastname = $this->lastname;
    
        return[$login,$password,$email,$firstname,$lastname];
    }
    public function userpdo_getLogin(){
        $login = $this->login;
        return $login;
    }
    public function userpdo_getEmail(){
        $email = $this->email;
        return $email;
    }
    public function userpdo_getFirstname(){
        $firstname = $this->firstname;
        return $firstname;
    }
    public function userpdo_getLastname(){
        $lastname = $this->lastname;
        return $lastname;
    }
    public function userpdo_refresh(){
        $bdd = new PDO('mysql:host=localhost;dbname=classes', 'root', '');
    
        $id = $this->id;
    
        $select = "SELECT * FROM utilisateurs WHERE id = ?";
        $query = $bdd->prepare($select);
        $query->execute([$id]);
        $refresh = $query->fetch(PDO::FETCH_ASSOC);



        $this->login = $refresh['login'];
        $this->password = $refresh['password'];
        $this->email = $refresh['email'];
        $this->firstname = $refresh['firstname'];
        $this->lastname = $refresh['lastname'];
    
        return $refresh;
    
    }
}


$jorispdo = new userpdo();
$jorispdo->userpdo_register('onetwothree','LeBoss','Du','PDO',':)');
var_dump($jorispdo);

$jorispdo->userpdo_connect('onetwothree','LeBoss');

// $jorispdo->userpdo_disconnect();

// $jorispdo->userpdo_delete();

$jorispdo->userpdo_update('viva', 'la','sauce','alger','yesss');

$jorispdo->userpdo_isConnected();

$jorispdo->userpdo_getAllInfos();
var_dump($jorispdo);


$jorispdo->userpdo_getLogin();
var_dump($jorispdo);

$jorispdo->userpdo_getEmail();
var_dump($jorispdo);

$jorispdo->userpdo_getFirstname();
var_dump($jorispdo);

$jorispdo->userpdo_getLastname();
var_dump($jorispdo);
echo'------';

$jorispdo->userpdo_refresh();
var_dump($jorispdo);
?>

