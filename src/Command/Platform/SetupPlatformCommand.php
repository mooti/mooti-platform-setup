<?php
namespace Mooti\Setup\Command\Platform;

use Mooti\Framework\Application\Cli\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mooti\Framework\Framework;
use Mooti\Framework\Util\FileSystem;
use Symfony\Component\Process\Process;
use Mooti\Framework\Exception\FileSystemException;

class SetupPlatformCommand extends Command
{
    use Framework;

    protected $isDefaultCommand = true;

    const LATEST_RELEASE = 'tags/0.0.7';
    const RELEASE_URL    =  'https://api.github.com/repos/mooti/platform/releases';

    protected function configure()
    {
        $this->setName('platform:setup');
        $this->setDescription('Setup the platform');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $latestRelease = self::LATEST_RELEASE;
        $releaseUrl    = self::RELEASE_URL;

        $output->writeln($latestRelease);

        $fileSystem = $this->createNew(FileSystem::class);
        $curDir = $fileSystem->getCurrentWorkingDirectory();

        try {
            $platformConfigContents = $fileSystem->fileGetContents($curDir . '/platform.json');
            $platformConfig = json_decode($platformConfigContents, true);
        } catch (FileSystemException $e) {
            $platformConfig = null;
        }

        if ($platformConfig) {
            $currentRelease = 'tags/'.$platformConfig['platform']['version'];
        } else {
            $currentRelease = $latestRelease;
        }

        $currentReleaseUrl = $releaseUrl . '/' . $currentRelease;

        $relaseDetails = $this->getReleaseDetails($currentReleaseUrl);
        
        $downloadUrl = $relaseDetails->assets[0]->browser_download_url;

        $command = 'curl -o platform.zip -L '.$downloadUrl;
        $this->runCommand($command, $output);

        $command = 'unzip platform.zip && rm platform.zip';
        $this->runCommand($command, $output);

        $output->writeln('done');
    }

    public function getReleaseDetails($releaseUrl)
    {
        // Initialize session and set URL.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $releaseUrl);

        // Set so curl_exec returns the result instead of outputting it.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch,CURLOPT_USERAGENT, 'cURL');

        // Get the response and close the channel.
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }

    public function runCommand($command, OutputInterface $output)
    {
        $output->writeln('Run: '.$command);
        $process = $this->createNew(Process::class, $command);
        $process->setTimeout(3600);
        $process->mustRun(function ($type, $buffer) use ($output) {
            $output->writeln(trim($buffer));
        });
    }
}