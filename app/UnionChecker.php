<?php


class UnionChecker
{
    protected $unions;

    protected $hashSalt;

    public function __construct($unions, $hashSalt)
    {
        $this->unions = $unions;
        $this->hashSalt = $hashSalt;
    }

    public function getUnionByHash($hash)
    {
        foreach ($this->unions as $name => $union) {
            $unionKtHash = sha1($union['kt'] . $this->hashSalt);
            if ($hash == $unionKtHash) {
                return $union;
            }
        }
    }
}