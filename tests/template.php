<?php

include("../template.class.php");

$users = array(
    array("username" => "monk3y", "location" => "Portugal")
    , array("username" => "Sailor", "location" => "Moon")
    , array("username" => "Treix!", "location" => "Caribbean Islands")
);

foreach ($users as $user) {
    $row = new Template("templates/list_users_row.tpl");

    foreach ($user as $key => $value) {
        $row->set($key, $value);
    }
    $usersTemplates[] = $row;
}
$usersContents = Template::merge($usersTemplates);

$usersList  = new Template("templates/list_users.tpl");
$usersList->set("users", $usersContents);

$layout = new Template("templates/layout.tpl");
$layout->set("title", "Users");
$layout->set("content", $usersList->output());

echo $layout->output();
