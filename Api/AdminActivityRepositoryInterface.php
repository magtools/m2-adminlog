<?php

namespace Mtools\AdminLog\Api;

use Ceg\Impo\Api\Data\ImpoInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;

interface AdminActivityRepositoryInterface
{
    /**
     * @param int $logId
     * @return AdminActivityInterface
     * @throws NoSuchEntityException
     */
    public function getById($logId);

    /**
     * @param  AdminActivityInterface $log
     * @return AdminActivityInterface
     * @throws CouldNotSaveException
     */
    public function save(AdminActivityInterface $log);

    /**
     * @param  AdminActivityInterface $log
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(AdminActivityInterface $log);
}
