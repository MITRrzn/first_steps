<link rel="stylesheet" href="/css/table.css?3523452">
<link rel="stylesheet" href="/css/header.css?341">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css"
    integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">

<?php
    $mysqli = new mysqli("localhost", "root", "root", "testdb");
        
    $minage = $_GET["minimal"];
    $maxage = $_GET["maximal"]; 
    $sorting = $_GET["sortage"];
    $user_list = array();
    $current_page = $_GET["page"];
    if (!$current_page){
        $current_page = 0;
    }
    $limit = 5; //количество записей на странице
    $offset = 0;
    $sort_field = "id";
    $sort_value = "asc";
    if($_GET["sortage"] == "desc"){ 
        $sort_value = "desc";
    }

    if ($sorting){
        $sort_field = "age";
    }



    if(isset($_GET["page"])){
        $offset = $_GET["page"] * $limit;
    }
        
    $result = $mysqli->query ("SELECT * FROM user_test ORDER BY $sort_field $sort_value LIMIT $limit OFFSET $offset");
   
    $result_count = $mysqli->query ("SELECT * FROM user_test");    

    if ($minage > 0 && $maxage > 0) {
        $result = $mysqli->query("SELECT * FROM user_test WHERE age BETWEEN $minage AND $maxage ORDER BY $sort_field $sort_value LIMIT $limit OFFSET $offset");
        $result_count = $mysqli->query("SELECT * FROM user_test WHERE $sort_field BETWEEN $minage AND $maxage");
        $total_rows = mysqli_num_rows($result_count);

    }
    
    if($search){
        $search= htmlspecialchars($search);
            $result = $mysqli->query("SELECT * FROM user_test WHERE name LIKE '%$search%' ORDER BY $sort_field $sort_value ");
            
            // $result = $mysqli->query("SELECT * FROM user_test WHERE name LIKE '%$search%'");
            $result_count = $mysqli->query("SELECT * FROM user_test WHERE name LIKE '%$search%'");
            
    }

    if(isset($_POST["reset"])){
        $result = $mysqli->query ("SELECT * FROM user_test ORDER BY $sort_field ASC LIMIT 0,5");
        $result_count = $mysqli->query ("SELECT * FROM user_test ORDER BY id ASC");
    }
    while(($row = $result->fetch_assoc())!=false){
        $user_list[]=$row;
    }
    
    $total_rows = mysqli_num_rows($result_count);//общее количество записей в таблице

    $page_count = ceil($total_rows/$limit);//количество страниц в пагинации
    // $mysqli->close();    

?>


<form action="" method="GET" id="srch_tbl">
    <input type="text" name="search" placeholder="Search..." minlength="3" maxlength="20" value = "<?=$search?>">
    <button type="submit" ><i class="fas fa-search fa-3x "></i></button>
    <button type="submit" name="reset"><i class="fas fa-times fa-4x"></i></i></button>
</form>
<hr>
<form action="" method="get">
    Возраст от:<input type="number" name="minimal" require min = "1" value = "<?=$minage?>">
    Возраст до:<input type="number" name="maximal" require max = "100" value = "<?=$maxage?>">
    <button type="submit" name="findage">find</button>

</form>

<table>
    <?php

    foreach ($user_list as $key => $row) {
        if ($key == 0) {
            echo "<tr>";
            foreach ($row as $zagolovok => $value) {
                ?>
    <th>
        <?php 
            if ($zagolovok == "age"){
                    ?>
                    <?php 
                        if ($sort_value == "asc") {
                            ?><a  href='users.php?page=<?=$current_page?>&minimal=<?=$minage?>&maximal=<?=$maxage?>&search=<?=$search?>&sortage=desc'><?=$zagolovok?></a> <?php 
                        } else {
                            ?><a  href='users.php?page=<?=$current_page?>&minimal=<?=$minage?>&maximal=<?=$maxage?>&search=<?=$search?>&sortage=asc'><?=$zagolovok?></a><?php 
                        }
                    ?>
                
                
                <?php
            } else {
                echo $zagolovok;    
            }
        ?>
        
    </th>
    <?php
        
            }
            echo "</tr>";
        }
        echo "<tr>";
        foreach($row as $zagolovok => $value){
        
        ?>
    <td><?=$value?></td>
    <?php
        }
        ?>
    <td class="del_btn">
        <a href="del.php?id=<?=$row['id']?>"><i class="fas fa-trash-alt"></i></a>
    </td>
    <td class="edit_btn">
        <a href="edit.php?id=<?=$row['id']?>"><i class="fas fa-edit"></i></a>
    </td>
    <?echo "</tr>";
    }
    
    // $result = $mysqli->query ("SELECT * FROM user_test");
    

    
    // for ($i=1; $i < $page_count ; $i++) { 
    //    echo "<a href='users.php?page=$i'>".$i."</a>";
    // }
    $prev_page = $_GET["page"]-1;
    $next_page = $_GET["page"]+1;

   $first_page_class ="";
   $last_page_class = "";
    
   if ($current_page < 1) {
        $first_page_class = "non_active";
   }


   if ($current_page >= $page_count-1){
        $last_page_class = "non_active";
   }
?>

    <table>
        <div class="nav">
            <a href="users.php?page=0&minimal=<?=$minage?>&maximal=<?=$maxage?>&sortage=<?=$sorting?>" class="nav_angle <?=$first_page_class?>"><i class="fas fa-angle-double-left fa-3x"></i></a>
            <a href='users.php?page=<?=$prev_page?>&minimal=<?=$minage?>&maximal=<?=$maxage?>&sortage=<?=$sorting?>' class="nav_angle <?=$first_page_class?>"><i class="fas fa-angle-left fa-3x"></i></a>
            <a href='users.php?page=<?=$next_page?>&minimal=<?=$minage?>&maximal=<?=$maxage?>&sortage=<?=$sorting?>' class="nav_angle <?=$last_page_class?>"><i class="fas fa-angle-right fa-3x"></i></a>
            <a href='users.php?page=<?=$page_count-1?>&minimal=<?=$minage?>&maximal=<?=$maxage?>&sortage=<?=$sorting?>' class="nav_angle <?=$last_page_class?>"><i class="fas fa-angle-double-right fa-3x"></i></a>
        </div>
<hr>

<?php 
    $user_spisok = array();
    $result = $mysqli->query("SELECT name FROM user_test");
    while(($row = $result->fetch_assoc())!=false){
        $user_spisok[]=$row;
    }
?>
<input list="users">
<datalist id="users">
<?php 
     foreach ($user_spisok as $key => $value) {
        foreach ($value as $key => $value) {
 ?>         <option value="<?=$value?>"></option>
 <?php
        }
     }
?>
<hr>
</datalist>