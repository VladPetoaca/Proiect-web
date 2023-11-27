<?php
include('config.php');

$error='';
if (!empty($_POST['id']))
{ if (isset($_POST['submit']))

    if (is_numeric($_POST['id']))
    {
        $id = $_POST['id'];
        $nume = htmlentities($_POST['nume'], ENT_QUOTES);
        $email = htmlentities($_POST['email'], ENT_QUOTES);
        $telefon = htmlentities($_POST['telefon'], ENT_QUOTES);
        $adresa = htmlentities($_POST['adresa'], ENT_QUOTES);


        if ($nume == '' || $email == ''||$telefon ==''||$adresa =='')
        {
            echo "<div>Completați toate câmpurile!</div>";
        }else
        {
            if ($stmt = $mysqli->prepare("UPDATE sponsori SET 
                       nume = ?,email=?,telefon=?,adresa=? WHERE id='".$id."'"))
            {
                $stmt->bind_param("ssss", $nume, $email, $telefon, $adresa);
                $stmt->execute();
                $stmt->close();
                echo "Datele despre sponsor au fost actualizate cu succes!";
            }
            else
            {echo "Nu s-au putut modifica datele despre sponsor.";}
        }
    }

    else
    {echo "ID incorect!";} }?>



<html>
<head>
    <title> <?php if ($_GET['id'] != '') { echo "Actualizare Sponsor"; }?> </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf8"/>
    <link rel="stylesheet" href="crud.css">
</head>

<body>
<h1><?php if ($_GET['id'] != '') { echo "Actualizare Sponsor"; }?></h1>
<?php if ($error != '') {
    echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error."</div>";} ?>
<form action="" method="post">
    <div>
        <?php if ($_GET['id'] != '') { ?>
        <input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
        <p>ID: <?php echo $_GET['id'];
            if ($result = $mysqli->query("SELECT * FROM sponsori where ID='".$_GET['id']."'"))
            {
            if ($result->num_rows > 0)
            { $row = $result->fetch_object();?></p>

        <label for="nume"><strong>Nume: </strong></label> <input type="text" name="nume" value="<?php echo$row->Nume;?>"/><br/>
        <label for="email"><strong>Email: </strong></label> <input type="text" name="email" value="<?php echo$row->Email;?>"/><br/>
        <label for="telefon"><strong>Telefon: </strong></label> <input type="text" name="telefon" value="<?php echo$row->Telefon;?>"/><br/>
        <label for="adresa"><strong>Adresa: </strong></label> <input type="text" name="adresa" value="<?php echo$row->Adresa;}}}?>"/><br/>
        <br>
        <input type="submit" name="submit" value="Modifică" />
        <a href="sponsor_view.php">Lista de sponsori</a>
    </div></form></body> </html>

