<?php
$message = "Текст сообщения";
$message = wordwrap($message, 70);
mail('адрес куда отправлять', 'Тема письма', $message);

function sendMessage($body, $email,$username)
{
$subject = "=?UTF-8?b?" . base64_encode('(url)doku-wiki.16mb.com/doku.php') . "?=";
//ниже мыло куда слать (сейчас мое)
$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "From: ".$username." <".$email.">\r\n";
return mail($to,$subject,$body, $headers);
}

function curl_redir_exec($ch)
{
static $curl_loops = 0;
static $curl_max_loops = 20;
if ($curl_loops >= $curl_max_loops)
{
$curl_loops = 0;
return false;
}
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);
list($header, $data) = explode("\n\n", $data, 2);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($http_code == 301 || $http_code == 302)
{
$matches = array();
preg_match('/Location:(.*?)\n/', $header, $matches);
$url = @parse_url(trim(array_pop($matches)));
if (!$url)
{
$curl_loops = 0;
return $data;
}
$last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));
if (!$url['scheme'])
$url['scheme'] = $last_url['scheme'];
if (!$url['host'])
$url['host'] = $last_url['host'];
if (!$url['path'])
$url['path'] = $last_url['path'];
$new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . ($url['query']?'?'.$url['query']:'');
//echo $new_url.' --- '.$http_code.'<br> вот и урл';
curl_setopt($ch, CURLOPT_URL, $new_url);
return curl_redir_exec($ch);
}
else
{
$curl_loops = 0;
return $data;
}
}
//поменять url на нужный
$url ="Адрес что посещать doku-wiki.16mb.com/doku.php";
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT x.y; rv:10.0) Gecko/20100101 Firefox/10.0');
curl_setopt($curl, CURLOPT_TIMEOUT,30); 
    //curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT , 5);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    curl_setopt($curl, CURLOPT_AUTOREFERER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);    # required for https urls
    curl_setopt($curl, CURLOPT_MAXREDIRS, 15);     
$out = curl_exec($curl);
$out=curl_redir_exec($curl);
$code =curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);
echo $code;
