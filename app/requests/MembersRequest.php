<?php


class MembersRequest
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

        if (empty($this->params['action'])) {
            throw new Exception('Action param is Required', 400);
        }

        if (empty($this->params['created_at'])) {
            throw new Exception('Created_at param is Required', 400);
        }

        $availableActions = array('getLatest');
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