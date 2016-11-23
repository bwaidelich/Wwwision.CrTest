<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Event;

use Neos\Cqrs\Event\EventInterface;

final class NodeWasRenamed implements EventInterface
{
    /**
     * @var string
     */
    private $nodeId;

    /**
     * @var string
     */
    private $workspaceId;

    /**
     * @var string
     */
    private $newName;


    public function __construct(string $nodeId, string $workspaceId, string $newName)
    {
        $this->nodeId = $nodeId;
        $this->workspaceId = $workspaceId;
        $this->newName = $newName;
    }

    public function getNodeId(): string
    {
        return $this->nodeId;
    }

    public function getWorkspaceId(): string
    {
        return $this->workspaceId;
    }

    public function getNewName(): string
    {
        return $this->newName;
    }

}