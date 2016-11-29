<?php
namespace Wwwision\CrTest\Domain\Aggregate\NodeTree\Command;

use TYPO3\Flow\Annotations as Flow;

final class DiscardNodeTree
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