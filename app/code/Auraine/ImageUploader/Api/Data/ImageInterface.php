<?php
namespace Auraine\ImageUploader\Api\Data;

interface ImageInterface
{
    
    public const ID = 'image_id';
    public const PATH = 'path';
    public const NAME = 'name';
    /**
     * Getpath
     *
     * @return void
     */
    public function getPath();

    /**
     * Setpath
     *
     * @param string $value
     * @return void
     */
    public function setPath($value);

    /**
     * Getname
     *
     * @return void
     */
    public function getName();

    /**
     * Setname
     *
     * @param string $value
     * @return void
     */
    public function setName($value);

     /**
     * Getid
     *
     * @return void
     */
    public function getId();

    /**
     * Setid
     *
     * @param string $value
     * @return void
     */
    public function setId($value);
}
