<?php
class Plopcom_Insta_Block_Adminhtml_System_Config_Form_Button extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /*
    * Set template
    */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('plopcom/insta/system/config/button.phtml');
    }

    /**
    * Return element html
    *
    * @param  Varien_Data_Form_Element_Abstract $element
    * @return string
    */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->_toHtml();
    }

    public function getCurrentScope()
    {
        if (strlen($code = Mage::getSingleton('adminhtml/config_data')->getStore())) // store level
        {
            $store_id = Mage::getModel('core/store')->load($code)->getId();
        }
        elseif (strlen($code = Mage::getSingleton('adminhtml/config_data')->getWebsite())) // website level
        {
            $website_id = Mage::getModel('core/website')->load($code)->getId();
            $store_id = Mage::app()->getWebsite($website_id)->getDefaultStore()->getId();
        }
        else // default level
        {
            $store_id = 0;
        }
        return $store_id;
    }

    /**
    * Generate button html
    *
    * @return string
    */
    public function getButtonHtml()
    {
        $url = "https://api.instagram.com/oauth/authorize/".
                "?client_id=".
                Mage::getStoreConfig('plopcom_insta/post/client_id').
                "&scope=public_content".
                "&response_type=code".
                "&redirect_uri=".
                Mage::helper('plopcom_insta')->getSaveTokenUrl($this->getCurrentScope());
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
            'id'        => 'plopcom_insta_post_get_access_token',
            'label'     => $this->helper('adminhtml')->__('Get access token'),
            'onclick'   => "javascript:window.location.href = '".$url."'; return false;"
            ));
        $_SESSION["instagram_scope"] = $this->getCurrentScope();

        return $button->toHtml();
    }
}