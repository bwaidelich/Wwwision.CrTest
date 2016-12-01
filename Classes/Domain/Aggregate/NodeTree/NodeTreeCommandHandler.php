<?php
namespace Wwwision\CrTest\Domain\Aggregate\NodeTree;

use Neos\Cqrs\EventStore\Exception\EventStreamNotFoundException;
use Neos\Flow\Annotations as Flow;
use Neos\Error\Messages\Result;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Command\CreateNode;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Command\CreateSiteNode;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Command\DiscardNode;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Command\DiscardNodeTree;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Command\MoveNode;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Command\PublishNode;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Command\PublishNodeTree;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Command\PublishNodeTreePartially;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Command\RenameNode;

/**
 * @Flow\Scope("singleton")
 */
final class NodeTreeCommandHandler
{
    /**
     * @Flow\Inject
     * @var NodeTreeRepository
     */
    protected $nodeTreeRepository;

    public function handleCreateSiteNode(CreateSiteNode $command)
    {
        $nodeTree = $this->getOrCreateNodeTree($command->getWorkspaceId());
        $nodeTree->addNode($command->getNodeId(), $command->getWorkspaceId(), $command->getName(), NodeTree::POSITION_INTO, NodeTree::REFERENCE_ROOT_NODE);
        $this->nodeTreeRepository->save($nodeTree);
    }

    public function handleCreateNode(CreateNode $command)
    {
        $nodeTree = $this->getOrCreateNodeTree($command->getWorkspaceId());
        $nodeTree->addNode($command->getNodeId(), $command->getWorkspaceId(), $command->getName(), $command->getPosition(), $command->getReferenceNodeId());
        $this->nodeTreeRepository->save($nodeTree);
    }

    public function handleRenameNode(RenameNode $command)
    {
        $nodeTree = $this->getOrCreateNodeTree($command->getWorkspaceId());
        $nodeTree->renameNode($command->getNodeId(), $command->getNewName());
        $this->nodeTreeRepository->save($nodeTree);
    }

    public function handlePublishNode(PublishNode $command)
    {
        $targetTree = $this->getOrCreateNodeTree($command->getTargetWorkspaceId());
        $targetTree->publishNodeTo($command->getNodeId(), $command->getSourceWorkspaceId());

        $sourceTree = $this->nodeTreeRepository->get($command->getSourceWorkspaceId());
        $sourceTree->publishNodeFrom($command->getNodeId(), $command->getTargetWorkspaceId());

        // TODO: Check targetTree version and pull changes if > sourceTree
        $this->nodeTreeRepository->save($targetTree);
        $this->nodeTreeRepository->save($sourceTree);
    }

    public function handlePublish(PublishNodeTree $command)
    {
        $targetTree = $this->getOrCreateNodeTree($command->getTargetWorkspaceId());
        $targetTree->publishTo($command->getWorkspaceId());

        $sourceTree = $this->nodeTreeRepository->get($command->getWorkspaceId());
        $sourceTree->publishFrom($command->getTargetWorkspaceId());

        // TODO: Check targetTree version and pull changes if > sourceTree
        $this->nodeTreeRepository->save($targetTree);
        $this->nodeTreeRepository->save($sourceTree);
    }

    public function handlePublishPartially(PublishNodeTreePartially $command)
    {
        $targetTree = $this->getOrCreateNodeTree($command->getTargetWorkspaceId());
        $targetTree->publishNodesTo($command->getNodeIds(), $command->getWorkspaceId());

        $sourceTree = $this->nodeTreeRepository->get($command->getWorkspaceId());
        $sourceTree->publishNodesFrom($command->getNodeIds(), $command->getTargetWorkspaceId());

        // TODO: Check targetTree version and pull changes if > sourceTree
        $this->nodeTreeRepository->save($targetTree);
        $this->nodeTreeRepository->save($sourceTree);
    }

    public function handleDiscardNode(DiscardNode $command)
    {
        $nodeTree = $this->nodeTreeRepository->get($command->getWorkspaceId());
        $nodeTree->discardNode($command->getNodeId());
        $this->nodeTreeRepository->save($nodeTree);
    }

    public function handleDiscard(DiscardNodeTree $command)
    {
        $nodeTree = $this->nodeTreeRepository->get($command->getWorkspaceId());
        $nodeTree->discard();
        $this->nodeTreeRepository->save($nodeTree);
    }


    public function handleMoveNode(MoveNode $command)
    {
        throw new \RuntimeException('Node moving is not yet implemented');
    }

    private function getOrCreateNodeTree(string $nodeTreeIdentifier)
    {
        try {
            return $this->nodeTreeRepository->get($nodeTreeIdentifier);
        } catch (EventStreamNotFoundException $exception) {
            return NodeTree::create($nodeTreeIdentifier);
        }
    }

}