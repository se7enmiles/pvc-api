<?php

require_once('./init.php');
require_once('./app/MemberPointsManager.php');
require_once('./app/requests/PointsRequest.php');

$request = new PointsRequest($_POST);
$inputs = $request->filter();

$unionHash = $inputs['union'];
$member_kt = $inputs['member'];
$points = $inputs['points'];
$action = $inputs['action'];
$date = $inputs['date'];
$comment = $inputs['comment'];


$unionChecker = new UnionChecker($unions, $configs['salt']);
$union = $unionChecker->getUnionByHash($unionHash);

$db = new Db($union['db']);


$pointsManager = new MemberPointsManager($db->getInstance());


switch (strtolower($action)) {
    case 'get':
        $result = $pointsManager->get($member_kt);
        break;
    case 'add':
        $result = $pointsManager->increase($member_kt, $points, $comment);
        break;
    case 'subtract':
        $result = $pointsManager->decrease($member_kt, $points, $comment);
        break;
	case 'history':
        $result = $pointsManager->getHistory($member_kt, $date);
        break;
}

echo json_encode($result);
