<?php


class MemberPointsManager
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * @param $kt_id
     * @return array
     * @throws Exception
     */
    public function get($kt_id)
    {
        $member = $this->getMember($kt_id);

        return array(
            'status' => 200,
            'data' => array(
                'kt_id' => $kt_id,
                'points' => (int)$member['points'],
            ),
        );
    }

	/**
	 * @param $kt_id
	 * @param $points
	 * @param $comment
	 *
	 * @return int[]
	 * @throws Exception
	 */
    public function increase($kt_id, $points, $comment)
    {
        $member = $this->getMember($kt_id);
		$this->changePointsHistory($member, $points, 0, $comment);

        return array(
            'status' => 200,
        );
    }

    public function decrease($kt_id, $points, $comment)
    {
        $member = $this->getMember($kt_id);
        $this->changePointsHistory($member, -1 * $points, 1, $comment);

        return array(
            'status' => 200,
        );
    }

    protected function getMember($kt_id)
    {
        $stmt = $this->db->prepare("select * from member where kt_id=?");
        $stmt->execute(array($kt_id));
        $member = $stmt->fetch();

        if (empty($member)) {
            throw new Exception("Member not found", 404);
        }

        return $member;
    }

	/**
	 * @param $member
	 * @param $points
	 * @param $changeType
	 * @param string $comment
	 *
	 * @throws Exception
	 */
    protected function changePointsHistory($member, $points, $changeType, $comment = 'Api points changed')
    {
        $changeData = array(
            $member['member_id'],
            $changeType,
            abs($points),
            $member['points'] + $points,
	        $comment,
            'pvc-system',
            1
        );

        try {
            $sql = "INSERT INTO member_point_change 
                    (member_id,  change_type,  change_point,  new_point,  comments,  updated_time, updated_by, state)
                VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($changeData);

            $stmt = $this->db->prepare("UPDATE member SET points = member.`points` + :points WHERE kt_id=:kt_id");
            $stmt->execute(array('points' => $points, 'kt_id' => $member['kt_id']));

        } catch (Exception $e) {
            throw new Exception('Can not change member points history', 500);
        }
    }

	public function getHistory($member_kt=0, $fromDate =''){

		$query = "SELECT
					CASE 
					WHEN mc.change_type = 0 THEN 'inc'
					WHEN mc.change_type = 1 THEN 'dec'
					END AS change_t,
					  mc.change_point,
					  mc.new_point,
					  mc.comments,
					  mc.updated_time,
					  mc.updated_by
					FROM
					  member_point_change mc
					  INNER JOIN member m
					    ON m.`member_id` = mc.`member_id`
					WHERE m.`kt_id` = :kt_id";

		$parameters = array('kt_id' => $member_kt);

		if(!empty($fromDate)){
			$query .= ' AND updated_time < :from_date';
			$parameters['from_date'] = $fromDate;
		}

		$stmt = $this->db->prepare($query);
		$stmt->execute($parameters);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}