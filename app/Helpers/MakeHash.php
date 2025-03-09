<?php

namespace App\Helpers;

class MakeHash
{
    public ?string $word;

    public string $md5;

    public string $sha1;

    public string $sha256;

    public string $sha512;

    public string $ntlm;

    public function __construct(?string $word = null)
    {
        $this->word = $word;
        $this->md5 = $this->md5();
        $this->sha1 = $this->sha1();
        $this->sha256 = $this->sha256();
        $this->sha512 = $this->sha512();
        $this->ntlm = $this->ntlm();
    }

    private function md5(): string
    {
        return hash('md5', $this->word);
    }

    private function sha1(): string
    {
        return hash('sha1', $this->word);
    }

    private function sha256(): string
    {
        return hash('sha256', $this->word);
    }

    private function sha512(): string
    {
        return hash('sha512', $this->word);
    }

    private function ntlm(): string
    {
        // Convert the password from UTF8 to UTF16 (little endian)
        $Input = iconv('UTF-8', 'UTF-16LE', $this->word);

        // Encrypt it with the MD4 hash
        $MD4Hash = hash('md4', $Input);

        // Make it uppercase, not necessary, but it's common to do so with NTLM hashes
        $NTLMHash = strtoupper($MD4Hash);

        // Return the result
        return $NTLMHash;
    }
}
