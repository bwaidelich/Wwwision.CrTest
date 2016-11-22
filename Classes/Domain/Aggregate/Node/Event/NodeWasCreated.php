<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Event;

use Neos\Cqrs\Event\EventInterface;

final class NodeWasCreated implements EventInterface
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
    private $name;

    /**
     * @var string one of the POSITION_* constants
     */
    private $position;

    /**
     * @var string
     */
    private $referenceNodeId;

    public function __construct(string $nodeId, string $workspaceId, string $name, string $position, string $referenceNodeId)
    {
        $this->nodeId = $nodeId;
        $this->workspaceId = $workspaceId;
        $this->name = $name;
        $this->position = $position;
        $this->referenceNodeId = $referenceNodeId;
    }

    public function getNodeId(): string
    {
        return $this->nodeId;
    }

    public function getWorkspaceId(): string
    {
        return $this->workspaceId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function getReferenceNodeId(): string
    {
        return $this->referenceNodeId;
    }

}