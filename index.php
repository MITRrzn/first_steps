<?php 

    require_once 'vendor/autoload.php';
    Twig_Autoloader::register();

    $loader = new Twig_Loader_Filesystem('templates');
    $twig = new Twig_Environment($loader);
    $index = $twig->loadTemplate('index.html');
########################################################################################################################  

    $mysqli = new mysqli("localhost", "root", "root", "testdb");
   
    $search = trim($_GET["search"]);
    $title = "Home page";
    $sort_field = "id";
    $sort_value = "asc";
    $limit = 5; //количество записей на странице
    $offset = 0;
    $prev_page = $_GET["page"]-1;
    $next_page = $_GET["page"]+1;
    $current_page = $_GET["page"];
    if (!$current_page){
        $current_page = 0;
    }
    if(isset($_GET["page"])){
        $offset = $_GET["page"] * $limit;
    }
    $uAgent = $_SERVER['HTTP_USER_AGENT'];
    $uIP = $_SERVER['REMOTE_ADDR'];


########################################################################################################################

    if($search){
        $search= htmlspecialchars($search);
        $result = $mysqli->query("SELECT * FROM user_test WHERE name LIKE '%$search%'");
    } else{
        $result = $mysqli->query ("SELECT * FROM user_test ORDER BY $sort_field $sort_value LIMIT $limit OFFSET $offset");
    }

    
########################################################################################################################
   
    $result_count = $mysqli->query ("SELECT * FROM user_test"); 
    $total_rows = mysqli_num_rows($result_count);//общее количество записей в таблице
    $page_count = ceil($total_rows/$limit);

########################################################################################################################
    $newarr = $result->fetch_all(MYSQLI_ASSOC);
    foreach ($result as $key => $columnName) {
        $thName = (array_keys($columnName));
    }

########################################################################################################################
    echo $index->render(array('title' => $title, 'list' => $newarr,
     'thName' => $thName, 'page_count' =>$page_count,'next_page' => $next_page,
     'prev_page' => $prev_page, 'current_page' => $current_page, 'search' =>$search, 
     'uAgent'=>$uAgent, 'uIP'=>$uIP));
########################################################################################################################
?>