<?php 

if(isset($_POST['mail'])){
$mail = $_POST['mail'];
$sql = "SELECT * FROM member WHERE mail = :mail";
$ps = $dbh->prepare($sql);
$ps->bindValue(':mail', $mail, PDO::PARAM_STR);
$ps->execute();
$rows = $ps->fetchAll(PDO::FETCH_ASSOC);
}
?>
<body>

