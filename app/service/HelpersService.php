<?php

class HelpersService
{
    public function checkIfClickSubmit($post)
    {
        if(isset($post)) {
            return true;
        } else {
            return false;
        }
    }

    public function includeNProgressBar($percent_decimal)
    {
        echo '<script language="javascript">
                NProgress.set(' . $percent_decimal . ');
                NProgress.inc();
                NProgress.configure({ ease: \'ease\', speed: 1500 });
                </script>';
    }

    public function includeProgress($jscounter)
    {
        echo '<script language="javascript">
                document.getElementById("information").innerHTML=" Status: ' . $jscounter . ' url(i) processed.";
                </script>';
    }

    public function endOfNProgress()
    {
        echo '<script>NProgress.done();</script>';
    }

    public function calculatePercent($key, $counter)
    {
        return intval($key / $counter * 100);
    }

    public function calculatePercentDecimal($percent)
    {
        return $percent / 100;
    }
}