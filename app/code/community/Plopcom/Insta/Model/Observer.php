<?php

/**
 * Plopcom_Insta_Model_Observer
 */
class Plopcom_Insta_Model_Observer
{

    public function loadNewPosts(){
        $usernames = array();
        $counter = 0;
        foreach (Mage::app()->getWebsites() as $website) {
            foreach ($website->getGroups() as $group) {
                $stores = $group->getStores();
                foreach ($stores as $store) {
                    $username = Mage::getStoreConfig(Plopcom_Insta_Helper_Data::XML_PATH_POST_USERNAME,$store);
                    if ($username ){
                        if (!in_array($username,$usernames)){
                            $limit = Mage::getStoreConfig('plopcom_insta/post/limit',$store);
                            $counter += Mage::helper('plopcom_insta/data')->getLastPostFromUsername($username,$limit,$store->getId());
                            $usernames[] = $username;
                        }else{
                            $limit = Mage::getStoreConfig('plopcom_insta/post/limit',$store);
                            Mage::helper('plopcom_insta/data')->addPostFromUsernameToStore($username,$limit,$store->getId());
                        }
                    }
                }
            }
        }
        return $counter;
    }

}