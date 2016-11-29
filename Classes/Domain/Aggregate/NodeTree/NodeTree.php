<?php
namespace Wwwision\CrTest\Domain\Aggregate\NodeTree;

use Neos\Cqrs\Domain\AbstractEventSourcedAggregateRoot;
use TYPO3\Flow\Annotations as Flow;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodesWerePublishedFrom;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodesWerePublishedTo;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeTreeWasDiscarded;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeTreeWasPublishedFrom;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeTreeWasPublishedTo;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeWasCreated;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeWasDiscarded;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeWasPublishedFrom;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeWasPublishedTo;
use Wwwision\CrTest\Domain\Aggregate\NodeTree\Event\NodeWasRenamed;

final class NodeTree extends AbstractEventSourcedAggregateRoot
{
    const POSITION_BEFORE = 'before';
    const POSITION_INTO = 'into';
    const POSITION_AFTER = 'after';

    const REFERENCE_ROOT_NODE = '/';

    protected static $supportedPositions = [self::POSITION_INTO, self::POSITION_AFTER, self::POSITION_BEFORE];

    static public function create(string $nodeTreeId): NodeTree
    {
        $nodeTree = new static($nodeTreeId);
        #$nodeTree->recordThat(new NodeWasCreated($nodeId, $workspaceId, $name, $position, $referenceNodeId));
        return $nodeTree;
    }

    public function addNode(string $nodeId, string $workspaceId, string $name, string $position, string $referenceNodeId)
    {
        $this->recordThat(new NodeWasCreated($nodeId, $workspaceId, $name, $position, $referenceNodeId));
    }

    public function publishNodeFrom(string $nodeId, string $targetWorkspaceId)
    {
        $this->recordThat(new NodeWasPublishedFrom($nodeId, $this->getIdentifier(), $targetWorkspaceId));
    }

    public function publishNodeTo(string $nodeId, string $sourceWorkspaceId)
    {
        $this->recordThat(new NodeWasPublishedTo($nodeId, $this->getIdentifier(), $sourceWorkspaceId));
    }

    public function publishNodesFrom(array $nodeIds, string $targetWorkspaceId)
    {
        $this->recordThat(new NodesWerePublishedFrom($nodeIds, $this->getIdentifier(), $targetWorkspaceId));
    }

    public function publishNodesTo(array $nodeIds, string $sourceWorkspaceId)
    {
        $this->recordThat(new NodesWerePublishedTo($nodeIds, $this->getIdentifier(), $sourceWorkspaceId));
    }
    public function publishFrom(string $targetWorkspaceId)
    {
        $this->recordThat(new NodeTreeWasPublishedFrom($this->getIdentifier(), $targetWorkspaceId));
    }

    public function publishTo(string $sourceWorkspaceId)
    {
        $this->recordThat(new NodeTreeWasPublishedTo($this->getIdentifier(), $sourceWorkspaceId));
    }

    public function discardNode(string $nodeId)
    {
        $this->recordThat(new NodeWasDiscarded($nodeId, $this->getIdentifier()));
    }

    public function discard()
    {
        $this->recordThat(new NodeTreeWasDiscarded($this->getIdentifier()));
    }

    public function renameNode(string $nodeId, string $newName)
    {
        $this->recordThat(new NodeWasRenamed($nodeId, $this->getIdentifier(), $newName));
    }

}