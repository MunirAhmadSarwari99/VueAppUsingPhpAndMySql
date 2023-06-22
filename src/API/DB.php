<?php
$con = new mysqli('localhost', 'root', '', 'vuedb');

if(! $con){
    echo "Connection Error";
}