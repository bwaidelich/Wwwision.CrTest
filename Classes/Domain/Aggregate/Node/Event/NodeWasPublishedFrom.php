<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Event;

use Neos\Cqrs\Event\EventInterface;

final class NodeWasPublishedFrom implements EventInterface
{
    /**
     * @var string
     */
    private $nodeId;

    /**
     * Workspace id of the node that was published from
     *
     * @var string
     */
    private $workspaceId;

    /**
     * Workspace id of the node that was published to
     *
     * @var string
     */
    private $targetWorkspaceId;


    public function __construct(string $nodeId, string $workspaceId, string $targetWorkspaceId)
    {
        $this->nodeId = $nodeId;
        $this->workspaceId = $workspaceId;
        $this->targetWorkspaceId = $targetWorkspaceId;
    }

    public function getNodeId(): string
    {
        return $this->nodeId;
    }

    public function getWorkspaceId(): string
    {
        return $this->workspaceId;
    }

    public function getTargetWorkspaceId(): string
    {
        return $this->targetWorkspaceId;
    }

}