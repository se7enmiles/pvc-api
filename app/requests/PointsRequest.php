<?php


class PointsRequest
{
    protected $params;

    public function __construct($post)
    {
        $this->params = $post;
    }

    private function validate()
    {
        if (empty($this->params['union'])) {
            throw new Exception('Union param is Required', 400);
        }

        if (empty($this->params['member'])) {
            throw new Exception('Member param is Required', 400);
        }

        if (empty($this->params['action'])) {
            throw new Exception('Action param is Required', 400);
        }

        if (empty($this->params['points'])) {
            $this->params['points'] = 0;
        }

		if (empty($this->params['comment'])) {
            $this->params['comment'] = 'Points have been changed by API';
        }

        $availableActions = array('get', 'add', 'subtract', 'history');
        if (!in_array($this->params['action'], $availableActions)) {
            throw new Exception('Invalid action: Available actions ' . implode(',', $availableActions), 400);
        }
    }


    public function filter()
    {
        $this->validate();

        return $this->params;
    }
}