<?php
/**
 * Created by PhpStorm.
 * User: bigtallbill
 * Date: 3/7/15
 * Time: 10:44 AM
 */

namespace Bigtallbill\Hooker\Commands;


use Bigtallbill\Hooker\Hooker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CommandInstall extends Command
{
    /** @var null|string */
    protected $hookerRoot;

    public function __construct($hookerRoot, $name = null)
    {
        parent::__construct($name);

        $this->hookerRoot = $hookerRoot;
    }


    protected function configure()
    {
        parent::configure();

        $this->setName('install')
            ->setDescription('Installs the comit hook scripts into the target repo')
            ->addOption(
                'repo-root',
                'p',
                InputOption::VALUE_REQUIRED,
                'The path of the repo to install to. Default is current working dir',
                getcwd()
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repoRoot = $input->getOption('repo-root');
        $output->writeln('installing into repository at "' . $repoRoot . '"');

        $hooker = new Hooker($this->hookerRoot, $repoRoot);
        $hooker->install();
    }
}
