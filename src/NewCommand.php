<?php 
namespace Acme;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use GuzzleHttp\ClientInterface;
use ZipArchive;

class NewCommand extends Command
{  
    private $client;
    public function __construct(ClientInterface $client)
    {
            $this->client = $client;
            parent::__construct();
    }
    public function configure()
    {
        $this->setName('new')
        ->setDescription('Create a Laravel Drench Application')
        ->addArgument('name', InputArgument::REQUIRED, 'Name of Laravel Drench Application');
        
    }
    public function execute(InputInterface $input, OutputInterface $output)
    {
        //create directory
       $directory = getcwd() . '/' . $input->getArgument('name');
       $output->writeln('Crafting Laravel Drench......');
       $output->writeln('......');
        $this->assertApplicationDoesNotExist($directory, $output);
       
        $this->download($zipfile = $this->makeFileName())
             ->extract($zipfile, $directory)
             ->cleanUp($zipfile);

             $output->writeln('Application Ready!.');

    }
    private function assertApplicationDoesNotExist($directory, OutputInterface $output)
    {
        //assert whether the appliction already exists
        if (is_dir($directory)) {
            $output->writeln('<error>Application already exists!</error>');
           exit(1);
        }
    }
    private function download($zipfile)
    {
       $response =  $this->client->get('http://cabinet.laravel.com/latest.zip')->getBody();

        file_put_contents($zipfile , $response); 
        return $this;
          
    }
    public function makeFileName()
    {
        return getcwd(). '/laravel_drench_' . md5(time().uniqid()) . '.zip';
    }

    public function extract($zipfile, $directory)
    {
        $archive = new ZipArchive;

        $archive->open($zipfile);
        $archive->extractTo($directory);
        $archive->close();

        return $this;
    }
    private function cleanUp($zipfile)
    {
        //@chmod($zipfile, 0777);
        unlink($zipfile);
        return $this;

    }
}
