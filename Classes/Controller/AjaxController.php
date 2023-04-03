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
use TYPO3\CMS\Core\Utility\RootlineUtility;
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;


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

        // getting current page ID
        $rootPageID = intVal($_GET['id']);
        $this->view->assign("rootPageID", $rootPageID);

        // checking if selected page is root
        $rootline = GeneralUtility::makeInstance(RootlineUtility::class, GeneralUtility::_GP('id'));
        $rootlinePages = $rootline->get();

        $is_siteroot =  array_values($rootlinePages)[0]["is_siteroot"];
        $this->view->assign("is_siteroot", $is_siteroot);

        // setting path to fileadmin path of extension
        $path = dirname(__DIR__, 5) . "/fileadmin/typo3_template_baukasten/" . $rootPageID . "_";     // TODO include HP1/2
        // if the file doesn't exist we use the path to typo3_template_baukasten instead (=default)
        if (!file_exists( $path . "header.css")){
            $path = dirname(__DIR__, 3) . "/typo3_template_baukasten/Resources/Public/Css/";    // TODO include HP1/2
        }



        // font
        $fileString = file_get_contents($path . "header.css");

        $start = strpos($fileString, "body");
        $fontSelector = strpos($fileString, "font-family:", $start);
        $fontStart = strpos($fileString, " ", $fontSelector)+1;

        $fontEnd = strpos($fileString, ";", $fontStart);
        if (str_contains(substr($fileString, $fontStart, $fontEnd - $fontStart), ",")){
            $fontEnd = strpos($fileString, ",", $fontStart);
        }

        $font = substr($fileString, $fontStart, $fontEnd - $fontStart);
        $this->view->assign("selectedFont", $font);

        $this->view->assign("fonts", ["Arial", "Brush Script MT", "Copperplate", "Courier New", "Cursive", "Fantasy",
            "Garamond", "Georgia", "Helvetica", "Lucida Console", "Lucida Handwriting", "Monaco", "Monospace",
            "Papyrus", "Sans-serif", "Serif", "Times New Roman", "Verdana"]);



        // text size
        $fileString = file_get_contents($path . "header.css");

        $start = strpos($fileString, "body");
        $sizeSelector = strpos($fileString, "font-size:", $start);
        $sizeStart = strpos($fileString, " ", $sizeSelector)+1;

        $sizeEnd = strpos($fileString, "px;", $sizeStart);
        $size = substr($fileString, $sizeStart, $sizeEnd - $sizeStart);
        $this->view->assign("selectedSize", $size);

        $this->view->assign("sizes", ["08", "12", "16", "20", "24", "28", "32"]);



        // text alignment
        $fileString = file_get_contents($path . "header.css");

        $start = strpos($fileString, "body");
        $alignSelector = strpos($fileString, "text-align:", $start);
        $alignStart = strpos($fileString, " ", $alignSelector)+1;

        $alignEnd = strpos($fileString, ";", $alignStart);
        $align = substr($fileString, $alignStart, $alignEnd - $alignStart);
        $this->view->assign("selectedAlign", $align);

        $this->view->assign("alignments", ["Left", "Right", "Center", "Justify"]);



        // getting all background colors of all elements and saving them in the map
        // header and footer are excluded because they are constructed separately
        $map = array();
        // header
        $fileString = file_get_contents($path . "header.css");
        $colorStart = $this->getStart($fileString, "div.site-header-hp1");
        $colorEnd = $this->getEnd($fileString, $colorStart);

        $hp1_headerColor = substr($fileString, $colorStart, $colorEnd - $colorStart);

        $colorStart = $this->getStart($fileString, "div.site-header-hp2");
        $colorEnd = $this->getEnd($fileString, $colorStart);

        $hp2_headerColor = substr($fileString, $colorStart, $colorEnd - $colorStart);

        // footer
        $fileString = file_get_contents($path . "footer.css");
        $colorStart = $this->getStart($fileString, "div.site-footer-hp1");
        $colorEnd = $this->getEnd($fileString, $colorStart);

        $hp1_footerColor = substr($fileString, $colorStart, $colorEnd - $colorStart);

        $colorStart = $this->getStart($fileString, "div.site-footer-hp2");
        $colorEnd = $this->getEnd($fileString, $colorStart);

        $hp2_footerColor = substr($fileString, $colorStart, $colorEnd - $colorStart);

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
        $gridOptionsUncut = [];
        $gridOptions = [];

        foreach($layouts as $key => $value) {
            if (str_contains($key, "homepage")) {
                array_push($homepageOptions, explode(".", $key)[0]);
            } else {
                array_push($gridOptionsUncut, explode(".", $key)[0]);
            }
        }


        $gridOptions = array_slice($gridOptionsUncut, 0, 4);
        


        $homepageArray = [];
        $gridArray = [];

        $alertText = "'Die Farbe des Headers kann nur über die Homepage angepasst werden.'";

        foreach ($homepageOptions as $homepage) {
            foreach($layouts as $key => $value) {
                $layoutName = explode(".", $key)[0];
                $homepageID = (!strcmp($layoutName, $homepage)) ? $layoutName : $layoutName."_".$homepage;
   
                $layoutContainer = "<div id='".$homepageID."' style='visibility: collapse; padding: 0;'>";
                $heightCounter = count($value["config."]["backend_layout."]["rows."]) -2;
    
                if (!str_contains($key, "homepage")) {
                    if ($homepage == "homepage1"){
                        $layoutContainer .= "<div id='".$layoutName."_header' name='header_".$homepage."' onclick='alertText()' style='height: 20%; width:100%; border: 1px solid black; background-image: linear-gradient(to bottom right,  transparent calc(50% - 1px), black, transparent calc(50% + 1px)); background-color: " . $hp1_headerColor . "'>header</div>";
                    } else {
                        $layoutContainer .= "<div id='".$layoutName."_header' name='header_".$homepage."' onclick='alertText()' style='height: 20%; width:100%; border: 1px solid black; background-image: linear-gradient(to bottom right,  transparent calc(50% - 1px), black, transparent calc(50% + 1px)); background-color: " . $hp2_headerColor . "'>header</div>";
                    }
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
                            if ($layoutName == "homepage1"){
                                $layoutContainer .= "<div id='".$layoutName."_header' name='header_".$homepage."' onclick='onClick(this);' style='height: 20%; width:100%; border: 1px solid black; background-color: " . $hp1_headerColor . "'>header</div>";
                            } else {
                                $layoutContainer .= "<div id='".$layoutName."_header' name='header_".$homepage."' onclick='onClick(this);' style='height: 20%; width:100%; border: 1px solid black; background-color: " . $hp2_headerColor . "'>header</div>";
                            }

                        } else if ($gridName == "footer") {
                            if ($layoutName == "homepage1"){
                                $layoutContainer .= "<div id='".$layoutName."footer' name='footer_".$homepage."' onclick='onClick(this);' style='height: 20%; width:100%; border: 1px solid black; background-color: " . $hp1_footerColor . "'>footer</div>";
                            } else {
                                $layoutContainer .= "<div id='".$layoutName."footer' name='footer_".$homepage."' onclick='onClick(this);' style='height: 20%; width:100%; border: 1px solid black; background-color: " . $hp2_footerColor . "'>footer</div>";
                            }

                        } else {
                            $layoutContainer .= "<div id='".$layoutName."_".$gridName."' name=".$layoutName."_".$gridName." onclick='onClick(this);' style='height:". 60 / $heightCounter."%;width:". 100 * $c ."%; border: 1px solid black;".$a." background-color: " . $map[$layoutName."_".$gridName] ."'>".$gridName."</div>";

                        }

                    }
    
                }
    
                if (!str_contains($key, "homepage")) {
                    if ($homepage == "homepage1"){
                        $layoutContainer .= "<div id='".$layoutName."_footer' name='footer_".$homepage."' onclick='alertText();' style='height: 20%; width:100%; border: 1px solid black; background-image: linear-gradient(to bottom right,  transparent calc(50% - 1px), black, transparent calc(50% + 1px)); background-color: " . $hp1_footerColor . "'>footer</div>";
                    } else {
                        $layoutContainer .= "<div id='".$layoutName."_footer' name='footer_".$homepage."' onclick='alertText();' style='height: 20%; width:100%; border: 1px solid black; background-image: linear-gradient(to bottom right,  transparent calc(50% - 1px), black, transparent calc(50% + 1px)); background-color: " . $hp2_footerColor . "'>footer</div>";
                    }
                }
    
                if (!str_contains($key, "homepage")) {
                    array_push($gridArray, $layoutContainer.="</div>");
                } else {
                    array_push($homepageArray, $layoutContainer.="</div>");
                }
            }
        }


        $this->view->assign("homepageArray", $homepageArray);
        $this->view->assign("gridArray", $gridArray);
        $this->view->assign("homepageOptions", $homepageOptions);
        $this->view->assign("gridOptions", $gridOptions);


        return $this->htmlResponse();
    }







    /**
     * This function saves the changes made in the styling cockpit to the corresponding css files
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function doSomethingAction(ServerRequestInterface $request): ResponseInterface
    {
        //get the ajax inputs
        $colorArray = $request->getQueryParams()['colorArray'] ?? null;
        $rootPageID = $request->getQueryParams()['pageID'] ?? null;
        $font = $request->getQueryParams()['font'] ?? null;
        $size = $request->getQueryParams()['size'] ?? null;
        $alignment = $request->getQueryParams()['alignment'] ?? null;


        $map = array();
        foreach ($colorArray as $column){
            $map[$column[0]] = $column[1];
        }


        // initialize the fileadmin path
        $path = dirname(__DIR__, 5) . "/fileadmin/typo3_template_baukasten/" . $rootPageID . "_";     // TODO include HP1/2


        // check if the corresponding file already exists in the fileadmin
        // the path for loading / saving changes accordingly
        $loadDefault = !file_exists( $path . "header.css");

        if ($loadDefault){
            // set the path to the original templates
            $path = dirname(__DIR__, 3) . "/typo3_template_baukasten/Resources/Public/Css/";    // TODO include HP1/2
        }

        // -----------
        // All elements are edited the same way
        // 1. get content from current template (either typo3_template_baukasten default or fileadmin template)
        // 2. search where the selectors starts & ends
        // 3. write the variable change into the string
        // 4. output file
        // changes are grouped by file for better I/O performance
        // -----------
        // header
        $fileString = file_get_contents($path . "header.css");

        // font
        $start = strpos($fileString, "body");
        $fontSelector = strpos($fileString, "font-family:", $start);
        $fontStart = strpos($fileString, " ", $fontSelector)+1;
        $fontEnd = strpos($fileString, ";", $fontStart);
        $editedHeaderFileString = substr($fileString, 0, $fontStart) . $font . substr($fileString, $fontEnd);

        // header background color
        $hp1_colorStart = $this->getStart($editedHeaderFileString, "div.site-header-hp1");
        $hp1_colorEnd = $this->getEnd($editedHeaderFileString, $hp1_colorStart);
        $editedHeaderFileString = substr($editedHeaderFileString, 0, $hp1_colorStart) . $map['header_homepage1'] . substr($editedHeaderFileString, $hp1_colorEnd);

        $hp2_colorStart = $this->getStart($editedHeaderFileString, "div.site-header-hp2");
        $hp2_colorEnd = $this->getEnd($editedHeaderFileString, $hp2_colorStart);
        $editedHeaderFileString = substr($editedHeaderFileString, 0, $hp2_colorStart) . $map['header_homepage2'] . substr($editedHeaderFileString, $hp2_colorEnd);

        $colorStart = $this->getStart($editedHeaderFileString, "div.site-header");
        $colorEnd = $this->getEnd($editedHeaderFileString, $colorStart);
        $editedHeaderFileString = substr($editedHeaderFileString, 0, $colorStart) . $map['header_homepage2'] . substr($editedHeaderFileString, $colorEnd);

        // size
        $sizeSelector = strpos($editedHeaderFileString, "font-size:", $start);
        $sizeStart = strpos($editedHeaderFileString, " ", $sizeSelector)+1;
        $sizeEnd = strpos($editedHeaderFileString, "px;", $sizeStart);
        $editedHeaderFileString = substr($editedHeaderFileString, 0, $sizeStart) . $size . substr($editedHeaderFileString, $sizeEnd);

        // size
        $alignSelector = strpos($editedHeaderFileString, "text-align:", $start);
        $alignStart = strpos($editedHeaderFileString, " ", $alignSelector)+1;
        $alignEnd = strpos($editedHeaderFileString, ";", $alignStart);
        $editedHeaderFileString = substr($editedHeaderFileString, 0, $alignStart) . $alignment . substr($editedHeaderFileString, $alignEnd);



        // footer
        $fileString = file_get_contents($path . "footer.css");
        // background color
        $hp1_colorStart = $this->getStart($fileString, "div.site-footer-hp1");
        $hp1_colorEnd = $this->getEnd($fileString, $hp1_colorStart);
        $editedFooterFileString = substr($fileString, 0, $hp1_colorStart) . $map['footer_homepage1'] . substr($fileString, $hp1_colorEnd);

        $hp2_colorStart = $this->getStart($editedFooterFileString, "div.site-footer-hp2");
        $hp2_colorEnd = $this->getEnd($editedFooterFileString, $hp2_colorStart);
        $editedFooterFileString = substr($editedFooterFileString, 0, $hp2_colorStart) . $map['footer_homepage2'] . substr($editedFooterFileString, $hp2_colorEnd);

        $colorStart = $this->getStart($editedFooterFileString, "div.site-footer");
        $colorEnd = $this->getEnd($editedFooterFileString, $colorStart);
        $editedFooterFileString = substr($fileString, 0, $colorStart) . $map['footer_homepage2'] . substr($editedFooterFileString, $colorEnd);



        // HP1 Main Content
        $fileString = file_get_contents($path . "homepage1.css");
        // background color
        $colorTopStart = $this->getStart($fileString, "div .hp1_oben");
        $colorTopEnd = $this->getEnd($fileString, $colorTopStart);

        $colorLeftStart = $this->getStart($fileString, "div .hp1_unten_links");
        $colorLeftEnd = $this->getEnd($fileString, $colorLeftStart);

        $colorRightStart = $this->getStart($fileString, "div .hp1_unten_rechts");
        $colorRightEnd = $this->getEnd($fileString, $colorRightStart);

        $editedHP1FileString = substr($fileString, 0, $colorTopStart) . $map['homepage1_homepage1_main1'] . substr($fileString, $colorTopEnd);
        $editedHP1FileString = substr($editedHP1FileString, 0, $colorLeftStart) . $map['homepage1_homepage1_main2'] . substr($fileString, $colorLeftEnd);
        $editedHP1FileString = substr($editedHP1FileString, 0, $colorRightStart) . $map['homepage1_homepage1_main3'] . substr($fileString, $colorRightEnd);



        // HP2 Main Content
        $fileString = file_get_contents($path . "homepage2.css");
        // background color
        $colorLeftStart = $this->getStart($fileString, "div .hp2_links");
        $colorLeftEnd = $this->getEnd($fileString, $colorLeftStart);

//        $fileString = file_get_contents($path . "homepage2.css");
        $colorRightStart = $this->getStart($fileString, "div .hp2_rechts");
        $colorRightEnd = $this->getEnd($fileString, $colorRightStart);

        $editedHP2FileString = substr($fileString, 0, $colorLeftStart) . $map['homepage2_homepage2_main1'] . substr($fileString, $colorLeftEnd);
        $editedHP2FileString= substr($editedHP2FileString, 0, $colorRightStart) . $map['homepage2_homepage2_main2'] . substr($fileString, $colorRightEnd);



        // ----- backend layout -----
        $fileString = file_get_contents($path . "normal.css");
        // background color
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
        $editedNormalFileString = substr($fileString, 0, $_1ColumnStart) . $map['1spaltig_normal'] . substr($fileString, $_1ColumnEnd);
        $editedNormalFileString = substr($editedNormalFileString, 0, $_50_50_left_start) . $map['2Spalten-50-50_main_links'] . substr($fileString, $_50_50_left_end);
        $editedNormalFileString = substr($editedNormalFileString, 0, $_50_50_right_start) . $map['2Spalten-50-50_main_rechts'] . substr($fileString, $_50_50_right_end);
        $editedNormalFileString = substr($editedNormalFileString, 0, $_30_70_left_start) . $map['2Spalten-30-70_main_links'] . substr($fileString, $_30_70_left_end);
        $editedNormalFileString = substr($editedNormalFileString, 0, $_30_70_right_start) . $map['2Spalten-30-70_main_rechts'] . substr($fileString, $_30_70_right_end);
        $editedNormalFileString = substr($editedNormalFileString, 0, $_70_30_left_start) . $map['2Spalten-70-30_main_links'] . substr($fileString, $_70_30_left_end);
        $editedNormalFileString = substr($editedNormalFileString, 0, $_70_30_right_start) . $map['2Spalten-70-30_main_rechts'] . substr($fileString, $_70_30_right_end);



        // write all the files to fileadmin
        if ($loadDefault){
            //change path to fileadmin path for saving if the default had to be loaded
            $path = dirname(__DIR__, 5) . "/fileadmin/typo3_template_baukasten/" . $rootPageID . "_";     // TODO include HP1/2
        }

        file_put_contents($path . "header.css", $editedHeaderFileString);
        file_put_contents($path . "footer.css", $editedFooterFileString);
        file_put_contents($path . "homepage1.css", $editedHP1FileString);
        file_put_contents($path . "homepage2.css", $editedHP2FileString);
        file_put_contents($path . "normal.css", $editedNormalFileString);

        $data = ['result' => 'Doing Ajax Stuff'];
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