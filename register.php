<?php
include "header.php";
session_start();
if(isset($_POST['submit_reg']))
{
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['email']= $_POST['email'];
    $_SESSION['phone_number'] = $_POST['phone_number'];
    $_SESSION['nim'] = $_POST['nim'];
    header("Location:register.php?select");
}

?>

<?php
if(isset($_GET['select']))
{
    echo '<div class="text-center m-9rem">';
    $query ="SELECT * FROM  vaccines";
    $select_stmt = $db->prepare($query);
    $select_stmt->execute();
    $row = $select_stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($row as $result)
    {
        echo '<a href="register.php?vaccine='.$result['id'].'" class="button bt">'. $result['name'] .'</a><span> Left: '.$result['v_left'].'</span>';
        echo '<br>';
    }

}
else if (isset($_GET['vaccine']))
{


   $query = "SELECT name FROM vaccines where id= '{$_GET['vaccine']}'";
    $select_stmt = $db->prepare($query);
   $select_stmt->execute();
   $row0 = $select_stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['vaccine'] = $row0['name'];

  ?>
        <div class="form-group" >

        <h1 class="center">Choose your appointment</h1>

            <?php
            try {
                $query = "SELECT DISTINCT date from time WHERE time_status = 'available' order by date asc";

                $select_stmt = $db->prepare($query);
                $select_stmt->execute();
                $row = $select_stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($row as $result)
                {
                    //echo $result['date'];
                    echo " ";


                    $query2 = "SELECT * FROM time WHERE date = '{$result['date']}'";
                    $select_stmt2 = $db->prepare($query2);
                    $select_stmt2->execute();
                    $row2 = $select_stmt2->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($row2 as $result2)
                        {
                            $time =new DateTime($result2['vac_time']) ;

                            $time = date_format($time, "H:i");
                            $full_time = new DateTime($result2['vac_time']) ;
                            $full_time = date_format($full_time, "Y-m-d H:i");


                            echo '<a href="register.php?confirm='.$full_time.'" class="button bt">'.$time.'</a>';

                            echo " ";
                        }
                    echo '<br>';
                }

            }
            catch (PDOException $e)
            {
                echo $e;
            }




            ?>


        </div>


        <?php

}
else if(isset($_GET['confirm']))
{
    $_SESSION['time']= $_GET['confirm'];
   // echo $get_full_time;

    echo"<div class='center'>
     <h1>Please confirm your application</h1>";
    echo '<p>Name: '.$_SESSION['name'].'</p>';

    echo '<p>Vaccine: '.$_SESSION['vaccine'].'</p>';

    echo '<p>Email: '.$_SESSION['email'].'</p>';

    echo '<p>Email: '.$_SESSION['phone_number'].'</p>';

    echo '<p>Nim: '.$_SESSION['nim'].'</p>';
    echo '<p>Date: '.$_SESSION['time'].'</p>';

    echo '<a href="register.php?confirm_final" class="button bt">Confirm</a>';
    echo '<a href="register.php" class="button bt">Edit</a>';


 echo "</div>";


}
else if(isset($_GET['confirm_final']))
{
    $query = "INSERT into users (name, email, vaccine, phone_number, nim, time) values ('{$_SESSION['name']}', '{$_SESSION['email']}','{$_SESSION['vaccine']}', '{$_SESSION['phone_number']}','{$_SESSION['nim']}','{$_SESSION['time']}')";
    $insert_stmt=$db->prepare($query);
    $insert_stmt->execute();



    echo"<div class='center'>
     <h1>Your application has been confirmed</h1>";
    echo '<p>Name: '.$_SESSION['name'].'</p>';

    echo '<p>Vaccine: '.$_SESSION['vaccine'].'</p>';

    echo '<p>Email: '.$_SESSION['email'].'</p>';

    echo '<p>Email: '.$_SESSION['phone_number'].'</p>';

    echo '<p>Nim: '.$_SESSION['nim'].'</p>';
    echo '<p>Date: '.$_SESSION['time'].'</p>';



    echo '<a href="index.php" class="button bt">Home</a>';

    ?>

        <?php
    echo "</div>";
}
else
{
    ?>
    <div class="form-group center" >
        <form method="post" class="form-container form1">
            <h1>Your info</h1>
            <label for="username"><b>Name</b></label>
            <input type="text" placeholder="Name" name="name" required >
            <label for="text"><b>Email</b></label>
            <input type="text" placeholder="Email" name="email" required >
            <label for="number"><b>Phone Number</b></label>
            <input type="number" placeholder="Phone number" name="phone_number" required >
            <label for="email"><b>National identification number</b></label>
            <input type="number" placeholder="National identification number" name="nim" required >


            <button name="submit_reg" type="submit" class="btn">Next</button>

        </form>
    </div>

<?php
}


?>
