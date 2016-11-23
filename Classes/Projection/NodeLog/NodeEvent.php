<?php
namespace Wwwision\CrTest\Projection\NodeLog;

use Doctrine\ORM\Mapping as ORM;
use Neos\Cqrs\EventStore\ExpectedVersion;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Entity
 * @ORM\Table(name="wwwision_crtest_nodeevent")
 */
class NodeEvent
{

    /**
     * @ORM\Id
     * @var int
     */
    protected $sequenceNumber;

    /**
     * @var \DateTimeImmutable
     */
    protected $timestamp;

    /**
     * @var string
     */
    protected $nodeId;

    /**
     * @var string
     */
    protected $workspaceId;

    /**
     * @var string
     */
    protected $eventType;

    /**
     * @ORM\Column(type="json_array")
     * @var array
     */
    protected $data;

    /**
     * @param string $sequenceNumber
     * @param $timestamp
     * @param string $nodeId
     * @param string $workspaceId
     * @param string $eventType
     * @param array $data
     */
    public function __construct($sequenceNumber, $timestamp, $nodeId, $workspaceId, $eventType, array $data = [])
    {
        $this->sequenceNumber = $sequenceNumber;
        $this->timestamp = $timestamp;
        $this->nodeId = $nodeId;
        $this->workspaceId = $workspaceId;
        $this->eventType = $eventType;
        $this->data = $data;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getNodeId()
    {
        return $this->nodeId;
    }

    /**
     * @return string
     */
    public function getWorkspaceId()
    {
        return $this->workspaceId;
    }

    /**
     * @return string
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

}