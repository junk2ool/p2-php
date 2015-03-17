<?php
/**
 * rep2 - 2ch���O�C��
 */

// {{{ authenticate_2chapi()


/**
* 2chAPI�� SID ���擾����
*
* @return mix �擾�ł����ꍇ��SID��Ԃ�
*/
    function authenticate_2chapi()
    {
    	global $_conf;
        $url = 'https://api.2ch.net/v1/auth/';
        $CT = time();
        $AppKey = $_conf['2chapi_appkey'];
        $AppName = $_conf['2chapi_appname'];
        $HMKey = $_conf['2chapi_hmkey'];
        $AuthUA = sprintf($_conf['2chapi_ua.auth'],$AppName);
        $login2chID = "";
        $login2chPW = "";
        $message = $AppKey.$CT;
        $HB = hash_hmac("sha256", $message, $HMKey);
        
        if(empty($AppKey) || empty($AppName) || empty($HMKey)) {
            P2Util::pushInfoHtml("<p>p2 Error: 2ch API �̔F�؂ɕK�v�ȏ�񂪐ݒ肳��Ă��܂���B</p>");
            return '';
        }
        
        if ($_conf['2chapi_rounin'] == 1&& $array = P2Util::readIdPw2ch()) {
            list($login2chID, $login2chPW, $autoLogin2ch) = $array;
        }
        
        $values = array(
            'ID' => $login2chID,
            'PW' => $login2chPW,
            'KY' => $AppKey,
            'CT' => $CT,
            'HB' => $HB,
        );
        $options = array('http' => array(
            'ignore_errors' => true,
            'method' => 'POST',
            'header' => implode("\r\n", array(
                'User-Agent: '.$AuthUA,
                'X-2ch-UA: '.$AppName,
                'Content-Type: application/x-www-form-urlencoded',
            )),
            'content' => http_build_query($values),
        ));
        
        // �v���L�V
        if ($_conf['proxy_use']) {
            $options['http'] += array('proxy' => 'tcp://'.$_conf['proxy_host'].":".$_conf['proxy_port']);
            $options['http'] += array('request_fulluri' => true);
            $options['ssl'] = array('SNI_enabled' => false);
        }
        
        $response = '';
        $response = file_get_contents($url, false, stream_context_create($options));
        
        if(file_exists($_conf['sid2chapi_php'])) {
            unlink($_conf['sid2chapi_php']);
        }
        
        if (strpos($response, ':') != false)
        {
            $sid = explode(':', $response);
            
            if($_conf['2chapi_debug_print']==1)
            {
                P2Util::pushInfoHtml($response."<br>".$AuthUA);
            }
            
            if($sid[0]!='SESSION-ID=Monazilla/1.00') {
                P2Util::pushInfoHtml("<p>p2 Error: 2ch API ��SessionID���擾�o���܂���ł����B</p>");
                return '';
            }
            
            $cont = sprintf('<?php $SID2chAPI = %s;', var_export($sid[1], true));
            if (false === file_put_contents($_conf['sid2chapi_php'], $cont, LOCK_EX)) {
                P2Util::pushInfoHtml("<p>p2 Error: {$_conf['sid2chapi_php']} ��ۑ��ł��܂���ł����B���O�C���o�^���s�B</p>");
                return '';
            }
            
            return $sid[1];
        }
        
        return '';
    }
// }}}

/*
 * Local Variables:
 * mode: php
 * coding: cp932
 * tab-width: 4
 * c-basic-offset: 4
 * indent-tabs-mode: nil
 * End:
 */
// vim: set syn=php fenc=cp932 ai et ts=4 sw=4 sts=4 fdm=marker: