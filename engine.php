<?php

require_once('app/controller/EngineController.php');

$engineController = new EngineController();
$lang = (object)$engineController->getLandService()->getLang();

if ($engineController->getHelpersService()->checkIfClickSubmit(isset($_POST['submit']) ? $_POST['submit'] : null)) {

    if ($engineController->getFileService()->checkIfFileIsUploaded($_FILES['linkstxt']['tmp_name'])) {
        $fileOpen = $engineController->getFileService()->captureGivenFile($_FILES['linkstxt']['tmp_name']);

        $domain = $engineController->getDomainService()->validateDomain($_POST['domain']);

        $counter = $engineController->getFileService()->countFile($fileOpen);

        $regex = $engineController->getRegExpService()->getRules();

        if (!preg_match('/http:\/\//', $domain)) {
            if (!preg_match("@^[hf]tt?ps?://@", $domain)) {
                $domain = $engineController->getDomainService()->addHttpOrHttpsToGivenDomain($domain, 1);
            }
        }
        if (!preg_match("/^$regex$/i", $domain)) {
            return $engineController->getMessagesService()->error($lang->email_error);
        }

        foreach ($fileOpen as &$value) {
            if (!preg_match("~^(?:f|ht)tps?://~i", $value)) {
                $value = $engineController->getDomainService()->addHttpOrHttpsToGivenDomain($value, 2);
            }
            $value = $engineController->getDomainService()->changeHttpsToHttpInGivenDomain($value);
        }

        $new_domain = $engineController->getDomainService()->findOnlyDomainInGivenArr($domain);

        foreach ($fileOpen as $key => $fileLinks) {
            $curlInit = $engineController->getCurlService()->initCurl($fileLinks);
            $statusCode = $engineController->getCurlService()->getCode();

            $links = [];
            $found = false;

            echo '<hr>';
            if (!preg_match("/^$regex$/i", $fileLinks)) {
                $engineController->getMessagesService()->error($lang->invalid_domain . '<b>' . str_replace('http://', '', $fileLinks) . '</b>');
                continue;
            }

            $domService = $engineController->getDOMService();
            $dom = $domService->loadHtml($fileLinks);

            $findAHrefs = $domService->getDom()->getElementsByTagName('a');
            $findImgs = $domService->getDom()->getElementsByTagName('img');

            $links = $domService->findAllHerfsInGivenArray($findAHrefs);

            $links = $domService->findAllImagesInGivenArray($findAHrefs);

            $engineController->getMessagesService()->simple('<b>' . $lang->address_url . '</b>' . $fileLinks . ' | ');
            $engineController->getMessagesService()->simple('<b>' . $lang->response . '</b> ' . $curlInit . '  (Code: ' . $statusCode . ') | ');

            if (!empty($info['redirect_url'])) {
                $engineController->getMessagesService()->detectedUrlsAlert('' . $info['redirect_url'] . ' | ', 'blue', null);
            }

            $engineController->getMessagesService()->simple('<b>' . $lang->search . '</b>' . $domain . ' <br><b>' . $lang->status . '</b>');

            foreach ($links as $link) {
                $percent = $engineController->getHelpersService()->calculatePercent($key, $counter);

                $percent_decimal = $engineController->getHelpersService()->calculatePercentDecimal($percent);

                $engineController->getHelpersService()->includeProgress($key + 1);

                $engineController->getHelpersService()->includeNProgressBar($percent_decimal);

                $find_href = $engineController->getCurlService()->findHref($new_domain, $link['href']);
                $find_img = $engineController->getCurlService()->findImg($new_domain, $link['src']);

                if ($find_href) {
                    $save_txt = $engineController->getFileService()->saveDetectedUrlsToFile('detected_url.txt', $link['href']);

                    $found = true;
                    if ($link['href'] != '') {
                        $engineController->getMessagesService()->detectedUrlsAlert($lang->detected, 'green', $link['href']);
                        $engineController->getMessagesService()->simple('<b>' . $lang->anchor . '</b>');
                        $found = true;
                    }
                    if ($link['anchor'] != "") {
                        echo $link['anchor'];
                    } else {
                        $engineController->getMessagesService()->simple($lang->no_parameter);
                    }
                    echo '<b>' . $lang->rel . '</b>';

                    if ($link['rel'] != "") {
                        echo $link['rel'];
                    } else {
                        $engineController->getMessagesService()->simple($lang->no_parameter);
                    }
                }

                if ($find_img) {
                    $found = true;
                    if ($link['src'] != '') {
                        echo '<br><font color="green"><b>' . $lang->detected_graphic . '</b></font>' . $link['src'] . '';
                        $engineController->getMessagesService()->simple('<b>' . $lang->alt . '</b>');
                        $found = true;
                    }
                    if ($link['alt'] != "") {
                        echo $link['alt'];
                    } else {
                        $engineController->getMessagesService()->simple($lang->no_parameter);
                    }
                    $engineController->getMessagesService()->simple('<b>' . $lang->src . '</b>');

                    if ($link['src'] != "") {
                        echo $link['src'];
                        echo '<br>';
                    } else {
                        $engineController->getMessagesService()->simple($lang->no_parameter);
                    }
                }
            }
            if (!$found) {
                echo '<font color="red"><b>' . $lang->undetected . '</b></font>';
            }
        }

        $engineController->getHelpersService()->endOfNProgress();
    } else {
        $engineController->getMessagesService()->error($lang->choose_file);
    }
}