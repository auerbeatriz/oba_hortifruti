<?php
require_once("config.php");
include_once("util.php");
include_once("post.php");

session_start();
$post = new Post($con);
$nome = $post->getAdmName($_SESSION["id"]);

require_once("headerA.php");

echo "<h1> Registros de vendas </h1>";

?>

<div class='nota'>
    <label>Número da nota: </label> <br>
    <label>Data: </label><br>
    <label>Cliente: </label>

    <hr noshade='noshade'> 

    <label>1 banana nanica kg</label>

    <hr noshade='noshade'>
    
    <label>Total: </label>
</div>

<?php
mysqli_close($con);
include_once("footer.php");
?>