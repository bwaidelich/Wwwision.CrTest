<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Command;

use Neos\Cqrs\EventStore\ExpectedVersion;
use TYPO3\Flow\Annotations as Flow;

final class PublishNode
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
    private $sourceWorkspaceId;

    /**
     * @Flow\Validate(type="NotEmpty")
     * @var string
     */
    private $targetWorkspaceId;

    /**
     * @var int
     */
    private $expectedVersion;

    public function __construct(string $nodeId, string $sourceWorkspaceId, string $targetWorkspaceId, int $expectedVersion = ExpectedVersion::ANY)
    {
        $this->nodeId = $nodeId;
        $this->sourceWorkspaceId = $sourceWorkspaceId;
        $this->targetWorkspaceId = $targetWorkspaceId;
        $this->expectedVersion = $expectedVersion;
    }

    public function getNodeId(): string
    {
        return $this->nodeId;
    }

    public function getSourceWorkspaceId(): string
    {
        return $this->sourceWorkspaceId;
    }

    public function getTargetWorkspaceId(): string
    {
        return $this->targetWorkspaceId;
    }

    public function getExpectedVersion(): int
    {
        return $this->expectedVersion;
    }

    public function getSourceNodeContextId(): string
    {
        return $this->nodeId . '@' . $this->sourceWorkspaceId;
    }

    public function getTargetNodeContextId(): string
    {
        return $this->nodeId . '@' . $this->targetWorkspaceId;
    }

}