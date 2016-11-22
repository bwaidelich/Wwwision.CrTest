<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Event;

use Neos\Cqrs\Event\EventInterface;

final class NodeWasPublishedFrom implements EventInterface
{
    /**
     * @var string
     */
    private $nodeContextId;

    /**
     * @var string
     */
    private $targetWorkspaceId;


    public function __construct(string $nodeContextId, string $targetWorkspaceId)
    {
        $this->nodeContextId = $nodeContextId;
        $this->targetWorkspaceId = $targetWorkspaceId;
    }

    public function getNodeContextId(): string
    {
        return $this->nodeContextId;
    }

    public function getTargetWorkspaceId(): string
    {
        return $this->targetWorkspaceId;
    }


}