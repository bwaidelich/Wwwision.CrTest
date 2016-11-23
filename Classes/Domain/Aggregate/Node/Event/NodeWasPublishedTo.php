<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Event;

use Neos\Cqrs\Event\EventInterface;

final class NodeWasPublishedTo implements EventInterface
{
    /**
     * @var string
     */
    private $nodeId;

    /**
     * Workspace id of the node that was published to
     *
     * @var string
     */
    private $workspaceId;

    /**
     * Workspace id of the node that was published from
     *
     * @var string
     */
    private $sourceWorkspaceId;

    public function __construct(string $nodeId, string $workspaceId, string $sourceWorkspaceId)
    {
        $this->nodeId = $nodeId;
        $this->workspaceId = $workspaceId;
        $this->sourceWorkspaceId = $sourceWorkspaceId;
    }

    public function getNodeId(): string
    {
        return $this->nodeId;
    }

    public function getWorkspaceId(): string
    {
        return $this->workspaceId;
    }

    public function getSourceWorkspaceId(): string
    {
        return $this->sourceWorkspaceId;
    }

}