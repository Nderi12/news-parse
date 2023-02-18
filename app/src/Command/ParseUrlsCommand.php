<?php 

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

use App\Message\SourceUrlMessage;


class ParseUrlsCommand extends Command
{
  protected static $defaultName = 'app:parse-urls';
  private $projectDir;

  /**
   * Undocumented function
   *
   * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
   * @param \Symfony\Component\Messenger\MessageBusInterface $bus
   * @author Nderi Kamau <nderikamau1212@gmail.com>
   */
  public function __construct(KernelInterface $kernel, MessageBusInterface $bus)
  {
    $this->projectDir = $kernel->getProjectDir();
    $this->messageBus = $bus;

    parent::__construct();
  }

  /**
   * Enque Parsing function
   *
   * @param array $src
   * @return void
   * @author Nderi Kamau <nderikamau1212@gmail.com>
   */
  private function enqueueParsing(array $src) 
  {
    $this->messageBus->dispatch(new SourceUrlMessage(json_encode($src)));
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $sourcesJson = file_get_contents($this->projectDir. '/resources/sources.json');
    $sources     = json_decode($sourcesJson, true);

    foreach($sources as $src) {
      $this->enqueueParsing($src);
    }

    return Command::SUCCESS;

    // return Command::FAILURE;

    // or return this to indicate incorrect command usage; e.g. invalid options
    // or missing arguments (it's equivalent to returning int(2))
    // return Command::INVALID
  }
}
