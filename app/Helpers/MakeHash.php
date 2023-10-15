<?php

namespace App\Helpers;

class MakeHash
{

    public $word;
    public $md5;
    public $sha1;
    public $sha256;
    public $sha512;
    public $ntlm;

    public function __construct(string $word = null)
    {
        $this->word = $word;
        $this->md5 = $this->md5();
        $this->sha1 = $this->sha1();
        $this->sha256 = $this->sha256();
        $this->sha512 = $this->sha512();
        $this->ntlm = $this->ntlm();
    }

    private function  md5(): string
    {
        return hash('md5', $this->word);
    }

    private function  sha1(): string
    {
        return hash('sha1', $this->word);
    }

    private function  sha256(): string
    {
        return hash('sha256', $this->word);
    }

    private function  sha512(): string
    {
        return hash('sha512', $this->word);
    }

    private function  ntlm(): string
    {
        // Convert the password from UTF8 to UTF16 (little endian)
        $Input = iconv('UTF-8', 'UTF-16LE', $this->word);

        // Encrypt it with the MD4 hash
        $MD4Hash = bin2hex(mhash(MHASH_MD4, $Input));

        // You could use this instead, but mhash works on PHP 4 and 5 or above
        // The hash function only works on 5 or above
        //$MD4Hash=hash('md4',$Input);

        // Make it uppercase, not necessary, but it's common to do so with NTLM hashes
        $NTLMHash = strtoupper($MD4Hash);

        // Return the result
        return ($NTLMHash);
    }
}
