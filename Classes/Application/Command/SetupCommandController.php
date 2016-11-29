<?php
namespace Wwwision\CrTest\Application\Command;

use Doctrine\Common\Persistence\ObjectManager as DoctrineObjectManager;
use Doctrine\ORM\EntityManager;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Command\CreateNode;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Command\CreateSiteNode;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\NodeTree;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\NodeTreeCommandHandler;
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
     * @var NodeTreeCommandHandler
     */
    protected $nodeTreeCommandHandler;

    /**
     * @Flow\Inject
     * @var EntityManager
     */
    protected $entityManager;

    public function injectEntityManager(DoctrineObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function basicCommand()
    {
        $this->setupBasics();
        $this->outputLine('Done');
    }

    public function fixtureCommand()
    {
        $this->setupBasics();

        $this->nodeTreeCommandHandler->handleCreateNode(new CreateNode('live', 'Node A', NodeTree::POSITION_INTO, '/'));
        $this->nodeTreeCommandHandler->handleCreateNode(new CreateNode('live', 'Node B', NodeTree::POSITION_INTO, '/'));

        $this->outputLine('Done');
    }

    private function setupBasics()
    {
        $this->entityManager->getConnection()->exec('
            TRUNCATE wwwision_crtest_workspace;
            TRUNCATE wwwision_crtest_node;
            TRUNCATE neos_cqrs_events;
        ');

        $this->workspaceCommandHandler->handleCreateWorkspace(new CreateWorkspace('user1'));
        $this->workspaceCommandHandler->handleCreateWorkspace(new CreateWorkspace('user2'));
        $this->workspaceCommandHandler->handleCreateWorkspace(new CreateWorkspace('live'));

        $this->nodeTreeCommandHandler->handleCreateSiteNode(new CreateSiteNode('live', 'some-site'));
    }
}