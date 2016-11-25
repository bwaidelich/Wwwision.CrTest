<?php
namespace Wwwision\CrTest\Domain\Aggregate\Workspace\Command;

use TYPO3\Flow\Annotations as Flow;

final class PublishWorkspace
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

    public function __construct(string $workspaceId, string $targetWorkspaceId)
    {
        $this->workspaceId = $workspaceId;
        $this->targetWorkspaceId = $targetWorkspaceId;
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