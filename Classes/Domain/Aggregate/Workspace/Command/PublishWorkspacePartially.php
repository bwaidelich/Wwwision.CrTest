<?php
namespace Wwwision\CrTest\Domain\Aggregate\Workspace\Command;

use TYPO3\Flow\Annotations as Flow;

final class PublishWorkspacePartially
{

    /**
     * @Flow\Validate(type="NotEmpty")
     * @var string
     */
    private $workspaceId;

    /**
     * @Flow\Validate(type="NotEmpty")
     * @var string
     */
    private $targetWorkspaceId;

    /**
     * @Flow\Validate(type="NotEmpty")
     * @var array
     */
    private $nodes;

    /**
     * @param string $workspaceId
     * @param string $targetWorkspaceId
     * @param array $nodes
     */
    public function __construct(string $workspaceId, string $targetWorkspaceId, array $nodes)
    {
        $this->workspaceId = $workspaceId;
        $this->targetWorkspaceId = $targetWorkspaceId;
        $this->nodes = $nodes;
    }

    public function getWorkspaceId(): string
    {
        return $this->workspaceId;
    }

    public function getTargetWorkspaceId(): string
    {
        return $this->targetWorkspaceId;
    }

    public function getNodes(): array
    {
        return $this->nodes;
    }
}