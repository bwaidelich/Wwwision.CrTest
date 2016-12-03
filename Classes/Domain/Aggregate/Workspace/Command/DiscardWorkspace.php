<?php
namespace Wwwision\CrTest\Domain\Aggregate\Workspace\Command;

use Neos\Flow\Annotations as Flow;

final class DiscardWorkspace
{

    /**
     * @Flow\Validate(type="NotEmpty")
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