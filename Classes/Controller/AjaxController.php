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


        // ******************************************************

        $test = "homepage1";
        $homepageArray = array();
        $gridArray = array();

        $homepageOptions = array();
        $gridOptions = array();


        // getting the background color for div elements
        // TODO change filepath from absolute to relative
        // header
        $fileString = file_get_contents(dirname(__DIR__, 3) . "/typo3_template_baukasten/Resources/Public/Css/header.css");
        $headerStart = strpos($fileString, "div.site-header");
        $colorSelector = strpos($fileString, "background-color:", $headerStart);
        $colorStart = strpos($fileString, " ", $colorSelector)+1;
        $colorEnd = strpos($fileString, ";", $colorStart);

        $headerColor = substr($fileString, $colorStart, $colorEnd - $colorStart);

        //footer
        $fileString = file_get_contents(dirname(__DIR__, 3) . "/typo3_template_baukasten/Resources/Public/Css/footer.css");
        $footerStart = strpos($fileString, "div.site-footer");
        $colorSelector = strpos($fileString, "background-color:", $footerStart);
        $colorStart = strpos($fileString, " ", $colorSelector)+1;
        $colorEnd = strpos($fileString, ";", $colorStart);

        $footerColor = substr($fileString, $colorStart, $colorEnd - $colorStart);

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
                        $testLayout .= "<div id='".$keyus."_header' name='header' onclick='onClick(this);' style='height: 20%; width:100%; border: 1px solid black; background-color: ".$headerColor."'>header</div>";
                    } else if ($testKilian == "footer") {
                        $testLayout .= "<div id='".$keyus."footer' name='footer' onclick='onClick(this);' style='height: 20%; width:100%; border: 1px solid black; background-color: ".$footerColor."'>footer</div>";
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


        /*
        $pid = intVal($_GET['id']);
        $page = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Domain\\Repository\\PageRepository');

        echo "<pre>";
        print_r($page);
        echo "</pre>";
        */


        return $this->htmlResponse();
    }


    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function doSomethingAction(ServerRequestInterface $request): ResponseInterface
    {
        //get the ajax inputs
        $colorArray = $request->getQueryParams()['colorArray'] ?? null;

        // split all the colors from the colorArray
        $headerColor = $colorArray[0][1];
        $footerColor = $colorArray[1][1];

        $hp2MainContentColor = "#fafafa"; // TODO
        $hp1MainContentColor = "#fafafa"; // TODO
        $_1ColumnContentColor = "#fafafa"; // TODO
        $_50_50_left_ContentColor = "#fafafa"; // TODO
        $_50_50_right_ContentColor = "#fafafa"; // TODO
        $_30_70_left_ContentColor = "#fafafa"; // TODO
        $_30_70_right_ContentColor = "#fafafa"; // TODO
        $_70_30_left_ContentColor = "#fafafa"; // TODO
        $_70_30_right_ContentColor = "#fafafa"; // TODO


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
            $colorStart = $this->getStart($fileString, "div .main-homepage1_content");
            $colorEnd = $this->getEnd($fileString, $colorStart);

            $editedFileString = substr($fileString, 0, $colorStart) . $hp1MainContentColor . substr($fileString, $colorEnd);
            file_put_contents($path . "_homepage1.css", $editedFileString);


            // HP2 Main Content
            $fileString = file_get_contents($templatePath . "homepage2.css");
            $colorStart = $this->getStart($fileString, "div .main-homepage2_content");
            $colorEnd = $this->getEnd($fileString, $colorStart);

            $editedFileString = substr($fileString, 0, $colorStart) . $hp2MainContentColor . substr($fileString, $colorEnd);
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


            // HP2 Main Content
            $fileString = file_get_contents($path . "_homepage1.css");
            $colorStart = $this->getStart($fileString, "div .main-homepage1_content");
            $colorEnd = $this->getEnd($fileString, $colorStart);

            $editedFileString = substr($fileString, 0, $colorStart) . $hp1MainContentColor . substr($fileString, $colorEnd);
            file_put_contents($path . "_homepage1.css", $editedFileString);


            // HP2 Main Content
            $fileString = file_get_contents($path . "_homepage2.css");
            $colorStart = $this->getStart($fileString, "div .main-homepage2_content");
            $colorEnd = $this->getEnd($fileString, $colorStart);

            $editedFileString = substr($fileString, 0, $colorStart) . $hp2MainContentColor . substr($fileString, $colorEnd);
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

        $data = ['result' => 'my stuff'];
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
