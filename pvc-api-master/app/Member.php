<?php


class Member
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getLatestMembersByCreatedTime($created_time)
    {
        $stmt = $this->db->prepare("select * from member where created_time > ? or updated_at > ?");
        $stmt->execute(array($created_time, $created_time));
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array(
            'status' => 200,
            'data' => !empty($members) ? $members : array()
        );
    }
}