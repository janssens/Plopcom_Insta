<?php
/**
 * Plopcom_Insta extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Plopcom
 * @package        Plopcom_Insta
 * @copyright      Copyright (c) 2019
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Insta default helper
 *
 * @category    Plopcom
 * @package     Plopcom_Insta
 * @author      G Janssens
 */
class Plopcom_Insta_Helper_Data extends Mage_Core_Helper_Abstract
{
    //"/tags/".str_replace('#','',$tagname)."/media/recent";

    public function addPostFromUsernameToStore($username,$limit = 3,$store_id = null){
        if ($username && $store_id) {
            $posts = Mage::getModel("plopcom_insta/post")->getCollection()->addFieldToFilter('username', $username);
            $posts->getSelect()->limit($limit);
            foreach ($posts as $post){
                $post->addStore($store_id)->save();
            }
        }
    }
    public function getLastPostFromUsername($username,$limit = 3,$store_id = null){
        $counter = 0;
        if ($username){
            $url = 'https://www.instagram.com/'.$username.'/';
            $html = file_get_contents($url);
            $html = strstr($html, '"entry_data');
            $html = strstr($html, '</script>', true);
            $html = substr($html, 0, -1);
            $html = '{'.$html;
            // For debugging... (like when Instagram changes its HTML page output).
            // echo $html;
            //$encoding = mb_detect_encoding($html); //ASCII
            $data = json_decode($html);
            if (!$data){
                return 0;
            }
            if ($data->entry_data->ProfilePage[0]->graphql->user->edge_owner_to_timeline_media->edges) {
                $nodes = $data->entry_data->ProfilePage[0]->graphql->user->edge_owner_to_timeline_media->edges;
            } else {
                //exit('Looks like this Instagram account is set to private or doesn\'t exist. We can\'t do much about that now, can we?');
                die();
            }
            $index = 0;

            foreach($nodes as $node) {
                $post = Mage::getModel("plopcom_insta/post")->getCollection()->addFieldToFilter('media_id', $node->node->id)->getFirstItem();
                if (!$post->getId()){ //not saved yet
                    try{
                        $post->setMediaId($node->node->id);
                        $post->setUsername($username);
                        $post->setShort($node->node->shortcode);
                        $post->setLikeCount((integer)$node->node->edge_liked_by->count);
                        $post->setTimestamp($node->node->taken_at_timestamp);
                        if (isset($node->node->edge_media_to_caption)){
                            $text = $node->node->edge_media_to_caption->edges[0]->node->text;
                            $post->setCaption(base64_encode($text));
                        }
                        if (isset($node->node->display_url)) {
                            $this->saveImage($node->node->display_url,$node->node->id);
                            $post->setMediaUrl($node->node->display_url);
                            $post->setStatus(1);
                            if ($store_id){
                                $post->setStores(array($store_id));
                            }
                            $post->save();
                            $counter++;
                        }
                    }catch(Exception $e){
                        Mage::log($e->getMessage(),null,'plopcom_insta.log');
                    }
                }else{
                    $post->setLikeCount((integer)$node->node->edge_liked_by->count);
                    if ($store_id){
                        $post->addStore($store_id);
                    }
                    $post->save();
                }
                $index++;
                if ($index>=$limit){
                    break;
                }
            }
        }
        return $counter;
    }

    protected function saveImage($url,$id){
        $ch = curl_init($url);
        if ($this->_createDir(Mage::getBaseDir('media') . DS . 'plopcom_insta')){
            $path = $this->getImagePath($id);
            if (!file_exists($path)){
                $fp = fopen($path, 'wb');
                try {
                    curl_setopt($ch, CURLOPT_FILE, $fp);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_exec($ch);
                    curl_close($ch);
                } catch (Exception $e){
                    Mage::log($e->getMessage(),null,'plopcom_insta.log');
                }

                fclose($fp);
            }
        }
    }

    public function getImagePath($id){
        return Mage::getBaseDir('media') . DS . 'plopcom'. DS . 'insta' . DS . $id . '.jpg';
    }

    public function getImageUrl($id){
        return Mage::getBaseUrl( Mage_Core_Model_Store::URL_TYPE_MEDIA ) . 'plopcom'. DS . 'insta' . DS . $id . '.jpg';
    }

    private function _createDir($dir,$parent = null){
        try{
            $base = Mage::getBaseDir('base');
            $dir = str_replace($base,'',$dir);
            $parent = str_replace($base,'',$parent);
            $dirs = explode(DS,$dir);
            $dirs = array_filter($dirs,function ($a){
                return strlen($a) > 0;
            });
            if (count($dirs)>1){
                $dir = array_pop($dirs);
                return $this->_createDir($dir,$parent.DS.implode(DS,$dirs));
            }else{
                if (!is_dir($base.DS.$parent.DS.$dir)) {
                    if (!is_dir($base.DS.$parent)){
                        $this->_createDir($parent);
                    }
                    if (is_dir_writeable($base.DS.$parent)){
                        mkdir($base.DS.$parent.DS.$dir);
                    }else{
                        throw new Exception($this->__('Unable to write on '.$base.DS.$parent.DS));
                    }
                }
                return true;
            }
        }catch(Exception $e){
            return false;
        }

    }
}