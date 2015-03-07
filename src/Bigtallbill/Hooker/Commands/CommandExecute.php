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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CommandExecute extends Command
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

        $this->setName('execute')
            ->setDescription('Executes the commit hook in the target repo')
            ->addArgument('type', InputArgument::REQUIRED)
            ->addArgument('git', InputArgument::IS_ARRAY)
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
        $output->writeln('Executing ' . $input->getArgument('type') . ' in "' . $repoRoot . '"');

        $hooker = new Hooker($this->hookerRoot, $repoRoot);
        $out = $hooker->execute($input->getArgument('type'), $input->getArgument('git'));
        if ($out !== true) {
            list($text, $exitCode) = $out;
            $output->writeln($text);

            // symfony will use this return as the exit code
            return $exitCode;
        }

        // normal exit code
        return 0;
    }
}
