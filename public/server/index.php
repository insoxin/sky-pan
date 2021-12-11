<?php


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');



class Server{

    const DS = '/';

    protected $config = [];

    protected $runtime_path;

    /**
     * 初始化
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        // 默认配置
        $default = [
            'token' => '',
            'chunks_path' => '/chunks/',
            'upload_path' => '/upload/',
            'min_file_size' => 2 * 1024 * 1024
        ];
        // 获取配置信息
        $this->config = array_merge($default,$config);

        // 运行目录
        $this->runtime_path = $this->getFitSeparator(__DIR__);

    }


    /**
     * 构建器
     * @param $config
     */
    public static function create($config){
        try {
            (new self($config))->start();
        }catch (Throwable $e){
            if($e->getCode() == 077)
                exit($e->getMessage());
            exit(json_encode(['code' => 0,'msg' => $e->getMessage()]));
        }
    }

    /**
     * 运行远程服务器
     * @throws Exception
     */
    public function start(){

        // 允许的操作类型
        $action = ['upload', 'download','delete'];

        // 执行方式
        $command = $_GET['md'] ?? '';

        // 签名
        $sign = $_GET['sign'] ?? '';

        // 操作类型不存在
        if(!in_array($command,$action)){
            $this->returnJson(0,'操作类型不存在');
        }

        if(empty($sign)){
            $this->returnJson(0,'数据签名不存在');
        }

        if(!$this->sign_verify($_GET,$sign)){
            $this->returnJson(0,'数据签名验证失败');
        }

        // 操作类型
        switch ($command){
            case 'upload':
                // 分片上传参数
                $chunk = $_POST['chunk'] ?? 0;
                $chunks = $_POST['chunks'] ?? 0;

                // 目录策略信息
                $folder_id = $_POST['folder_id'] ?? 0;
                $policy_id = $_GET['policy_id'] ?? 0;
                $root_folder_id = $_GET['root_folder_id'] ?? 0;

                // 分片上传
                if(!empty($chunks)){
                    //分片密钥
                    $chunks_key = $_POST['chunks_key'] ?? 0;

                    if(empty($chunks_key)){
                        $this->returnJson(0,'分片密钥错误');
                    }

                    $file = $_FILES["file"];

                    if(empty($file)){
                        $this->returnJson(0,'请上传分片文件');
                    }

                    // 分片存储目录
                    $chunk_path = $this->getUserChunkPath($_GET['uid']);

                    // 当前分片名称
                    $chunks_list_name = 'chunks_'.$_GET['uid'].'_'.$chunks_key;

                    // 分片列表
                    $chunks_list = $this->getChunkKeyFile($chunks_list_name,$chunk_path);

                    // 获取当前分片文件
                    $chunks_file = $this->getChunkFile($file);

                    // 当前分片文件名
                    $chunks_filename = 'chunks_'.$_GET['uid'].$this->getRandomName(16).'_'.$chunk;

                    $chunkObj = fopen ($chunk_path . self::DS .$chunks_filename.".chunk","w+");
                    $chunkObjWrite = fwrite ($chunkObj,$chunks_file);

                    if(!$chunkObj || !$chunkObjWrite){
                        $this->returnJson(0,'分片创建错误');
                    }

                    // 加入分片文件
                    $chunks_list[] = $chunks_filename;

                    // 保存分片key文件
                    $this->setChunkKeyFile($chunks_list_name,$chunk_path,$chunks_list);

                    // 判断分片是否上传完成
                    if($chunk == ($chunks - 1)){
                        // 融合文件名
                        $combine_name = "file_".$this->getRandomName(8);
                        // 打开融合文件hanlder
                        $fileObj = fopen($chunk_path.self::DS.$combine_name,"a+");
                        // 融合
                        foreach ($chunks_list as $value) {

                            $chunkObj = fopen($chunk_path.self::DS.$value.".chunk", "rb");

                            if(!$fileObj || !$chunkObj){
                                $this->returnJson(0,'文件创建失败');
                            }

                            $content = fread($chunkObj, (2 * 1024 * 1024));

                            fwrite($fileObj, $content, (2 * 1024 * 1024));

                            unset($content);

                            fclose($chunkObj);

                            unlink($chunk_path.self::DS.$value.".chunk");
                        }

                        // 获取保存目录
                        $save_dir = $this->getUserUploadPath($_GET['uid']);

                        // 文件后缀
                        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

                        // 随机文件名
                        $file_name = 'file_'.$this->getRandomName(16);

                        // 保存文件名
                        $save_file_name = $this->getFitSeparator($this->runtime_path.$save_dir) . $file_name.'.'.$ext;

                        if(!@rename($chunk_path.self::DS.$combine_name,$save_file_name)){
                            $this->returnJson(0,'融合文件创建失败');
                        }

                        $this->rmChunkKeyFile($chunks_list_name,$chunk_path);

                        $size = filesize($save_file_name);

                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mime_type = finfo_file($finfo,$save_file_name);

                        // 文件信息
                        $info =  [
                            'uid' => $_GET['uid'],
                            'name' => $file['name'],
                            'ext' => $ext,
                            'path' => $save_dir . $file_name .'.'. $ext,
                            'size' => $size,
                            'mime' => $mime_type,
                            'folder_id' => ($folder_id == 0 ? $root_folder_id : $folder_id),
                            'policy_id' => $policy_id
                        ];

                        // 回调通知
                        $res = $this->upload_notify($_GET['notify'],$info);
                        if($res != 'UPLOAD_SUCCESS'){
                            $this->returnJson(0,'同步错误：'.$res);
                        }

                        $this->returnJson(1,'上传文件成功');

                    }
                    $this->returnJson(1,$chunks_filename.'.chunk');
                }

                // 上传文件
                $info = $this->upload_file($_GET['uid']);
                // 额外参数
                $info['uid'] = $_GET['uid'];
                $info['folder_id'] = ($folder_id == 0 ? $root_folder_id : $folder_id);
                $info['policy_id'] = $policy_id;
                // 回调通知
                $res = $this->upload_notify($_GET['notify'],$info);
                if($res != 'UPLOAD_SUCCESS'){
                    $this->returnJson(0,'同步错误：'.$res);
                }
                $this->returnJson(1,'上传文件成功');
                break;

            case 'download':
                // 下载操作
                $tk = $_GET['tk'] ?? '';
                $tk_info = $this->decodeTk($tk);

                //获取下载文件位置
                $download_file_path = $this->getFitSeparator($this->runtime_path . $tk_info['file']);

                // 检查文件是否存在
                if(!is_file($download_file_path)){
                    $this->returnJson(0,'下载文件不存在');
                }

                if ($tk_info['type'] == 'none'){
                    // 禁止下载
                    $this->returnJson(0,'禁止下载此文件');
                }

                // 启用 nginx X-Accel 下载
                header('Content-Type: application/octet-stream');
                $encoded_fname = rawurlencode($tk_info['origin']);
                header('Content-Disposition: attachment;filename="'.$encoded_fname.'";filename*=utf-8'."''".$encoded_fname);

                header('X-Accel-Redirect: '. $tk_info['file']);
                header('X-Accel-Buffering: yes');

                if ($tk_info['type'] > 0){
                    $_file_limit_size = round(intval($tk_info['type']) * 1024);
                    // 限速下载
                    header('X-Accel-Limit-Rate:'.$_file_limit_size);
                }

                break;

            case 'delete':
                // 文件根目录
                $root = $this->runtime_path;
                // 文件路径
                $file_path = $_GET['path'] ?? '';

                if(empty($file_path)){
                    $this->returnJson(0,'删除的文件路径不存在');
                }

                // 获取真实文件地址
                $real_path = $root . $file_path;
                // 修复文件路径
                $real_path = str_replace(['\\','/','//','\\\\'],DIRECTORY_SEPARATOR,$real_path);
                // 文件是否存在
                if(is_file($real_path)){
                    // 删除文件
                    @unlink($real_path);
                }

                $this->returnJson(1,'删除文件成功');
                break;
        }

    }


    /**
     * 解密tk
     * @param $string
     * @return array|false|string[]
     * @throws Exception
     */
    protected function decodeTk($string){
        $data = str_replace(['-','_'],['+','/'],$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        $tk = base64_decode($data);
        $tk_data = explode(',',$tk);

        if(count($tk_data) != 4){
            $this->returnJson(0,'下载文件失败，参数错误');
        }

        $keys = ['file','origin','type','time'];

        return array_combine($keys,$tk_data);
    }

    /**
     * 获取分片文件内容
     * @return false|string
     * @throws Exception
     */
    protected function getChunkFile($file){
        $size = $file['size'];

        if($size > (2 * 1024 * 1024)){
            $this->returnJson(0,'分片大小错误');
        }
        $chunk_fh = fopen($file['tmp_name'],'r');
        $chunk_data = fread($chunk_fh,$size);
        fclose($chunk_fh);
        return $chunk_data;
    }


    /**
     * 删除chunkKey文件
     * @param $key
     * @param $path
     */
    protected function rmChunkKeyFile($key,$path){
        unlink($path.$key.'.crx');
    }

    /**
     * 读取chunkKey文件
     * @param $key
     * @param $path
     * @return array|mixed
     */
    protected function getChunkKeyFile($key,$path){
        $file_name = $path.$key.'.crx';
        if(!is_file($file_name)){
            return [];
        }

        $content = file_get_contents($file_name);

        $data = json_decode($content,true);

        if(empty($data)){
            return [];
        }

        return $data;
    }

    /**
     * 写入chunkKey文件
     * @param $key
     * @param $path
     * @param $data
     * @return false|int
     */
    protected function setChunkKeyFile($key,$path,$data){
        $content = json_encode($data);
        return file_put_contents($path.$key.'.crx',$content);
    }

    /**
     * 上传文件到服务器
     * @param $uid
     * @return array
     * @throws Exception
     */
    protected function upload_file($uid): array
    {
        // 获取上传文件
        $file = $_FILES["file"];

        if(!$file){
            $this->returnJson(0,'请选择需要上传的文件');
        }

        // 获取文件错误
        if($file['error'] > 0){
            $this->returnJson(0,'上传错误：'.$this->getFileUploadError($file['error']));
        }

        // 大于 2mb 用分片上传
        if($file['size'] > $this->config['min_file_size']){
            $this->returnJson(0,'上传方式错误：超出此方式大小');
        }

        // 文件后缀
        $file_ext = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));

        // 获取保存目录
        $save_dir = $this->getUserUploadPath($uid);

        // 获取保存文件夹
        $file_name = 'file_'.$this->getRandomName(16);

        // 最终保存路径
        $save_file = $this->runtime_path . $save_dir . $file_name .'.'. $file_ext;

        // 保存文件
        move_uploaded_file($file["tmp_name"], $save_file);


        if(!is_file($save_file)){
            $this->returnJson(0,'文件上传失败：Error Move Files');
        }

        $size = filesize($save_file);

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo,$save_file);

        // 文件信息
        return [
            'name' => $file['name'],
            'ext' => $file_ext,
            'path' => $save_dir . $file_name .'.'. $file_ext,
            'size' => $size,
            'mime' => $mime_type
        ];

    }


    /**
     * 获取用户上传目录
     * @param $uid
     * @return array|string|string[]
     */
    protected function getUserUploadPath($uid){
        $root = $this->runtime_path;
        $path = $this->getFitSeparator($this->config['upload_path'] . date('Ymd') . self::DS . $uid . self::DS);

        $save_path = $this->getFitSeparator($root.$path);

        is_dir($save_path) || mkdir($save_path,0775,true);

        return $path;
    }

    /**
     * 获取分片上传目录
     * @param $uid
     * @return array|string|string[]
     */
    protected function getUserChunkPath($uid){
        $root = $this->runtime_path;
        $path = $this->getFitSeparator($this->config['chunks_path'] . $uid . self::DS);

        $save_path = $this->getFitSeparator($root.$path);

        is_dir($save_path) || mkdir($save_path,0775,true);

        return $save_path;
    }

    /**
     * 获取随机文件名
     * @param int $length
     * @return string
     */
    protected function getRandomName(int $length = 16): string
    {
        $charTable = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $result = "";
        for ( $i = 0; $i < $length; $i++ ){
            $result .= $charTable[ mt_rand(0, strlen($charTable) - 1) ];
        }
        return $result;
    }

    /**
     * 获取一致化目录分隔符
     * @param $dir
     * @param string $ds
     * @return array|string|string[]
     */
    protected function getFitSeparator($dir, string $ds = ''){
        $ds = empty($ds) ? self::DS : $ds;
        return str_replace(['\\','/','\\\\','//'],$ds,$dir);
    }

    /**
     * 获取文件上传错误
     * @param $code
     * @return bool|string
     */
    protected function getFileUploadError($code){
        switch ($code){
            case 1:
                // 文件大小超出了服务器的空间大小
                return "The file is too large (server).";
            case 2:
                // 要上传的文件大小超出浏览器限制
                return "The file is too large (form).";
            case 3:
                // 文件仅部分被上传
                return "The file was only partially uploaded.";
            case 4:
                // 没有找到要上传的文件
                return "No file was uploaded.";
            case 5:
                // 服务器临时文件夹丢失
                return "The servers temporary folder is missing.";
            case 6:
                // 文件写入到临时文件夹出错
                return "Failed to write to the temporary folder.";
        }

        return true;
    }

    /**
     * 返回json内容
     * @param $code
     * @param $msg
     * @param $data
     * @return void
     * @throws Exception
     */
    protected function returnJson($code, $msg, $data = null){
        $result = [
            'code' => $code,
            'msg' => $msg
        ];

        if(is_array($data)){
            $result = array_merge($result,$data);
        }else if (is_string($data)){
            $result['data'] = $data;
        }

        $json = json_encode($result);

        throw new Exception($json,077);
    }

    /**
     * 参数签名
     * @param $params
     * @return string
     */
    protected function sign_params($params): string
    {
        // 过滤参数
        $params = array_filter($params,function($key) use ($params){
            if(empty($params[$key]) || $key == 'sign'){
                return false;
            }
            return true;
        },ARRAY_FILTER_USE_KEY);

        // ascii排序
        ksort($params);
        reset($params);

        // 签名
        return md5(urldecode(http_build_query($params)) . $this->config['token']);
    }

    /**
     * 签名验证
     * @param $params
     * @param $sign
     * @return bool
     */
    protected function sign_verify($params,$sign): bool
    {
        return $this->sign_params($params) == $sign;
    }

    /**
     * 回调通知上传
     * @param $url
     * @param $param
     * @return bool|string
     */
    protected function upload_notify($url,$param)
    {
        // 生成签名
        $sign = $this->sign_params($param);
        $param['sign'] = $sign;

        // 请求地址
        $request_url = $url.'?'.urldecode(http_build_query($param));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}

Server::create(['token' => 'asdasfasfasfasfasfa', 'upload_path' => '/upload/']);