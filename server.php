<?php
class Server {
    public $cookieStr = '__cfduid=ddad36436ed7d6532b9f069911dd2a7f01563540315; PHPSESSID=hnpn38u4h5lhmvjdbq743mb6o1; UM_distinctid=16c0a43e2881bf-0bce04e90f5cd-37657c02-100200-16c0a43e289812; CNZZDATA1277711897=1069433515-1563536814-%7C1563536814; phpdisk_zcore_v2_info=e5d5vm3DfGPV%2FLRsfQbfZUagFvXaZuIkMpWruzfSUbWbXkdIOLuCtvpb1XW7dW6S2nf%2F9wCaKYQhQS%2Bwa6M6vywlqES1N1atCMK8KRsFg9Ss0YGr3OJ3WNA%2F';
    public $header = ['Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
        //    'Accept-Encoding: gzip, deflate, sdch',
        'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8',
        'Cache-Control: max-age=0',
        'Connection: keep-alive',
        'CLIENT-IP: 49.83.237.58',
        'X-FORWARDED-FOR: 49.83.237.58',
        'Host: www.89file.com',
        'Cookie: __cfduid=dc8ecfe692ac54334108d416fc1792b011563503408; PHPSESSID=mmkb97uh6ueo5v6p6abhcqd8s3; UM_distinctid=16c0811941352a-013585b4719b09-37657c02-1fa400-16c08119415b3b; CNZZDATA1277711897=213546448-1563499683-null%7C1563499683; phpdisk_zcore_v2_info=c549o29axvleVog%2B6af0zABkMIQIR%2FmCORytyaSVfQFryPFoycPbqJxdX2wppSQMo0hTypJG9ENCuxc7xMuzi3G%2FBxEzH4uK3sC380pXfAMIbfeQPVI4t7Yn',
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36',
        'Referer: http://www.89file.com/file/QVNaMTQwMDcx.html'];
    private $url;
    function __construct($url) {
        $this->url = $url;
    }
    public function getHtml() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_COOKIE, $this->cookieStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public function getFileId($content) {
        preg_match_all('#onclick="save_as\((.*?)\)"#',$content,$out,PREG_SET_ORDER);
        $fileId = $out[0][1];
        if ($fileId == NULL) {
            echo "cookie失效";
        }
        return $fileId;
    }
    public function getFilePath($fileId) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://www.89file.com/ajax.php");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_COOKIE, $this->cookieStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $postData = array("action"=>"load_down_addr_vip", "file_id"=>$fileId);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $result = curl_exec($ch);
        curl_close($ch);
        $arr = explode("|", $result);
        if ($arr[0] == "true") {
            return $arr[1];
        }
        return "抓包异常，请联系管理员";
    }
    public function download($path) {
        set_time_limit(0);
        $referer = "http://www.89file.com/file/QVNaMTQwMDcx.html";
        $opts = [
            'http'=> [
                'method' => 'GET',
                'header'=> 'Referer: '.$referer.'\r\n'
            ]
        ];
        $context = stream_context_create($opts);
        $read_buffer = 4096;
        $buffer = fopen($path, 'rb', false, $context);
        $sum_buffer=0;
        $contend = $http_response_header[3];
        // var_dump($contend);
        // preg_match_all('#Content-Length:(.*?)#',$contend,$out,PREG_SET_ORDER);
        $arrs = explode(" ", $contend);
        $filesize = $arrs[1];
        // var_dump($filesize);
        // $file = './'.$filename;
        // file_put_contents($file, $buffer);
        // chmod($file, 0777);
        // if (file_exists($file)) {
        foreach ($http_response_header as $item) {
            header($item);
        }
        // $filesize=filesize($path);
        // var_dump($http_response_header);
        while(!feof($buffer)) {
            echo fgets($buffer, 4096);
        }

        // while (!feof($hostfile)) {
        //     $output = fread($hostfile, 8192);
        // }
        // readfile($file);
        // fpassthru($buffer);
        fclose($buffer);
        
        // unlink($file);
        exit;
        // }
    }
    public function getWhatYouWant() {
        $result = $this -> getHtml($this->url);
        $fileId = $this->getFileId($result);
        $fileData = $this->getFilePath($fileId);
        preg_match_all('#<a href="(.*?)"#',$fileData,$out,PREG_SET_ORDER);
        $downloadPaths = array(0=>$out[0][1], 1=>$out[1][1], 2=>$out[2][1]);
        return $downloadPaths;
    }
}