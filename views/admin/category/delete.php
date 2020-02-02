<?php

use App\Auth;
use App\BddConnection;
use App\Table\CategoryTable;

Auth::check();

$pdo = BddConnection::getPDO();
$table = new CategoryTable($pdo);
$table->delete($params["id"]);

header("Location: ". $router->url("admin_categories") . "?delete=1");

?>