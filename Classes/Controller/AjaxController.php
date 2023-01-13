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

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

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
    public function listAction(): \Psr\Http\Message\ResponseInterface
    {
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
