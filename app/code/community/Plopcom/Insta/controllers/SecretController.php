<?php

class Plopcom_Insta_SecretController extends Mage_Core_Controller_Front_Action
{

	public function loadAction()
	{
	    $helper = Mage::helper('plopcom_insta');
	    if (isset($_POST['content'])&&isset($_POST['location'])&&isset($_POST['salt'])){
	        if (md5($helper->getSalt())==$_POST['salt']){
                $location = $_POST['location'];
                $username = false;
                foreach ($helper->getAllUniqueUrls() as $k => $url){
                    if (strpos($url,$location)===0){
                        $username = $k;
                        break;
                    }
                }
                if ($username){
                    $content = $_POST['content'];
                    $imported = Mage::helper('plopcom_insta')->getFromHtml($content,$username,10);
                    echo $imported.' post imported '.(($imported>0) ? "🙂":"").'!'."\n";
                }else{
                    echo 'Cannot import form this url ⚠ !'."\n";
                }
            }else{
                echo 'Someting went wrong 😥 !'."\n";
            }
        }

	    echo '<script> setTimeout(function(){window.close();},3000) </script>'."\n";
	    die();
	}

	public function scriptAction()
    {
        header("Content-type: text/javascript");
        header("Content-Disposition: inline; filename=\"user.script.js\"");
        //header("Content-Length: ".filesize("my-file.js"));
        $content = "// ==UserScript==
// @name     load Post from Instagram for ";
        $names = array();
        /** @var Mage_Core_Model_Store $store */
        foreach (Mage::app()->getStores() as $store){
            $names[] = $store->getFrontendName()." ";
        }
        foreach (array_unique($names) as $name){
            $content .= $name." ";
        }
        $content .= "\n// @version  1
// @grant   none\n";
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
        let a = document.querySelectorAll(\"[href='/accounts/emailsignup/']\")[0];
        if (typeof(a) == 'undefined'){ //may be logged in
            a = document.querySelectorAll(\"[href='/accounts/activity/']\")[0];
        }
        if (typeof(a) != 'undefined'){
            init();
            let sibling = a.parentElement;
            let clonedElement = sibling.cloneNode(true);
            let new_a = clonedElement.querySelector('a');
            new_a.setAttribute('href','javascript:PostContent()');
            new_a.innerText = 'vers magento et au delà';
            let container = sibling.parentElement;
            container.insertBefore(clonedElement,container.firstChild);
        }else{
            console.log('cannot find a place to fit button');
        }
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
      \"NewFile\", param);
    }
    
    function init()
    {
         window.PostContent = PostContent;
    }
";
        echo $content;
        die();
    }

}