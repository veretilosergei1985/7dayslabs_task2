<?php

namespace App\Command;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use joshtronic\LoremIpsum;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddCustomPostCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * @var LoremIpsum
     */
    private LoremIpsum $loremIpsum;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoremIpsum $loremIpsum,
        string $name = null
    ) {
        $this->em = $entityManager;
        $this->loremIpsum = $loremIpsum;
        parent::__construct($name);
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('app:add-custom-post');
        $this->setDescription('Generate custom post');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $post = new Post();
        $post->setTitle(sprintf("Summary %s", (new \DateTime())->format('Y-m-d')));
        $post->setContent($this->loremIpsum->paragraph());
        $this->em->persist($post);
        $this->em->flush();

        $output->writeln('The post has been added.');

        return Command::SUCCESS;
    }
}
