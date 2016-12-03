<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node;

use Neos\Cqrs\Domain\AbstractEventSourcedAggregateRoot;
use Neos\Flow\Annotations as Flow;
use Wwwision\CrTest\Domain\Aggregate\Node\Command\CreateNode;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\NodeWasCreated;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\NodeWasDiscarded;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\NodeWasMaterialized;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\NodeWasPublishedFrom;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\NodeWasPublishedTo;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\NodeWasRenamed;
use Wwwision\CrTest\Domain\Aggregate\Node\Event\SiteNodeWasCreated;

final class Node extends AbstractEventSourcedAggregateRoot
{

    const POSITION_BEFORE = 'before';
    const POSITION_INTO = 'into';
    const POSITION_AFTER = 'after';

    const REFERENCE_ROOT_NODE = '/';

    protected static $supportedPositions = [self::POSITION_INTO, self::POSITION_AFTER, self::POSITION_BEFORE];

    /**
     * @var bool
     */
    private $dirty = false;

    /**
     * @var string
     */
    private $nodeId;

    /**
     * @var string
     */
    private $workspaceId;

    static public function createSiteNode(string $nodeId, string $workspaceId, string $name): Node
    {
        $contextId = $nodeId . '@' . $workspaceId;
        $node = new static($contextId);
        $node->recordThat(new NodeWasCreated($nodeId, $workspaceId, $name, self::POSITION_INTO, self::REFERENCE_ROOT_NODE));
        return $node;
    }

    static public function create(string $nodeId, string $workspaceId, string $name, string $position, string $referenceNodeId): Node
    {
        if (!in_array($position, static::$supportedPositions)) {
            throw new \RuntimeException(sprintf('Position "%s" is not allowed. Supported positions: %s', $position, implode(', ', static::$supportedPositions)), 1479901788);
        }
        $contextId = $nodeId . '@' . $workspaceId;
        $node = new static($contextId);
        $node->recordThat(new NodeWasCreated($nodeId, $workspaceId, $name, $position, $referenceNodeId));
        return $node;
    }

    public static function materialize(string $nodeId, string $workspaceId)
    {
        $contextId = $nodeId . '@' . $workspaceId;
        $node = new static($contextId);
        $node->recordThat(new NodeWasMaterialized($nodeId, $workspaceId));
        return $node;
    }

    public function whenNodeWasCreated(NodeWasCreated $event)
    {
        $this->dirty = true;
        $this->nodeId = $event->getNodeId();
        $this->workspaceId = $event->getWorkspaceId();
    }

    public function whenNodeWasMaterialized(NodeWasMaterialized $event)
    {
        $this->nodeId = $event->getNodeId();
        $this->workspaceId = $event->getWorkspaceId();
    }

    public static function publish(string $nodeId, string $workspaceId, string $sourceWorkspaceId)
    {
        $contextId = $nodeId . '@' . $workspaceId;
        $node = new static($contextId);
        $node->publishTo($sourceWorkspaceId);
        return $node;
    }

    public function publishFrom(string $targetWorkspaceId)
    {
        $this->recordThat(new NodeWasPublishedFrom($this->nodeId, $this->workspaceId, $targetWorkspaceId));
    }

    public function publishTo(string $sourceWorkspaceId)
    {
        $this->recordThat(new NodeWasPublishedTo($this->nodeId, $this->workspaceId, $sourceWorkspaceId));
    }

    public function whenNodeWasPublishedFrom(NodeWasPublishedFrom $_)
    {
        $this->dirty = false;
    }

    public function rename(string $newName)
    {
        $this->recordThat(new NodeWasRenamed($this->nodeId, $this->workspaceId, $newName));
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
        $this->recordThat(new NodeWasDiscarded($this->nodeId, $this->workspaceId));
    }

    public function whenNodeWasDiscarded(NodeWasDiscarded $_)
    {
        $this->dirty = false;
    }

}