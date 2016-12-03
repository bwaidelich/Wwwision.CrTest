<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node;

use Neos\Cqrs\EventStore\Exception\EventStreamNotFoundException;
use Neos\Cqrs\EventStore\ExpectedVersion;
use Neos\Flow\Annotations as Flow;
use Wwwision\CrTest\Domain\Aggregate\Node\Command\CreateNode;
use Wwwision\CrTest\Domain\Aggregate\Node\Command\CreateSiteNode;
use Wwwision\CrTest\Domain\Aggregate\Node\Command\DiscardNode;
use Wwwision\CrTest\Domain\Aggregate\Node\Command\MoveNode;
use Wwwision\CrTest\Domain\Aggregate\Node\Command\PublishNode;
use Wwwision\CrTest\Domain\Aggregate\Node\Command\RenameNode;

/**
 * @Flow\Scope("singleton")
 */
final class NodeCommandHandler
{
    /**
     * @Flow\Inject
     * @var NodeRepository
     */
    protected $nodeRepository;

    public function handleCreateSiteNode(CreateSiteNode $command)
    {
        $node = Node::createSiteNode($command->getNodeId(), $command->getWorkspaceId(), $command->getName());
        $this->nodeRepository->save($node, ExpectedVersion::NO_STREAM);
    }

    public function handleCreateNode(CreateNode $command)
    {
        $node = Node::create($command->getNodeId(), $command->getWorkspaceId(), $command->getName(), $command->getPosition(), $command->getReferenceNodeId());
        $this->nodeRepository->save($node, ExpectedVersion::NO_STREAM);
    }

    public function handleRenameNode(RenameNode $command)
    {
        try {
            $node = $this->nodeRepository->get($command->getNodeContextId());
        } catch (EventStreamNotFoundException $exception) {
            $node = Node::materialize($command->getNodeId(), $command->getWorkspaceId());
        }
        $node->rename($command->getNewName());
        $this->nodeRepository->save($node);
    }

    public function handlePublishNode(PublishNode $command)
    {
        try {
            $targetNode = $this->nodeRepository->get($command->getTargetNodeContextId());
        } catch (EventStreamNotFoundException $exception) {
            $targetNode = Node::materialize($command->getNodeId(), $command->getTargetWorkspaceId());
        }
        $targetNode->publishTo($command->getSourceWorkspaceId());

        $sourceNode = $this->nodeRepository->get($command->getSourceNodeContextId());
        $sourceNode->publishFrom($command->getTargetWorkspaceId());

        $this->nodeRepository->save($targetNode, $command->getExpectedVersion());
        $this->nodeRepository->save($sourceNode);
    }

    public function handleDiscardNode(DiscardNode $command)
    {
        $node = $this->nodeRepository->get($command->getNodeContextId());
        $node->discard();
        $this->nodeRepository->save($node);
    }

    public function handleMoveNode(MoveNode $command)
    {
        throw new \RuntimeException('Node moving is not yet implemented');
    }
}