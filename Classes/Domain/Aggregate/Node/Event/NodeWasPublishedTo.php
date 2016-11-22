<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Event;

use Neos\Cqrs\Event\EventInterface;

final class NodeWasPublishedTo implements EventInterface
{
    /**
     * @var string
     */
    private $nodeContextId;

    /**
     * @var string
     */
    private $sourceWorkspaceId;


    public function __construct(string $nodeContextId, string $sourceWorkspaceId)
    {
        $this->nodeContextId = $nodeContextId;
        $this->sourceWorkspaceId = $sourceWorkspaceId;
    }

    public function getNodeContextId(): string
    {
        return $this->nodeContextId;
    }

    public function getSourceWorkspaceId(): string
    {
        return $this->sourceWorkspaceId;
    }


}