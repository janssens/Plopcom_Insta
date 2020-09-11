<?php

class Plopcom_Insta_Adminhtml_Instagram_SecretController extends Mage_Adminhtml_Controller_Action
{
	public function scriptAction()
    {
        $names = array();
        /** @var Mage_Core_Model_Store $store */
        foreach (Mage::app()->getStores() as $store){
            $names[] = $store->getFrontendName()." ";
        }

        $content = "// ==UserScript==\n";
        $content .= "// @name     Magento instagram importer for ";
        foreach (array_unique($names) as $name){
            $content .= $name." ";
        }
        $content .= "\n";
        $content .= "// @description This script add a button on instagram to import post in magento\n";
        $content .= "// @author   gjanssens plopcom.fr\n";
        $content .= "// @icon ".Mage::getDesign()->getSkinUrl('plopcom/insta/instaToMage.png')."\n";
        $content .= "// @version  1\n";
        $content .= "// @grant    none\n";
        foreach (Mage::helper('plopcom_insta')->getAllUniqueUrls() as $url){
            $content .= "// @match	".$url."*\n";
        }
        $content .= "// ==/UserScript==

let my_location = window.location;
let my_htmlContent = window.document.querySelector('body').innerHTML;
let my_salt = '".md5(Mage::helper('plopcom_insta')->getSalt())."';
let my_url = '".Mage::getUrl('instagram/secret/load')."';

console.log('Hacked :) !');

setTimeout(function(){
        console.log('May now be fully loaded (?)');
        init();
        let button = document.createElement(\"a\");
        button.setAttribute('href','javascript:PostContent()');
        button.setAttribute('style','position:fixed;top:10px; left: 10px;border: 1px solid black;background: white;border-radius: 32px;width: 64px;height: 64px;display: block;');
        button.innerHTML = '<img src=\"https://www.flaneurz.local/skin/frontend/base/default/plopcom/insta/instaToMage.png\" alt=\"vers magento et au delà\" width=\"48px\" height=\"48px\" style=\"margin:8px;\">';
        document.body.appendChild(button);
},1500);

function OpenWindowWithPost(url, windowoption, name, params)
   {
            var form = document.createElement(\"form\");
            form.setAttribute(\"method\", \"post\");
            form.setAttribute(\"action\", url);
            form.setAttribute(\"target\", name);

            for (var i in params) {
                if (params.hasOwnProperty(i)) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = i;
                    input.value = params[i];
                    form.appendChild(input);
                }
            }

            document.body.appendChild(form);

            //note I am using a post.htm page since I did not want to make double request to the page
           //it might have some Page_Load call which might screw things up.
            window.open(\"post.htm\", name, windowoption);

            form.submit();

            document.body.removeChild(form);
    }
function PostContent()
   {
       var param = { 'content' : my_htmlContent,'salt' : my_salt,'location' : my_location};
      OpenWindowWithPost(my_url,
      \"width=730,height=345,left=100,top=100,resizable=no,scrollbars=no\",
      \"PostWindow\", param);
    }
    
    function init()
    {
         window.PostContent = PostContent;
    }
";
        header("Content-type: text/javascript");
        header("Content-Disposition: inline; filename=\"insta-magento.user.js\"");
        header("Content-Length: ".strlen($content));
        echo $content;
        die();
    }

}
