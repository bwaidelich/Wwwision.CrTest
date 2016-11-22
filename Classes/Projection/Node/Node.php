<?php
namespace Wwwision\CrTest\Projection\Node;

use Doctrine\ORM\Mapping as ORM;
use Neos\Cqrs\EventStore\ExpectedVersion;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Entity
 * @ORM\Table(name="wwwision_crtest_node")
 */
class Node
{
    /**
     * @ORM\Id
     * @var string
     */
    protected $contextId;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $workspaceId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $publishedVersion = ExpectedVersion::NO_STREAM;

    public function __construct(string $contextId, string $name)
    {
        $this->contextId = $contextId;
        list($this->id, $this->workspaceId) = explode('@', $this->contextId);
        $this->name = $name;
    }

    public function getContextId(): string
    {
        return $this->contextId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getWorkspaceId(): string
    {
        return $this->workspaceId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return integer
     */
    public function getPublishedVersion()
    {
        return $this->publishedVersion;
    }

    public function _setName(string $newName)
    {
        $this->name = $newName;
    }

    public function _setPublishedVersion($publishedVersion)
    {
        $this->publishedVersion = $publishedVersion;
    }

}