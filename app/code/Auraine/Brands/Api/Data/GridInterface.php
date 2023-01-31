<?php
namespace Auraine\Brands\Api\Data;

interface GridInterface
{
     /**
      * @var entity_id
      */
    public const ENTITY_ID = 'entity_id';
     /**
      * @var title
      */
    public const TITLE = 'title';
     /**
      * @var status
      */
    public const STATUS = 'status';
     /**
      * @var image
      */
    public const IMAGE = 'image';
     /**
      * @var description
      */
    public const DESCRIPTION = 'description';
     /**
      * @var is_popular
      */
    public const POPULAR = 'is_popular';
     /**
      * @var is_featured
      */
    public const FEATURED = 'is_featured';
     /**
      * @var is_exclusive
      */
    public const EXCLUSIVE = 'is_exclusive';
     /**
      * Constant justin
      *
      * @var is_justin
      */
    public const JUSTIN = 'is_justin';
     /**
      * Get EntityId.
      *
      * @param entityId
      */
    public function getEntityId();
     /**
      * Set EntityId.
      *
      * @param int $entityId
      */
    public function setEntityId($entityId);
    /**
     * Get Title.
     *
     * @param $title
     */
    public function getTitle();
    /**
     * Set Title.
     *
     * @param string $title
     */
    public function setTitle($title);
    /**
     * Get Status.
     *
     * @param int $status
     */
    public function getStatus();
    /**
     * Set status.
     *
     * @param int $status
     */
    public function setStatus($status);
    /**
     * Get image.
     */
    public function getImage();
    /**
     * Set image.
     *
     * @param string $image
     */
    public function setImage($image);
    /**
     * Get description.
     */
    public function getDescription();
    /**
     * Set description.
     *
     * @param string $description
     */
    public function setDescription($description);
    /**
     * Get popualr.
     */
    public function getPopular();
    /**
     * Set popualr.
     *
     * @param int $is_popular
     */
    public function setPopular($is_popular);
    /**
     * Get featured.
     */
    public function getFeatured();
    /**
     * Set featured.
     *
     * @param int $is_featured
     */
    public function setFeatured($is_featured);
    /**
     * Get justin.
     */
    public function getJustin();
    /**
     * Set justin.
     *
     * @param int $is_justin
     */
    public function setJustin($is_justin);
    /**
     * Get exclusive.
     */
    public function getExclusive();
    /**
     * Set exclusive.
     *
     * @param int $is_exclusive
     */
    public function setExclusive($is_exclusive);
}
