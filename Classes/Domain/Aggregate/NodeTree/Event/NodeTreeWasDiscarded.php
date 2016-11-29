<?php
namespace Wwwision\CrTest\Domain\Aggregate\NodeTree\Event;

use Neos\Cqrs\Event\EventInterface;

final class NodeTreeWasDiscarded implements EventInterface
{

    /**
     * @var string
     */
    private $workspaceId;

    public function __construct(string $workspaceId)
    {
        $this->workspaceId = $workspaceId;
    }

    public function getWorkspaceId(): string
    {
        return $this->workspaceId;
    }

}