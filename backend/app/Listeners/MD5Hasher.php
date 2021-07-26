<?php


namespace App\Listeners;


class MD5Hasher implements \Illuminate\Contracts\Hashing\Hasher
{

    /**
     * @inheritDoc
     */
    public function info($hashedValue)
    {
        // TODO: Implement info() method.
    }

    /**
     * @inheritDoc
     */
    public function make($value, array $options = [])
    {
        return hash('md5',$value);
    }

    /**
     * @inheritDoc
     */
    public function check($value, $hashedValue, array $options = [])
    {
        return $this->make($value) === $hashedValue;
    }

    /**
     * @inheritDoc
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        return false;
    }
}
