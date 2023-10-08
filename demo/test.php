<?php
// 检查  CURL 扩展是否安装
if(in_array('curl', get_loaded_extensions())){
    echo 'CURL 扩展已安装';
}else{
    echo 'CURL 扩展未安装';
}

// info 
 phpinfo();

?>