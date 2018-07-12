<?php

namespace TaoBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use TaoBundle\Entity\Taker;
use TaoBundle\Repository\TakerRepository;

class LoadTakersCommand extends ContainerAwareCommand
{
    /** @var $takerRepository TakerRepository */
    protected $takerRepository;
    /** @var  $entityManager EntityManager */
    protected $entityManager;

    protected function configure()
    {
        $this
            ->setName('oat:load-takers')
            ->setDescription('Load takers from a json file and save them to database')
            ->addArgument('filename', InputArgument::REQUIRED, 'The filename in web/files.')
            ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->takerRepository = $this->getContainer()->get('doctrine')->getRepository(Taker::class);
        $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');

        $fileSystem = new Filesystem();
        $fileCompleteName = 'web/files/'.$filename;
        if(!$fileSystem->exists($fileCompleteName)) {
            $output->writeln(sprintf('<error>Could not find file %s in web/files/</error>', $filename));
            return;
        } else {
            $output->writeln(sprintf('<info>File %s found successfully</info>', $filename));
        }

        if(false === $fileContent = file_get_contents($fileCompleteName)) {
            $output->writeln('<error>Could not load file content</error>');
            return;
        }
        $json = json_decode($fileContent, true);

        $this->saveFileContent($output, $json);

    }

    /**
     * Check for each item of the json file if we should save it or not (based on the login
     * And persist it in db when needed
     *
     * @param OutputInterface $output
     * @param $json
     */
    protected function saveFileContent(OutputInterface $output, $json)
    {
        $nbSaved = 0;
        foreach($json as $item) {
            $currentTaker = $this->takerRepository->findByLogin($item['login']);
            if(!empty($currentTaker)) {
                $output->writeln(sprintf('<info>Taker with login "%s" already exists, skypping this item</info>', $item['login']));
            } else {
                $taker = new Taker();
                $taker->loadFromArray($item);

                $this->entityManager->persist($taker);
                $nbSaved++;
            }
        }
        if($nbSaved) {
            $output->writeln(sprintf('<info>%d item saved in database</info>', $nbSaved));
            $this->entityManager->flush();
        } else {
            $output->writeln('<info>Nothing to save</info>');
        }
    }
}