<?php
// Password kee asitti jijjiiruu danda'a (fkn: password123)
$plain_password = "password123";

// Password hash gochuu
$hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);

echo "<h3>Plain Password:</h3> " . $plain_password . "<br>";
echo "<h3>Hashed Password (Database keessatti galchuuf):</h3> " . $hashed_password;
?>