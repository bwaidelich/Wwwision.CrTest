<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Command;

use TYPO3\Flow\Annotations as Flow;

final class PublishNode
{
    /**
     * @Flow\Validate(type="NotEmpty")
     * @var string
     */
    private $nodeContextId;

    /**
     * @Flow\Validate(type="NotEmpty")
     * @var string
     */
    private $sourceWorkspaceId;

    /**
     * @var int
     */
    private $expectedVersion;

    public function __construct(string $nodeContextId, string $sourceWorkspaceId, int $expectedVersion)
    {
        $this->nodeContextId = $nodeContextId;
        $this->sourceWorkspaceId = $sourceWorkspaceId;
        $this->expectedVersion = $expectedVersion;
    }

    public function getNodeContextId(): string
    {
        return $this->nodeContextId;
    }

    public function getSourceWorkspaceId(): string
    {
        return $this->sourceWorkspaceId;
    }

    public function getExpectedVersion(): int
    {
        return $this->expectedVersion;
    }

}