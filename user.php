<?php
/*
public function register($login, $password, $email, $firstname,$lastname)
Crée l’utilisateur en base de données. Retourne un tableau contenant
l’ensemble des informations concernant l’utilisateur créé.
*/

class user{

    private $id;
    public $login = '';
    public $password = '';
    public $email = '';
    public $firstname = '';
    public $lastname = ''; // on enleve les espace, les \n -> string et caractere non affichable 

    public function user_register($login, $password, $email, $firstname,$lastname){

        $bdd = mysqli_connect("localhost", "root", "", "classes");

        $this ->login = mysqli_real_escape_string($bdd,( trim($login)));
        $this ->password = mysqli_real_escape_string($bdd,( trim($password)));
        $this ->email = mysqli_real_escape_string($bdd,( trim($email)));
        $this ->firstname = mysqli_real_escape_string($bdd,( trim($firstname)));
        $this ->lastname = mysqli_real_escape_string($bdd,( trim($lastname))); // on enleve les espace, les \n -> string et caractere non affichable 

        $errorLog = null;

            $query = mysqli_query($bdd, "SELECT login FROM utilisateurs WHERE login = '$login'");
            $count = mysqli_num_rows($query);

            if(!$count){ // si l'identifiant existe déjà alors $errorLog

                    if (isset($login, $password, $email, $firstname, $lastname)) {
        
                        $cryptedpass = password_hash($password, PASSWORD_BCRYPT);                // CRYPTED 
                        $insert = mysqli_query($bdd, "INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES ('$login', '$cryptedpass', '$email', '$firstname', '$lastname')");
                    }
                }
            else $errorLog = "Ce pseudo est déjà utilisé";
        
    return $errorLog;
        }
    /*public function connect($login, $password)
    Connecte l’utilisateur, modifie les attributs présents dans la classe et
    retourne un tableau contenant l’ensemble de ses informations.*/

public function user_connect($login,$password){

    $bdd = mysqli_connect("localhost", "root", "", "classes");

    $this->login = mysqli_real_escape_string($bdd,htmlspecialchars( trim($login)));
    $this->password = mysqli_real_escape_string($bdd,htmlspecialchars( trim($password)));

    $errorLog = null; 

    if(!empty($login) && !empty($password)){
        $verification = mysqli_query($bdd, "SELECT password FROM utilisateurs WHERE login = '$login' "); // on repere le mdp crypté a comparer avec celui entré par l'utilisateur
        $count = mysqli_num_rows($verification);
            
        $query_all = mysqli_query($bdd, "SELECT * FROM utilisateurs WHERE login = '$login'"); // on récupère toutes les données pour les mettre dans une $_SESSION

        if($count){ // l'identifiant est - il reconnu par la bdd ? 
        $result = mysqli_fetch_assoc($verification);
        $utilisateur = mysqli_fetch_assoc($query_all);

            if(password_verify($password, $result['password'])){ 
                
                $this->id = $utilisateur['id'];
                $this->email = $utilisateur['email'];       // on maintient le flux de donnée a la connexion 
                $this->firstname = $utilisateur['firstname'];
                $this->lastname = $utilisateur['lastname'];

                return $utilisateur; // Guuuuuuuuuuuuuuuuuuuuuuuud
            }
            else $errorLog = "Mot de passe incorrect";
        }
        else  $errorLog = "identifiant n'existe pas";
    }
    else  $errorLog = "veuillez entrer des caractere";

    echo $errorLog; // zeeeeeeebiiiiiiiii
}

/*- public function disconnect()
Déconnecte l’utilisateur.*/

public function user_disconnect(){ // si ça fonctionne, -> undefined index utilisateur 
    $this->login = null;    
    $this->password = null;    
    $this->email = null;    
    $this->firstname = null;    // on aurait pu utiliser un foreach keys values
    $this->lastname = null;    // on aurait pu utiliser unset(1,2,3..);


    // comment savoir si un utilisateur est connecté ? 
}
public function user_delete(){
    $bdd = mysqli_connect("localhost", "root", "", "classes");
    $userLogin = $this->login;
    $queryAll = "SELECT * FROM utilisateurs where login = '$userLogin'";
    mysqli_query($bdd, $queryAll);

    if($queryAll){
    $delete = "DELETE FROM utilisateurs WHERE login = '$userLogin' "; //normalement j'aurai du mettre ça une bonne grosse BBC
    mysqli_query($bdd, $delete);
    }
}
/*public function update($login, $password, $email, $firstname,
lastname)*/
public function user_update($login, $password, $email, $firstname, $lastname){

    $bdd =  mysqli_connect ('localhost', 'root', '', 'classes');
    $previousLogin = $this->login;

    $login = mysqli_real_escape_string($bdd,( trim($login)));
    $password = mysqli_real_escape_string($bdd,( trim($password)));
    $email = mysqli_real_escape_string($bdd,( trim($email)));
    $firstname = mysqli_real_escape_string($bdd,( trim($firstname)));
    $lastname = mysqli_real_escape_string($bdd,( trim($lastname))); // on enleve les espace, les \n -> string et caractere non affichable 

    $update = mysqli_query($bdd, "UPDATE utilisateurs SET  login = '$login', password = '$password', email = '$email', firstname = '$firstname', lastname = '$lastname' WHERE login = '$previousLogin'");
    
}
/*public function isConnected()
Retourne un booléen permettant de savoir si un utilisateur est connecté ou non.*/
public function user_isConnected(){
    $login = $this->login;
    if($login){
    echo 'tamere';
    return true;
    }
}
/*- public function getAllInfos()
Retourne un tableau contenant l’ensemble des informations de l’utilisateur.*/
public function user_getAllInfos(){
    $login = $this->login;
    $password = $this->password;
    $email = $this->email;
    $firstname = $this->firstname;
    $lastname = $this->lastname;

    return[$login,$password,$email,$firstname,$lastname];
}
/*public function getLogin()
Retourne le login de l’utilisateur connecté.*/

public function user_getLogin(){
    $login = $this->login;
    return $login;
}
/*public function getEmail()
Retourne l’adresse email de l’utilisateur connecté.

- public function getFirstname()
Retourne le firstname de l’utilisateur connecté.

- public function getLastname()
Retourne le lastname de l’utilisateur connecté.

- public function refresh()
Met à jour les attributs de la classe à partir de la base de données.
Vos requêtes SQL doivent être faites à l’aide des fonctions mysqli*.*/
public function user_getEmail(){
    $email = $this->email;
    return $email;
}
public function user_getFirstname(){
    $firstname = $this->firstname;
    return $firstname;
}
public function user_getLastname(){
    $lastname = $this->lastname;
    return $lastname;
}

public function user_refresh(){
    $bdd =  mysqli_connect ('localhost', 'root', '', 'classes');

    $id = $this->id;

    $select = "SELECT * FROM utilisateurs WHERE id = '$id'";
    var_dump($select);
    $query = mysqli_query($bdd,$select);
    $refresh = mysqli_fetch_assoc($query);

    $this->login = $refresh['login'];
    $this->password = $refresh['password'];
    $this->email = $refresh['email'];
    $this->firstname = $refresh['firstname'];
    $this->lastname = $refresh['lastname'];

    return $refresh;

}





}
//-------------------------------------------------------------------instanciation de la class----------------------------------------------------------------------------

$joris = new user;
$joris->user_register('ha','ha','ha@gmail.com','ha','ha');
var_dump($joris);

$joris->user_connect('ha','ha');
var_dump($joris);

// $joris->user_disconnect();
// var_dump($joris);

// $joris->user_delete();

$joris ->user_update('az', 'az', 'az@gmail.fr', 'az', 'az');
var_dump($joris);

$joris->user_isConnected();

$joris->user_getAllInfos();
var_dump($joris);

// $joris->user_getLogin();
// echo $loginDisplay;

$joris->user_getEmail();
var_dump($joris);

$joris->user_getFirstname();
var_dump($joris);

$joris->user_getLastname();
var_dump($joris);
echo'------';
$joris->user_refresh();
var_dump($joris);
echo'------';




?>