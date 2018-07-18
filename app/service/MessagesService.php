<?php

class MessagesService
{
    public function success($text = 'good good.')
    {
        echo '<div class="alert alert-success">'.$text.'</div>';
        return true;
    }

    public function error($text = 'an error.')
    {
        echo '<div class="alert alert-danger">'.$text.'</div>';
        return true;
    }

    public function simple($text)
    {
        echo $text;
    }

    public function detectedUrlsAlert($text, $color, $value)
    {
        echo '<br><font color="'.$color.'"><b>'.$text.'</b></font>' . $value . '';
    }
}