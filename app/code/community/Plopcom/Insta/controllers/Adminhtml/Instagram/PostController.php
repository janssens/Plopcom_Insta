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
 * Post admin controller
 *
 * @category    Plopcom
 * @package     Plopcom_Insta
 * @author      G Janssens
 */
class Plopcom_Insta_Adminhtml_Instagram_PostController extends Mage_Adminhtml_Controller_Action
{
    /**
     * init the post
     *
     * @access protected
     * @return Plopcom_Insta_Model_Post
     */
    protected function _initPost()
    {
        $postId  = (int) $this->getRequest()->getParam('id');
        $post    = Mage::getModel('plopcom_insta/post');
        if ($postId) {
            $post->load($postId);
        }
        Mage::register('current_post', $post);
        return $post;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author G Janssens
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('plopcom_insta')->__('Instagram'))
             ->_title(Mage::helper('plopcom_insta')->__('Posts'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author G Janssens
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit post - action
     *
     * @access public
     * @return void
     * @author G Janssens
     */
    public function editAction()
    {
        $postId    = $this->getRequest()->getParam('id');
        $post      = $this->_initPost();
        if ($postId && !$post->getId()) {
            $this->_getSession()->addError(
                Mage::helper('plopcom_insta')->__('This post no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getPostData(true);
        if (!empty($data)) {
            $post->setData($data);
        }
        Mage::register('post_data', $post);
        $this->loadLayout();
        $this->_title(Mage::helper('plopcom_insta')->__('Instagram'))
             ->_title(Mage::helper('plopcom_insta')->__('Posts'));
        if ($post->getId()) {
            $this->_title($post->getMediaId());
        } else {
            $this->_title(Mage::helper('plopcom_insta')->__('Add post'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new post action
     *
     * @access public
     * @return void
     * @author G Janssens
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * load last posts action
     *
     * @access public
     * @return void
     * @author G Janssens
     */
    public function loadLastPostsAction()
    {
        $count = @Mage::getModel("plopcom_insta/observer")->loadNewPosts();
        Mage::getSingleton('adminhtml/session')->addSuccess(
            Mage::helper('plopcom_insta')->__('Last Posts where successfully loaded (%d)',$count)
        );
        $this->_redirect('*/*/');
        return;
    }

    /**
     * save post - action
     *
     * @access public
     * @return void
     * @author G Janssens
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('post')) {
            try {
                $post = $this->_initPost();
                $post->addData($data);
                $post->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('plopcom_insta')->__('Post was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $post->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setPostData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('plopcom_insta')->__('There was a problem saving the post.')
                );
                Mage::getSingleton('adminhtml/session')->setPostData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('plopcom_insta')->__('Unable to find post to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete post - action
     *
     * @access public
     * @return void
     * @author G Janssens
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $post = Mage::getModel('plopcom_insta/post');
                $post->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('plopcom_insta')->__('Post was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('plopcom_insta')->__('There was an error deleting post.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('plopcom_insta')->__('Could not find post to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete post - action
     *
     * @access public
     * @return void
     * @author G Janssens
     */
    public function massDeleteAction()
    {
        $postIds = $this->getRequest()->getParam('post');
        if (!is_array($postIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('plopcom_insta')->__('Please select posts to delete.')
            );
        } else {
            try {
                foreach ($postIds as $postId) {
                    $post = Mage::getModel('plopcom_insta/post');
                    $post->setId($postId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('plopcom_insta')->__('Total of %d posts were successfully deleted.', count($postIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('plopcom_insta')->__('There was an error deleting posts.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author G Janssens
     */
    public function massStatusAction()
    {
        $postIds = $this->getRequest()->getParam('post');
        if (!is_array($postIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('plopcom_insta')->__('Please select posts.')
            );
        } else {
            try {
                foreach ($postIds as $postId) {
                $post = Mage::getSingleton('plopcom_insta/post')->load($postId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d posts were successfully updated.', count($postIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('plopcom_insta')->__('There was an error updating posts.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass add store change - action
     *
     * @access public
     * @return void
     * @author G Janssens
     */
    public function massStoreAddAction()
    {
        $postIds = $this->getRequest()->getParam('post');
        if (!is_array($postIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('plopcom_insta')->__('Please select posts.')
            );
        } else {
            try {
                foreach ($postIds as $postId) {
                    $post = Mage::getSingleton('plopcom_insta/post')->load($postId);
                    $post->addStore($this->getRequest()->getParam('store_id'));
                    $post->setIsMassupdate(true)->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d posts were successfully updated.', count($postIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('plopcom_insta')->__('There was an error updating posts.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass remove store change - action
     *
     * @access public
     * @return void
     * @author G Janssens
     */
    public function massStoreRemoveAction()
    {
        $postIds = $this->getRequest()->getParam('post');
        if (!is_array($postIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('plopcom_insta')->__('Please select posts.')
            );
        } else {
            try {
                foreach ($postIds as $postId) {
                    $post = Mage::getSingleton('plopcom_insta/post')->load($postId);
                    $post->removeStore($this->getRequest()->getParam('store_id'));
                    $post->setIsMassupdate(true)->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d posts were successfully updated.', count($postIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('plopcom_insta')->__('There was an error updating posts.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author G Janssens
     */
    public function exportCsvAction()
    {
        $fileName   = 'post.csv';
        $content    = $this->getLayout()->createBlock('plopcom_insta/adminhtml_post_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author G Janssens
     */
    public function exportExcelAction()
    {
        $fileName   = 'post.xls';
        $content    = $this->getLayout()->createBlock('plopcom_insta/adminhtml_post_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author G Janssens
     */
    public function exportXmlAction()
    {
        $fileName   = 'post.xml';
        $content    = $this->getLayout()->createBlock('plopcom_insta/adminhtml_post_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author G Janssens
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('plopcom_insta/post');
    }
}
