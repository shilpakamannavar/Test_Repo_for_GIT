<?php
namespace Auraine\Brands\Api\Data;

interface GridInterface
{
    const ENTITY_ID = 'entity_id';
    const TITLE = 'title';
    const STATUS = 'status';
    const IMAGE = 'image';
    const DESCRIPTION = 'description';
    const POPULAR = 'is_popular';
    const FEATURED = 'is_featured';
    const EXCLUSIVE = 'is_exclusive';
    const JUSTIN = 'is_justin';
    


    public function getEntityId();
    /**
     * Set EntityId.
     */
    public function setEntityId($entityId);
    /**
     * Get Title.
     *
     * @return varchar
     */
    public function getTitle();
    /**
     * Set Title.
     */
    public function setTitle($title);

    public function getStatus();

    public function setStatus($status);

    public function getImage();

    public function setImage($image);

    public function getDescription();

    public function setDescription($description);

    public function getPopular();

    public function setPopular($is_popular);

    public function getFeatured();

    public function setFeatured($is_featured);

    public function getJustin();

    public function setJustin($is_justin);

    public function getExclusive();

    public function setExclusive($is_exclusive);

   

}
