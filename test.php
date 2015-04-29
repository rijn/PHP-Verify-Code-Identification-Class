<?php

header("Content-Type: text/html; charset=utf-8");
header('Access-Control-Allow-Origin: *');
ini_set("display_errors", "On");

include "VC/VC.php";

$object = new VerifyCode();

$object->Init_Image("VC/1.jpg", array('g', '4', 'J', 'p'));
$object->Init_Image("VC/2.jpg", array('B', 'H', 'd', '7'));
$object->Init_Image("VC/3.jpg", array('T', 'F', '5', 'H'));
$object->Init_Image("VC/4.jpg", array('F', 'u', 'X', 'x'));
$object->Init_Image("VC/5.jpg", array('Y', 't', 'h', 'u'));
$object->Init_Image("VC/6.jpg", array('e', 'E', 'F', 'E'));
$object->Init_Image("VC/7.jpg", array('9', 'W', 'Q', 'n'));
$object->Init_Image("VC/8.jpg", array('7', 'g', 'M', 'C'));
$object->Init_Image("VC/9.jpg", array('d', 's', 'w', 'p'));
$object->Init_Image("VC/10.jpg", array('x', 'p', 'K', '2'));
$object->Init_Image("VC/11.jpg", array('8', '7', 'f', 'U'));
$object->Init_Image("VC/12.jpg", array('3', '8', 'U', 'Y'));
$object->Init_Image("VC/13.jpg", array('R', 'T', 'P', 'D'));
$object->Init_Image("VC/14.jpg", array('Z', 'F', '5', 'H'));
$object->Init_Image("VC/15.jpg", array('4', 'W', 'Q', 'n'));
$object->Init_Image("VC/16.jpg", array('E', 'U', 'b', 'g'));
$object->Init_Image("VC/17.jpg", array('J', '6', 'v', 'b'));
$object->Init_Image("VC/18.jpg", array('G', 'H', 'd', '7'));
$object->Init_Image("VC/19.jpg", array('m', 'E', 'F', 'E'));
$object->Init_Image("VC/20.jpg", array('z', 'b', 'N', 'P'));
$object->Init_Image("VC/21.jpg", array('D', '7', 'f', 'U'));
$object->Init_Image("VC/22.jpg", array('K', 's', 'w', 'p'));
$object->Init_Image("VC/23.jpg", array('w', 'Q', 'R', 'z'));
$object->Init_Image("VC/24.jpg", array('n', 'c', 'B', 'S'));
$object->Init_Image("VC/25.jpg", array('C', 'f', 'Z', 'K'));
$object->Init_Image("VC/26.jpg", array('c', 'S', '3', 'v'));
$object->Init_Image("VC/27.jpg", array('5', 'v', 'J', 'p'));
$object->Init_Image("VC/28.jpg", array('M', 'u', 'X', 'x'));
$object->Init_Image("VC/29.jpg", array('q', 'R', 'D', 's'));
$object->Init_Image("VC/30.jpg", array('N', 'G', 't', 'E'));
$object->Init_Image("VC/31.jpg", array('6', 'J', 'S', 'A'));
$object->Init_Image("VC/33.jpg", array('K', '2', 'h', 'q'));
$object->Init_Image("VC/34.jpg", array('v', '9', 'Y', 'e'));
$object->Init_Image("VC/35.jpg", array('p', 'g', 'q', '9'));
$object->Init_Image("VC/36.jpg", array('3', 'C', 'T', 'q'));
$object->Init_Image("VC/37.jpg", array('x', '8', 'Z', '9'));
$object->Init_Image("VC/38.jpg", array('S', 'w', 'Y', 'e'));

echo $object->Recognize_Image("verifyCode.jpg");
