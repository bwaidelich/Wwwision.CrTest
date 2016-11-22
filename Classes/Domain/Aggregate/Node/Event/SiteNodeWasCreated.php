<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Event;

use Neos\Cqrs\Event\EventInterface;

final class SiteNodeWasCreated implements EventInterface
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

    public function __construct(string $nodeId, string $workspaceId, string $name)
    {
        $this->nodeId = $nodeId;
        $this->workspaceId = $workspaceId;
        $this->name = $name;
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

}