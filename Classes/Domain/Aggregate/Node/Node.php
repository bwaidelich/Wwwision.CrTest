<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node;

use Neos\Cqrs\Domain\AbstractEventSourcedAggregateRoot;
use TYPO3\Flow\Annotations as Flow;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\NodeWasCreated;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\NodeWasDiscarded;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\NodeWasPublishedFrom;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\NodeWasPublishedTo;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\NodeWasRenamed;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\SiteNodeWasCreated;

final class Node extends AbstractEventSourcedAggregateRoot
{
    /**
     * @var bool
     */
    private $dirty = false;

    static public function createSiteNode(string $nodeId, string $workspaceId, string $name): Node
    {
        $contextId = $nodeId . '@' . $workspaceId;
        $node = new static($contextId);
        $node->recordThat(new SiteNodeWasCreated($nodeId, $workspaceId, $name));
        return $node;
    }

    static public function create(string $nodeId, string $workspaceId, string $name, string $position, string $referenceNodeId): Node
    {
        $contextId = $nodeId . '@' . $workspaceId;
        $node = new static($contextId);
        $node->recordThat(new NodeWasCreated($nodeId, $workspaceId, $name, $position, $referenceNodeId));
        return $node;
    }

    public function whenNodeWasCreated(NodeWasCreated $_)
    {
        $this->dirty = true;
    }

    public static function materialize(string $contextId)
    {
        $node = new static($contextId);
        #$node->recordThat(new NodeWasMaterialized($contextId));
        return $node;
    }

    /**
     * @param string $contextId
     * @param string $sourceWorkspaceId
     * @return static
     */
    public static function publish(string $contextId, string $sourceWorkspaceId)
    {
        $node = new static($contextId);
        $node->publishTo($sourceWorkspaceId);
        return $node;
    }

    public function publishFrom(string $targetWorkspaceId)
    {
        $this->recordThat(new NodeWasPublishedFrom($this->getIdentifier(), $targetWorkspaceId));
    }

    public function publishTo(string $sourceWorkspaceId)
    {
        $this->recordThat(new NodeWasPublishedTo($this->getIdentifier(), $sourceWorkspaceId));
    }

    public function whenNodeWasPublishedFrom(NodeWasPublishedFrom $_)
    {
        $this->dirty = false;
    }

    public function rename(string $newName)
    {
        $this->recordThat(new NodeWasRenamed($this->getIdentifier(), $newName));
    }

    public function whenNodeWasRenamed(NodeWasRenamed $_)
    {
        $this->dirty = true;
    }

    public function discard()
    {
        if (!$this->dirty) {
            throw new \RuntimeException(sprintf('The node "%s" has no local changes!', $this->getIdentifier()), 1477594258);
        }
        $this->recordThat(new NodeWasDiscarded($this->getIdentifier()));
    }

}