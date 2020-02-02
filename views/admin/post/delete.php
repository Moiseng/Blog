<?php

use App\Auth;
use App\BddConnection;
use App\Table\PostTable;

Auth::check();

$pdo = BddConnection::getPDO();
$table = new PostTable($pdo);
$table->delete($params["id"]);

header("Location: ". $router->url('admin_posts'). "?delete=1");

?>