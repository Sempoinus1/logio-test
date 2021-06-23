<?php

require("Controller/ProductController.php");

for ($i = 0; $i <= 5; $i++){
    $p = (new ProductController)->detail($i);
    if (empty($p))
        echo "Not found <br/>";
    else{
        echo $p[key($p)]['id'] . ' => ' . $p[key($p)]['inquiry'] . "<br/>";
    }
}
