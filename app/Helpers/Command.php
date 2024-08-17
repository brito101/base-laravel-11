<?php

namespace App\Helpers;

use Symfony\Component\Process\Process;

class Command
{
    public static function execute($cmd): array
    {
        $process = Process::fromShellCommandline($cmd);
        $error = false;
        $processOutput = '';

        $captureOutput = function ($type, $line) use (&$processOutput) {
            $processOutput .= $line;
        };

        $process->setTimeout(null)
            ->run($captureOutput);

        if ($process->getExitCode()) {
            $error = true;
        }

        return ['cmd' => $cmd, 'result' => $processOutput, 'error' => $error];
    }

    /**
     * Summary of checkInstall
     */
    public static function checkInstall(string $program, ?string $package = null): bool
    {
        $result = Command::execute("which $program");

        if ($result['result'] == '' || $result['error']) {

            $yum = Command::execute('which yum');
            if ($yum['result'] != '' || ! $result['error']) {
                $installation = Command::execute("sudo yum install $program -y");
            } else {
                $installation = Command::execute("sudo apt install $program -y");
            }

            if ($installation['result'] == '' || $installation['error']) {
                return false;
            } else {
                if ($package) {

                    Command::execute($package);
                }

                return true;
            }
        } else {
            return true;
        }
    }
}
