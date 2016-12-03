<?php
namespace Wwwision\CrTest\Application\Command;

use Doctrine\Common\Persistence\ObjectManager as DoctrineObjectManager;
use Doctrine\ORM\EntityManager;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Wwwision\CrTest\Domain\Aggregate\Node\Command\CreateSiteNode;
use Wwwision\CrTest\Domain\Aggregate\Node\NodeCommandHandler;
use Wwwision\CrTest\Domain\Aggregate\Workspace\Command\CreateWorkspace;
use Wwwision\CrTest\Domain\Aggregate\Workspace\WorkspaceCommandHandler;

class SetupCommandController extends CommandController
{
    /**
     * @Flow\Inject
     * @var WorkspaceCommandHandler
     */
    protected $workspaceCommandHandler;

    /**
     * @Flow\Inject
     * @var NodeCommandHandler
     */
    protected $nodeCommandHandler;

    /**
     * @Flow\Inject
     * @var EntityManager
     */
    protected $entityManager;

    public function injectEntityManager(DoctrineObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function fixtureCommand()
    {
        $this->entityManager->getConnection()->exec('
            TRUNCATE wwwision_crtest_workspace;
            TRUNCATE wwwision_crtest_node;
            TRUNCATE neos_cqrs_events;
        ');

        $this->workspaceCommandHandler->handleCreateWorkspace(new CreateWorkspace('user1'));
        $this->workspaceCommandHandler->handleCreateWorkspace(new CreateWorkspace('user2'));
        $this->workspaceCommandHandler->handleCreateWorkspace(new CreateWorkspace('live'));

        $this->nodeCommandHandler->handleCreateSiteNode(new CreateSiteNode('live', 'some-site'));

        $this->outputLine('Done');
    }
}