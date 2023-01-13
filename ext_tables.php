<?php
defined('TYPO3') || die();

(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'stylingcockpit',
        'web',
        'backend',
        '',
        [
            \Zimk\stylingcockpit\Controller\AjaxController::class => 'list, show',
        ],
        [
            'access' => 'user,group',
            'icon'   => 'EXT:styling_cockpit/Resources/Public/Icons/user_mod_backend.svg',
            'labels' => 'LLL:EXT:styling_cockpit/Resources/Private/Language/locallang_backend.xlf',
        ]
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_stylingcockpit_domain_model_ajax', 'EXT:styling_cockpit/Resources/Private/Language/locallang_csh_tx_stylingcockpit_domain_model_ajax.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_stylingcockpit_domain_model_ajax');
})();
