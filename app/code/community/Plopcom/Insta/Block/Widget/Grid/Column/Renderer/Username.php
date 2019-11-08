<?php

class Plopcom_Insta_Block_Widget_Grid_Column_Renderer_Username
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $html = '<a href="https://www.instagram.com/'.$row->getUsername().'/" target="_blank" >@'.$row->getUsername().'</a>';
        return $html;
    }

}