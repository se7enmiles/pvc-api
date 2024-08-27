<?php

require_once('./init.php');
require_once ('./app/requests/MembersRequest.php');
require_once ('./app/Member.php');

$request = new MembersRequest($_POST);
$inputs = $request->filter();

$unionHash = $inputs['union'];
$created_time = $inputs['created_at'];
$action = $inputs['action'];


$unionChecker = new UnionChecker($unions, $configs['salt']);
$union = $unionChecker->getUnionByHash($unionHash);

$db = new Db($union['db']);

$member = new Member($db->getInstance());

switch (strtolower($action)) {
    case 'getlatest':
        $result = $member->getLatestMembersByCreatedTime($created_time);
        break;
}

echo json_encode($result);
