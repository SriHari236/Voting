<?php

$con = mysqli_connect("localhost","root","","voting");
if (!$con) {
    echo "Connection successful";
}else{
    die(mysqli_error($con));
}
?>
