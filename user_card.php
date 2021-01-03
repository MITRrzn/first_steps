<?php 

require_once 'vendor/autoload.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);
$user_card = $twig->loadTemplate('user_card.html');
########################################################################################################################

$mysqli = new mysqli("localhost", "root", "root", "testdb");

$user_card_id = $_GET["id"];
        if($user_card_id){
            $result = $mysqli->query("SELECT * FROM user_test WHERE id = $user_card_id");
            $user_card_id = $result->fetch_all(MYSQLI_ASSOC);
        }
// print_r($user_card_id[0]);
$avatar = base64_encode($user_card_id[0]['avatar']);


echo $user_card->render(array('name'=>$name, 'user_info'=>$user_card_id[0], 'avatar'=>$avatar ));

?>