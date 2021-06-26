<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PushNotifications
 */


namespace Amasty\PushNotifications\Api;

interface CampaignStoreRepositoryInterface
{
    /**
     * Save
     *
     * @param Data\CampaignStoreInterface $item
     *
     * @return Data\CampaignStoreInterface
     */
    public function save(Data\CampaignStoreInterface $item);

    /**
     * Get by id
     *
     * @param int $id
     *
     * @return Data\CampaignStoreInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * Delete
     *
     * @param Data\CampaignStoreInterface $item
     *
     * @return bool true on success
     *
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(Data\CampaignStoreInterface $item);

    /**
     * Delete by id
     *
     * @param int $id
     *
     * @return bool true on success
     *
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($id);
}
