<?php

$listType = '';
if (isset($field['customParameters']['listType'])) {
    $listType = $field['customParameters']['listType'];
}

$list = \MauticPlugin\MauticCitrixBundle\Helper\CitrixHelper::getCitrixChoices($listType);

echo $view->render(
    'MauticFormBundle:Field:select.html.php',
    [
        'field'    => $field,
        'inForm'   => (isset($inForm)) ? $inForm : false,
        'list'     => $list,
        'id'       => $id,
        'formId'   => (isset($formId)) ? $formId : 0,
        'formName' => (isset($formName)) ? $formName : '',
    ]
);
