<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Command;

use Neos\Flow\Annotations as Flow;

final class DiscardNode
{
    /**
     * @Flow\Validate(type="NotEmpty")
     * @var string
     */
    private $nodeId;

    /**
     * @Flow\Validate(type="NotEmpty")
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

    public function getNodeContextId(): string
    {
        return $this->nodeId . '@' . $this->workspaceId;
    }

}