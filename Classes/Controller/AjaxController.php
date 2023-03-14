<?php

declare(strict_types=1);

namespace Zimk\stylingcockpit\Controller;


/**
 * This file is part of the "Styling Cockpit" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022 Daniel Kuhn
 */

use TYPO3\CMS\Backend\Utility\BackendUtility;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;






ExtensionManagementUtility::addPageTSConfig('
    @import "EXT:styling_cockpit/Configuration/page.tsconfig"
');


/**
 * AjaxController
 */
class AjaxController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * action list
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(): \Psr\Http\Message\ResponseInterface
    {
        $PageTSconfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($this->pObj->id);
        $websiteID = $PageTSconfig['TSFE.']['constants.']['websiteConfig.'];

        // $this->view->assign('pageTsConfig', BackendUtility::getPagesTSconfig(1)['mod.']['web_layout.']);
        // $this->view->assign('rootLine', $rootlinePages);

        // ******************************************************


        $layouts = BackendUtility::getPagesTSconfig(1)["mod."]["web_layout."]["BackendLayouts."];

        $homepageOptions = [];
        $gridOptions = [];

        foreach($layouts as $key => $value) {
            if (str_contains($key, "homepage")) {
                array_push($homepageOptions, explode(".", $key)[0]);
            } else {
                array_push($gridOptions, explode(".", $key)[0]);
            }
        }
        


        $homepageArray = [];
        $gridArray = [];

        foreach ($homepageOptions as $homepage) {
            foreach($layouts as $key => $value) {
                $layoutName = explode(".", $key)[0];
                $homepageID = (!strcmp($layoutName, $homepage)) ? $layoutName : $layoutName."_".$homepage;
   
                $layoutContainer = "<div id='".$homepageID."' style='visibility: collapse; padding: 0;'>";
                $heightCounter = count($value["config."]["backend_layout."]["rows."]) -2;
    
                if (!str_contains($key, "homepage")) {
                    $layoutContainer .= "<div id='".$layoutName."_header' name='header_".$homepage."' onclick='alert();' style='height: 20%; width:100%; border: 1px solid black; background-image: linear-gradient(to bottom right,  transparent calc(50% - 1px), black, transparent calc(50% + 1px));'>header</div>";
                    $heightCounter += 2;
                } 
    
                foreach ($value["config."]["backend_layout."]["rows."] as $layout) {
                    $mar = explode("-", $key);
                    $zahl = 1;
    
                    foreach ($layout["columns."] as $sub) {
                        $a = (count($layout["columns."]) !== 1) ? 'display: inline-block;' : '';
                        $b = (count($mar) == 1) ? 1 : ((int)$mar[$zahl++]) / 100;
                        $c = (str_contains($key, "homepage")) ? 1 / count($layout["columns."]) : $b;
    
    
                        $gridName = end(explode(".", $sub['name']));
    
                        if ($gridName == "header") {
                            $layoutContainer .= "<div id='".$layoutName."_header' name='header_".$homepage."' onclick='onClick(this);' style='height: 20%; width:100%; border: 1px solid black'>header</div>";
                        } else if ($gridName == "footer") {
                            $layoutContainer .= "<div id='".$layoutName."footer' name='footer_".$homepage."' onclick='onClick(this);' style='height: 20%; width:100%; border: 1px solid black'>footer</div>";
                        } else {
                            $layoutContainer .= "<div id='".$layoutName."_".$gridName."' name=".$layoutName."_".$gridName." onclick='onClick(this);' style='height:". 60 / $heightCounter."%;width:". 100 * $c ."%; border: 1px solid black;".$a."'>".$gridName."</div>";
                        }
                    }
    
                }
    
                if (!str_contains($key, "homepage")) {
                    $layoutContainer .= "<div id='".$layoutName."_footer' name='footer_".$homepage."' onclick='alert();' style='height: 20%; width:100%; border: 1px solid black; background-image: linear-gradient(to bottom right,  transparent calc(50% - 1px), black, transparent calc(50% + 1px));'>footer</div>";
                }
    
                if (!str_contains($key, "homepage")) {
                    array_push($gridArray, $layoutContainer.="</div>");
                } else {
                    array_push($homepageArray, $layoutContainer.="</div>");
                }
            }
        }
        
        
        // $pid = intVal($_GET['id']);

        $this->view->assign("homepageArray", $homepageArray);
        $this->view->assign("gridArray", $gridArray);
        $this->view->assign("homepageOptions", $homepageOptions);
        $this->view->assign("gridOptions", $gridOptions);

        return $this->htmlResponse();
    }


    public function doSomethingAction(ServerRequestInterface $request): ResponseInterface
    {

        /*
        $extPath = ExtensionManagementUtility::siteRelPath(
            $this->request->getControllerExtensionKey()
        );

        $extCss = $extPath . 'Resources/Public/Css/test.txt';

        $file = fopen($extCss, "w");
        fwrite($file, "test");
        fclose($file);
        */


        // doesnt work
        /*
        $file = fopen("EXT:styling_cockpit/Resources/Public/Css/test.txt", "w");
        fwrite($file, "test");
        fclose($file);
        */


        // works but cant use absolute path
        /*
        $file = fopen("D://Dokumente/XAMPP/htdocs/Studienprojekt/typo3conf/ext/styling_cockpit/Resources/Public/Css/test.txt", "w");
        fwrite($file, "test");
        fclose($file);
        */


        $data = ['result' => 'my stuff'];
        return $this->jsonResponse(json_encode($data));
    }
}
