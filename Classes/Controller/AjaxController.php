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
use TYPO3\CMS\Core\Utility\PathUtility;

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
//use \TYPO3\CMS\Core\Utility\GeneralUtility;






//ExtensionManagementUtility::addPageTSConfig('
//    @import "EXT:styling_cockpit/Configuration/page.tsconfig"
//');



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



        // ------------- works dont change----------------------------

        // getting the background colors for all elements
        $rootPageID = 251;      // TODO
        // setting path to fileadmin path of extension
        $path = dirname(__DIR__, 5) . "/fileadmin/typo3_template_baukasten/" . $rootPageID . "_";     // TODO include HP1/2

        // if the file doesn't exist we use the path to typo3_template_baukasten instead (=default)
        if (!file_exists( $path . "header.css")){
            $path = dirname(__DIR__, 3) . "/typo3_template_baukasten/Resources/Public/Css/";    // TODO include HP1/2
        }

        // header
        $fileString = file_get_contents($path . "header.css");
        $colorStart = $this->getStart($fileString, "div.site-header");
        $colorEnd = $this->getEnd($fileString, $colorStart);

        $headerColor = substr($fileString, $colorStart, $colorEnd - $colorStart);


        // footer
        $fileString = file_get_contents($path . "footer.css");
        $colorStart = $this->getStart($fileString, "div.site-footer");
        $colorEnd = $this->getEnd($fileString, $colorStart);

        $footerColor = substr($fileString, $colorStart, $colorEnd - $colorStart);

        // ----------doesnt work yet-------------------------------
        $map = array();

        // hp2
        $fileString = file_get_contents($path . "homepage2.css");
        $colorLeftStart = $this->getStart($fileString, "div .hp2_links");
        $colorLeftEnd = $this->getEnd($fileString, $colorLeftStart);
        $map["homepage2_homepage2_main1"] = substr($fileString, $colorLeftStart, $colorLeftEnd - $colorLeftStart);

        $colorRightStart = $this->getStart($fileString, "div .hp2_rechts");
        $colorRightEnd = $this->getEnd($fileString, $colorRightStart);
        $map["homepage2_homepage2_main2"] = substr($fileString, $colorRightStart, $colorRightEnd - $colorRightStart);


        // hp1
        $fileString = file_get_contents($path . "homepage1.css");
        $colorTopStart = $this->getStart($fileString, "div .hp1_oben");
        $colorTopEnd = $this->getEnd($fileString, $colorTopStart);
        $map["homepage1_homepage1_main1"] = substr($fileString, $colorTopStart, $colorTopEnd - $colorTopStart);

        $colorRightStart = $this->getStart($fileString, "div .hp1_unten_rechts");
        $colorRightEnd = $this->getEnd($fileString, $colorRightStart);
        $map["homepage1_homepage1_main2"] = substr($fileString, $colorRightStart, $colorRightEnd - $colorRightStart);

        $colorLeftStart = $this->getStart($fileString, "div .hp1_unten_links");
        $colorLeftEnd = $this->getEnd($fileString, $colorLeftStart);
        $map["homepage1_homepage1_main3"] = substr($fileString, $colorLeftStart, $colorLeftEnd - $colorLeftStart);

        // backend layouts
        $fileString = file_get_contents($path . "normal.css");
        // 1 column
        $color_1Column_Start = $this->getStart($fileString, ".einspaltig_content");
        $color_1Column_End = $this->getEnd($fileString, $color_1Column_Start);
        $map["1spaltig_normal"] = substr($fileString, $color_1Column_Start, $color_1Column_End - $color_1Column_Start);

        // 50-50
        $_50_50_left_start = $this->getStart($fileString, ".main_50-50_left");
        $_50_50_left_end = $this->getEnd($fileString, $_50_50_left_start);
        $map["2Spalten-50-50_main_links"] = substr($fileString, $_50_50_left_start, $_50_50_left_end - $_50_50_left_start);

        $_50_50_right_start = $this->getStart($fileString, ".main_50-50_right");
        $_50_50_right_end = $this->getEnd($fileString, $_50_50_right_start);
        $map["2Spalten-50-50_main_rechts"] = substr($fileString, $_50_50_right_start, $_50_50_right_end - $_50_50_right_start);

        // 30-70
        $_30_70_left_start = $this->getStart($fileString, ".main_30-70_left");
        $_30_70_left_end = $this->getEnd($fileString, $_30_70_left_start);
        $map["2Spalten-30-70_main_links"] = substr($fileString, $_30_70_left_start, $_30_70_left_end - $_30_70_left_start);

        $_30_70_right_start = $this->getStart($fileString, ".main_30-70_right");
        $_30_70_right_end = $this->getEnd($fileString, $_30_70_right_start);
        $map["2Spalten-30-70_main_rechts"] = substr($fileString, $_30_70_right_start, $_30_70_right_end - $_30_70_right_start);

        // 70-30
        $_70_30_left_start = $this->getStart($fileString, ".main_70-30_left");
        $_70_30_left_end = $this->getEnd($fileString, $_70_30_left_start);
        $map["2Spalten-70-30_main_links"] = substr($fileString, $_70_30_left_start, $_70_30_left_end - $_70_30_left_start);

        $_70_30_right_start = $this->getStart($fileString, ".main_70-30_right");
        $_70_30_right_end = $this->getEnd($fileString, $_70_30_right_start);
        $map["2Spalten-70-30_main_rechts"] = substr($fileString, $_70_30_right_start, $_70_30_right_end - $_70_30_right_start);

        // -----------------------------------------





        $layouts = BackendUtility::getPagesTSconfig(1)["mod."]["web_layout."]["BackendLayouts."];

        $homepageOptions = [];
        $gridOptions = [];

        foreach($layouts as $key => $value) {
            if (str_contains($key, "homepage")) {
                array_push($homepageOptions, explode(".", $key)[0]);
            } else {
                array_push($gridOptions, explode(".", $key)[0]);
            }

            /*
            foreach ($value["config."]["backend_layout."]["rows."] as $layout) {
                $mar = explode("-", $key);
                $zahl = 1;

                foreach ($layout["columns."] as $sub) {
                    $a = (count($layout["columns."]) !== 1) ? 'display: inline-block;' : '';
                    $b = (count($mar) == 1) ? 1 : ((int)$mar[$zahl++]) / 100;
                    $c = (str_contains($key, "homepage")) ? 1 / count($layout["columns."]) : $b;


                    $testKilian = end(explode(".", $sub['name']));

                    if ($testKilian == "header") {
                        $testLayout .= "<div id='".$keyus."_header' name='header' onclick='onClick(this);' style='height: 20%; width:100%; border: 1px solid black; background-color: ".$headerColor."'>header</div>";
                    } else if ($testKilian == "footer") {
                        $testLayout .= "<div id='".$keyus."footer' name='footer' onclick='onClick(this);' style='height: 20%; width:100%; border: 1px solid black; background-color: ".$footerColor."'>footer</div>";
                    } else {
                        $testLayout .= "<div id='".$keyus."_".$testKilian."' name=".$keyus."_".$testKilian." onclick='onClick(this);' style='height:". 60 / $heightCounter."%;width:". 100 * $c ."%; border: 1px solid black;".$a."'>".$testKilian."</div>";
            */
               

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
                            $layoutContainer .= "<div id='".$layoutName."_header' name='header_".$homepage."' onclick='onClick(this);' style='height: 20%; width:100%; border: 1px solid black; background-color: " . $headerColor . "'>header</div>";
                        } else if ($gridName == "footer") {
                            $layoutContainer .= "<div id='".$layoutName."footer' name='footer_".$homepage."' onclick='onClick(this);' style='height: 20%; width:100%; border: 1px solid black; background-color: " . $footerColor . "'>footer</div>";
                        } else {
                            $layoutContainer .= "<div id='".$layoutName."_".$gridName."' name=".$layoutName."_".$gridName." onclick='onClick(this);' style='height:". 60 / $heightCounter."%;width:". 100 * $c ."%; border: 1px solid black;".$a." background-color: " . $map[$layoutName."_".$gridName] ."'>".$gridName."</div>";

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
        //get the ajax inputs
        $colorArray = $request->getQueryParams()['colorArray'] ?? null;

        // split all the colors from the colorArray
//        $headerColor = $colorArray[0][1];
//        $footerColor = $colorArray[1][1];


//        $outputString = $colorArray[1][0] . $colorArray[1][1];;

        // -----------------

        $map = array();
        foreach ($colorArray as $column){
            $map[$column[0]] = $column[1];
        }


        $headerColor = $map['header_homepage1'];    // TODO
        $footerColor = $map['footer_homepage1'];    // TODO
        $hp2Main_left_ContentColor = $map['homepage2_homepage2_main1']; // TODO
        $hp2Main_right_ContentColor = $map['homepage2_homepage2_main2']; // TODO
        $hp1Main_top_ContentColor = $map['homepage1_homepage1_main1']; // TODO
        $hp1Main_left_ContentColor = $map['homepage1_homepage1_main2']; // TODO
        $hp1Main_right_ContentColor = $map['homepage1_homepage1_main3']; // TODO
        $_1ColumnContentColor = $map['1spaltig_normal']; // TODO
        $_50_50_left_ContentColor = $map['2Spalten-50-50_main_links']; // TODO
        $_50_50_right_ContentColor = $map['2Spalten-50-50_main_rechts']; // TODO
        $_30_70_left_ContentColor = $map['2Spalten-30-70_main_links']; // TODO
        $_30_70_right_ContentColor = $map['2Spalten-30-70_main_rechts']; // TODO
        $_70_30_left_ContentColor = $map['2Spalten-70-30_main_links']; // TODO
        $_70_30_right_ContentColor = $map['2Spalten-70-30_main_rechts']; // TODO
        // -----------------


//        $headerColor = "whitesmoke";    // TODO
//        $footerColor = "whitesmoke";    // TODO
//        $hp2Main_left_ContentColor = "#fafafa"; // TODO
//        $hp2Main_right_ContentColor = "#fafafa"; // TODO
//        $hp1Main_top_ContentColor = "#fafafa"; // TODO
//        $hp1Main_left_ContentColor = "#fafafa"; // TODO
//        $hp1Main_right_ContentColor = "#fafafa"; // TODO
//        $_1ColumnContentColor = "#fafafa"; // TODO
//        $_50_50_left_ContentColor = "#fafafa"; // TODO
//        $_50_50_right_ContentColor = "#fafafa"; // TODO
//        $_30_70_left_ContentColor = "#fafafa"; // TODO
//        $_30_70_right_ContentColor = "#fafafa"; // TODO
//        $_70_30_left_ContentColor = "#fafafa"; // TODO
//        $_70_30_right_ContentColor = "#fafafa"; // TODO


        // get the root page id
        $rootPageID = 251;     // TODO
        // initialize the fileadmin path
        $path = dirname(__DIR__, 5) . "/fileadmin/typo3_template_baukasten/" . $rootPageID;     // TODO include HP1/2




        if (!file_exists( $path . "_header.css")){
            // set the path to the original templates
            $templatePath = dirname(__DIR__, 3) . "/typo3_template_baukasten/Resources/Public/Css/";    // TODO include HP1/2


            // -----------
            // All elements are edited the same way
            // 1. get content from original template
            // 2. search where the background color starts & ends
            // 3. create a file in fileadmin with the new colorcode
            // backend layout colors are treated as a collective to get better I/O performance
            // -----------
            // header
            $fileString = file_get_contents($templatePath . "header.css");
            $colorStart = $this->getStart($fileString, "div.site-header");
            $colorEnd = $this->getEnd($fileString, $colorStart);

            $editedFileString = substr($fileString, 0, $colorStart) . $headerColor . substr($fileString, $colorEnd);
            file_put_contents($path . "_header.css", $editedFileString);


            // footer
            $fileString = file_get_contents($templatePath . "footer.css");
            $colorStart = $this->getStart($fileString, "div.site-footer");
            $colorEnd = $this->getEnd($fileString, $colorStart);

            $editedFileString = substr($fileString, 0, $colorStart) . $footerColor . substr($fileString, $colorEnd);
            file_put_contents($path . "_footer.css", $editedFileString);


            // HP1 Main Content
            $fileString = file_get_contents($templatePath . "homepage1.css");
            $colorTopStart = $this->getStart($fileString, "div .hp1_oben");
            $colorTopEnd = $this->getEnd($fileString, $colorTopStart);

            $colorLeftStart = $this->getStart($fileString, "div .hp1_unten_links");
            $colorLeftEnd = $this->getEnd($fileString, $colorLeftStart);

            $colorRightStart = $this->getStart($fileString, "div .hp1_unten_rechts");
            $colorRightEnd = $this->getEnd($fileString, $colorRightStart);

            $editedFileString = substr($fileString, 0, $colorTopStart) . $hp1Main_top_ContentColor . substr($fileString, $colorTopEnd);
            $editedFileString = substr($editedFileString, 0, $colorLeftStart) . $hp1Main_left_ContentColor . substr($fileString, $colorLeftEnd);
            $editedFileString = substr($editedFileString, 0, $colorRightStart) . $hp1Main_right_ContentColor . substr($fileString, $colorRightEnd);
            file_put_contents($path . "_homepage1.css", $editedFileString);


            // HP2 Main Content
            $fileString = file_get_contents($templatePath . "homepage2.css");
            $colorLeftStart = $this->getStart($fileString, "div .hp2_links");
            $colorLeftEnd = $this->getEnd($fileString, $colorLeftStart);

            $fileString = file_get_contents($templatePath . "homepage2.css");
            $colorRightStart = $this->getStart($fileString, "div .hp2_rechts");
            $colorRightEnd = $this->getEnd($fileString, $colorRightStart);

            $editedFileString = substr($fileString, 0, $colorLeftStart) . $hp2Main_left_ContentColor . substr($fileString, $colorLeftEnd);
            $editedFileString= substr($editedFileString, 0, $colorRightStart) . $hp2Main_right_ContentColor . substr($fileString, $colorRightEnd);
            file_put_contents($path . "_homepage2.css", $editedFileString);


            // ----- backend layout -----
            // get file content
            $fileString = file_get_contents($templatePath . "normal.css");

            // 1 column
            $_1ColumnStart = $this->getStart($fileString, ".einspaltig_content");
            $_1ColumnEnd = $this->getEnd($fileString, $_1ColumnStart);

            // 50-50
            $_50_50_left_start = $this->getStart($fileString, ".main_50-50_left");
            $_50_50_left_end = $this->getEnd($fileString, $_50_50_left_start);

            $_50_50_right_start = $this->getStart($fileString, ".main_50-50_right");
            $_50_50_right_end = $this->getEnd($fileString, $_50_50_right_start);

            // 30-70
            $_30_70_left_start = $this->getStart($fileString, ".main_30-70_left");
            $_30_70_left_end = $this->getEnd($fileString, $_30_70_left_start);

            $_30_70_right_start = $this->getStart($fileString, ".main_30-70_right");
            $_30_70_right_end = $this->getEnd($fileString, $_30_70_right_start);

            // 70-30
            $_70_30_left_start = $this->getStart($fileString, ".main_70-30_left");
            $_70_30_left_end = $this->getEnd($fileString, $_70_30_left_start);

            $_70_30_right_start = $this->getStart($fileString, ".main_70-30_right");
            $_70_30_right_end = $this->getEnd($fileString, $_70_30_right_start);

            // construct the output string (there is probably a better way to do this if there's a method to specify substr between 2 ints)
            $editedFileString = substr($fileString, 0, $_1ColumnStart) . $_1ColumnContentColor . substr($fileString, $_1ColumnEnd);
            $editedFileString = substr($editedFileString, 0, $_50_50_left_start) . $_50_50_left_ContentColor . substr($fileString, $_50_50_left_end);
            $editedFileString = substr($editedFileString, 0, $_50_50_right_start) . $_50_50_right_ContentColor . substr($fileString, $_50_50_right_end);
            $editedFileString = substr($editedFileString, 0, $_30_70_left_start) . $_30_70_left_ContentColor . substr($fileString, $_30_70_left_end);
            $editedFileString = substr($editedFileString, 0, $_30_70_right_start) . $_30_70_right_ContentColor . substr($fileString, $_30_70_right_end);
            $editedFileString = substr($editedFileString, 0, $_70_30_left_start) . $_70_30_left_ContentColor . substr($fileString, $_70_30_left_end);
            $editedFileString = substr($editedFileString, 0, $_70_30_right_start) . $_70_30_right_ContentColor . substr($fileString, $_70_30_right_end);

            // write file to fileadmin
            file_put_contents($path . "_normal.css", $editedFileString);
        }else{

            // -----------
            // All elements are edited the same way
            // 1. get content from fileadmin template
            // 2. search where the background color starts & ends
            // 3. edit file in fileadmin with the new colorcode
            // backend layout colors are treated as a collective to get better I/O performance
            // -----------
            // header
            $fileString = file_get_contents($path . "_header.css");
            $colorStart = $this->getStart($fileString, "div.site-header");
            $colorEnd = $this->getEnd($fileString, $colorStart);

            $editedFileString = substr($fileString, 0, $colorStart) . $headerColor . substr($fileString, $colorEnd);
            file_put_contents($path . "_header.css", $editedFileString);


            // footer
            $fileString = file_get_contents($path . "_footer.css");
            $colorStart = $this->getStart($fileString, "div.site-footer");
            $colorEnd = $this->getEnd($fileString, $colorStart);

            $editedFileString = substr($fileString, 0, $colorStart) . $footerColor . substr($fileString, $colorEnd);
            file_put_contents($path . "_footer.css", $editedFileString);


            // HP1 Main Content
            $fileString = file_get_contents($path . "_homepage1.css");
            $colorTopStart = $this->getStart($fileString, "div .hp1_oben");
            $colorTopEnd = $this->getEnd($fileString, $colorTopStart);

            $colorLeftStart = $this->getStart($fileString, "div .hp1_unten_links");
            $colorLeftEnd = $this->getEnd($fileString, $colorLeftStart);

            $colorRightStart = $this->getStart($fileString, "div .hp1_unten_rechts");
            $colorRightEnd = $this->getEnd($fileString, $colorRightStart);

            $editedFileString = substr($fileString, 0, $colorTopStart) . $hp1Main_top_ContentColor . substr($fileString, $colorTopEnd);
            $editedFileString = substr($editedFileString, 0, $colorLeftStart) . $hp1Main_left_ContentColor . substr($fileString, $colorLeftEnd);
            $editedFileString = substr($editedFileString, 0, $colorRightStart) . $hp1Main_right_ContentColor . substr($fileString, $colorRightEnd);
            file_put_contents($path . "_homepage1.css", $editedFileString);


            // HP2 Main Content
            $fileString = file_get_contents($path . "_homepage2.css");
            $colorLeftStart = $this->getStart($fileString, "div .hp2_links");
            $colorLeftEnd = $this->getEnd($fileString, $colorLeftStart);

            $colorRightStart = $this->getStart($fileString, "div .hp2_rechts");
            $colorRightEnd = $this->getEnd($fileString, $colorRightStart);

            $editedFileString = substr($fileString, 0, $colorLeftStart) . $hp2Main_left_ContentColor . substr($fileString, $colorLeftEnd);
            $editedFileString= substr($editedFileString, 0, $colorRightStart) . $hp2Main_right_ContentColor . substr($fileString, $colorRightEnd);
            file_put_contents($path . "_homepage2.css", $editedFileString);


            // ----- backend layout -----
            // get file content
            $fileString = file_get_contents($path . "_normal.css");

            // 1 column
            $_1ColumnStart = $this->getStart($fileString, ".einspaltig_content");
            $_1ColumnEnd = $this->getEnd($fileString, $_1ColumnStart);

            // 50-50
            $_50_50_left_start = $this->getStart($fileString, ".main_50-50_left");
            $_50_50_left_end = $this->getEnd($fileString, $_50_50_left_start);

            $_50_50_right_start = $this->getStart($fileString, ".main_50-50_right");
            $_50_50_right_end = $this->getEnd($fileString, $_50_50_right_start);

            // 30-70
            $_30_70_left_start = $this->getStart($fileString, ".main_30-70_left");
            $_30_70_left_end = $this->getEnd($fileString, $_30_70_left_start);

            $_30_70_right_start = $this->getStart($fileString, ".main_30-70_right");
            $_30_70_right_end = $this->getEnd($fileString, $_30_70_right_start);

            // 70-30
            $_70_30_left_start = $this->getStart($fileString, ".main_70-30_left");
            $_70_30_left_end = $this->getEnd($fileString, $_70_30_left_start);

            $_70_30_right_start = $this->getStart($fileString, ".main_70-30_right");
            $_70_30_right_end = $this->getEnd($fileString, $_70_30_right_start);

            // construct the output string (there is probably a better way to do this if there's a method to specify substr between 2 ints)
            $editedFileString = substr($fileString, 0, $_1ColumnStart) . $_1ColumnContentColor . substr($fileString, $_1ColumnEnd);
            $editedFileString = substr($editedFileString, 0, $_50_50_left_start) . $_50_50_left_ContentColor . substr($fileString, $_50_50_left_end);
            $editedFileString = substr($editedFileString, 0, $_50_50_right_start) . $_50_50_right_ContentColor . substr($fileString, $_50_50_right_end);
            $editedFileString = substr($editedFileString, 0, $_30_70_left_start) . $_30_70_left_ContentColor . substr($fileString, $_30_70_left_end);
            $editedFileString = substr($editedFileString, 0, $_30_70_right_start) . $_30_70_right_ContentColor . substr($fileString, $_30_70_right_end);
            $editedFileString = substr($editedFileString, 0, $_70_30_left_start) . $_70_30_left_ContentColor . substr($fileString, $_70_30_left_end);
            $editedFileString = substr($editedFileString, 0, $_70_30_right_start) . $_70_30_right_ContentColor . substr($fileString, $_70_30_right_end);

            // write file to fileadmin
            file_put_contents($path . "_normal.css", $editedFileString);
        }


        $data = ['result' => $map];
        return $this->jsonResponse(json_encode($data));
    }

    /**
     * This function searches for the index where the specified selector starts in the css file
     * @param String $fileString    content string of the file to search
     * @param String $needle        specifies the selector to find
     * @return false|int            returns the start position of the selector in the string
     */
    public function getStart(String $fileString, String $needle){
        $start = strpos($fileString, $needle);
        $colorSelector = strpos($fileString, "background-color:", $start);
        $colorStart = strpos($fileString, " ", $colorSelector)+1;

        return $colorStart;
    }

    /**
     * This function searches for the index where the "background-color" property ends in the css file
     * (this doesn't have to be the end index of the selector because the rest of the file remains unchanged)
     * @param String $fileString    content string of the file to search
     * @param int $start            the index where to start the search
     * @return false|int            returns the end position of the property in the string
     */
    public function getEnd(String $fileString, int $start){
        return strpos($fileString, ";", $start);
    }
}
