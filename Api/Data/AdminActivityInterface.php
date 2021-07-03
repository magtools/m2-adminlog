<?php

namespace Mtools\AdminLog\Api\Data;

interface AdminActivityInterface
{
    const ID = 'entity_id';
    const CREATED_AT = 'created_at';
    const USER_ID = 'user_id';
    const USER_NAME = 'user_name';
    const USER_EMAIL = 'user_email';
    const URI = 'uri';
    const HANDLER = 'handler';
    const MODULE = 'module';
    const ROUTE = 'route';
    const CONTROLLER = 'controller';
    const ACTION = 'action';
    const PARAMS = 'params';

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @param int $value
     * @return $this
     */
    public function setId($value);

    /**
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * @return int|null
     */
    public function getUserId();

    /**
     * @param int $value
     * @return $this
     */
    public function setUserId($value);

    /**
     * @return string|null
     */
    public function getUserName();

    /**
     * @return string|null
     */
    public function setUserName();

    /**
     * @return string|null
     */
    public function getUserEmail();

    /**
     * @return string|null
     */
    public function setUserEmail();

    /**
     * @return string|null
     */
    public function getUri();

    /**
     * @return string|null
     */
    public function setUri();

    /**
     * @return string|null
     */
    public function getHandler();

    /**
     * @return string|null
     */
    public function setHandler();

    /**
     * @return string|null
     */
    public function getModule();

    /**
     * @return string|null
     */
    public function setModule();

    /**
     * @return string|null
     */
    public function getRoute();

    /**
     * @return string|null
     */
    public function setRoute();

    /**
     * @return string|null
     */
    public function getController();

    /**
     * @return string|null
     */
    public function setController();

    /**
     * @return string|null
     */
    public function getAction();

    /**
     * @return string|null
     */
    public function setAction();

    /**
     * @return string|null
     */
    public function getParams();

    /**
     * @return string|null
     */
    public function setParams();

}
