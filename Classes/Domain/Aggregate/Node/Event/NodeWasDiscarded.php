<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Event;

use Neos\Cqrs\Event\EventInterface;

final class NodeWasDiscarded implements EventInterface
{
    /**
     * @var string
     */
    private $nodeId;

    /**
     * @var string
     */
    private $workspaceId;

    public function __construct(string $nodeId, string $workspaceId)
    {
        $this->nodeId = $nodeId;
        $this->workspaceId = $workspaceId;
    }

    public function getNodeId(): string
    {
        return $this->nodeId;
    }

    public function getWorkspaceId(): string
    {
        return $this->workspaceId;
    }

}