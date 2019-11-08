<?php

class Plopcom_Insta_Block_Widget_Grid_Column_Renderer_Caption
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        return $row->getCaption();
    }

}