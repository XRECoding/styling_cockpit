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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\ResourceFactory;

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

        $test = "homepage1";
        $homepageArray = array();
        $gridArray = array();

        $homepageOptions = array();
        $gridOptions = array();



        $layouts = BackendUtility::getPagesTSconfig(1)["mod."]["web_layout."]["BackendLayouts."];

        foreach($layouts as $key => $value) {
            // echo explode(".", $key)[0]."<br>";
            $keyus = explode(".", $key)[0];

            $testLayout = "<div id='".$keyus."' style='visibility: collapse; padding: 0;'>";
            $heightCounter = count($value["config."]["backend_layout."]["rows."]) -2;

            if (!str_contains($key, "homepage")) {
                $testLayout .= "<div id='".$keyus."_header' name='header' onclick='alert();' style='height: 20%; width:100%; border: 1px solid black; background-image: linear-gradient(to bottom right,  transparent calc(50% - 1px), black, transparent calc(50% + 1px));'>header</div>";
                $heightCounter += 2;
                array_push($gridOptions, $keyus);
            } else {
                array_push($homepageOptions, $keyus);
            }

            foreach ($value["config."]["backend_layout."]["rows."] as $layout) {
                $mar = explode("-", $key);
                $zahl = 1;

                foreach ($layout["columns."] as $sub) {
                    $a = (count($layout["columns."]) !== 1) ? 'display: inline-block;' : '';
                    $b = (count($mar) == 1) ? 1 : ((int)$mar[$zahl++]) / 100;
                    $c = (str_contains($key, "homepage")) ? 1 / count($layout["columns."]) : $b;


                    $testKilian = end(explode(".", $sub['name']));

                    if ($testKilian == "header") {
                        $testLayout .= "<div id='".$keyus."_header' name='header' onclick='onClick(this);' style='height: 20%; width:100%; border: 1px solid black'>header</div>";
                    } else if ($testKilian == "footer") {
                        $testLayout .= "<div id='".$keyus."footer' name='footer' onclick='onClick(this);' style='height: 20%; width:100%; border: 1px solid black'>footer</div>";
                    } else {
                        $testLayout .= "<div id='".$keyus."_".$testKilian."' name=".$keyus."_".$testKilian." onclick='onClick(this);' style='height:". 60 / $heightCounter."%;width:". 100 * $c ."%; border: 1px solid black;".$a."'>".$testKilian."</div>";
                    }
                }

            }

            if (!str_contains($key, "homepage")) {
                $testLayout .= "<div id='".$keyus."_footer' name='footer' onclick='alert();' style='height: 20%; width:100%; border: 1px solid black; background-image: linear-gradient(to bottom right,  transparent calc(50% - 1px), black, transparent calc(50% + 1px));'>footer</div>";
            }
            $testLayout .= "</div>";

            if (!str_contains($key, "homepage")) {
                array_push($gridArray, $testLayout);
            } else {
                array_push($homepageArray, $testLayout);
            }

        }
        $this->view->assign("homepageArray", $homepageArray);
        $this->view->assign("gridArray", $gridArray);
        $this->view->assign("homepageOptions", $homepageOptions);
        $this->view->assign("gridOptions", $gridOptions);



        // ******************************************************
        // getting the background color for div elements
        // TODO change filepath from absolute to relative
        // header
        $fileString = file_get_contents("D://Dokumente/XAMPP/htdocs/Studienprojekt/typo3conf/ext/styling_cockpit/Resources/Public/Css/header.css");
        $headerStart = strpos($fileString, "div.site-header");
        $colorSelector = strpos($fileString, "background-color:", $headerStart);
        $colorStart = strpos($fileString, " ", $colorSelector)+1;
        $colorEnd = strpos($fileString, ";", $colorStart);

        $headerColor = substr($fileString, $colorStart, $colorEnd - $colorStart);

        //footer
        $fileString = file_get_contents("D://Dokumente/XAMPP/htdocs/Studienprojekt/typo3conf/ext/styling_cockpit/Resources/Public/Css/footer.css");
        $footerStart = strpos($fileString, "div.site-footer");
        $colorSelector = strpos($fileString, "background-color:", $footerStart);
        $colorStart = strpos($fileString, " ", $colorSelector)+1;
        $colorEnd = strpos($fileString, ";", $colorStart);

        $footerColor = substr($fileString, $colorStart, $colorEnd - $colorStart);

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

        /*
        $storageRepository = GeneralUtility::makeInstance(StorageRepository::class);
        $defaultStorage = $storageRepository->getDefaultStorage();
        $folder = $defaultStorage->getFolder('EXT:styling_cockpit/resources/public/css');
        $file = $folder->getStorage()->getFileInFolder("test.txt", $folder);

        fwrite($file,"test");
        fclose($file);
        */

        // works but cant use absolute path
        /*
        $file = fopen("D://Dokumente/XAMPP/htdocs/Studienprojekt/typo3conf/ext/styling_cockpit/Resources/Public/Css/header.css", "r");
        fwrite($file, "test");
        fclose($file);
        */

        // TODO change filepath from absolute to relative
        //edit header background color
        $fileString = file_get_contents("D://Dokumente/XAMPP/htdocs/Studienprojekt/typo3conf/ext/styling_cockpit/Resources/Public/Css/header.css");
        $headerStart = strpos($fileString, "div.site-header");
        $colorSelector = strpos($fileString, "background-color:", $headerStart);
        $colorStart = strpos($fileString, " ", $colorSelector)+1;
        $colorEnd = strpos($fileString, ";", $colorStart);

        $file = fopen("D://Dokumente/XAMPP/htdocs/Studienprojekt/typo3conf/ext/styling_cockpit/Resources/Public/Css/test.txt", "w");
        fwrite($file, substr($fileString, 0, $colorStart));
        fwrite($file, "color");     // TODO
        fwrite($file, substr($fileString, $colorEnd));
        fclose($file);

        //edit footer background color
        $fileString = file_get_contents("D://Dokumente/XAMPP/htdocs/Studienprojekt/typo3conf/ext/styling_cockpit/Resources/Public/Css/footer.css");
        $footerStart = strpos($fileString, "div.site-footer");
        $colorSelector = strpos($fileString, "background-color:", $footerStart);
        $colorStart = strpos($fileString, " ", $colorSelector)+1;
        $colorEnd = strpos($fileString, ";", $colorStart);

        $file = fopen("D://Dokumente/XAMPP/htdocs/Studienprojekt/typo3conf/ext/styling_cockpit/Resources/Public/Css/test.txt", "w");
        fwrite($file, substr($fileString, 0, $colorStart));
        fwrite($file, "color");     // TODO
        fwrite($file, substr($fileString, $colorEnd));
        fclose($file);





        $data = ['result' => 'pls work'];
        return $this->jsonResponse(json_encode($data));
    }
}
