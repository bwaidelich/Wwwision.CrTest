<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Command;

use TYPO3\Flow\Annotations as Flow;

final class RenameNode
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

    /**
     * @Flow\Validate(type="NotEmpty")
     * @Flow\Validate(type="StringLength", options={"minimum": 3})
     * @var string
     */
    private $newName;

    public function __construct(string $nodeId, string $workspaceId, string $newName)
    {
        $this->nodeId = $nodeId;
        $this->workspaceId = $workspaceId;
        $this->newName = $newName;
    }

    public function getNodeId(): string
    {
        return $this->nodeId;
    }

    public function getWorkspaceId(): string
    {
        return $this->workspaceId;
    }

    public function getNewName(): string
    {
        return $this->newName;
    }

    public function getNodeContextId(): string
    {
        return $this->nodeId . '@' . $this->workspaceId;
    }

}