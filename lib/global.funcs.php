<?php
/**
 * rep2expack - ���[�e�B���e�B�֐��Q
 */

// {{{ rep2 1.8.x lib/global.funcs.php ���

/**
 * htmlspecialchars() �̕ʖ��݂����Ȃ���
 *
 * @param   string  $alt  �l����̂Ƃ��̑�֕�����
 * @return  string|null
 */
function hs($str, $alt = '', $quoteStyle = ENT_QUOTES, $charset = 'Shift_JIS')
{
    return (isset($str) && strlen($str) > 0) ? htmlspecialchars($str, $quoteStyle, $charset) : $alt;
}

/**
 * notice �̗}�������Ă���� hs()
 * �Q�ƂŒl���󂯎��̂̓C�}�C�`�����A�������Ȃ����notice�̗}�����ł��Ȃ�
 *
 * @param   &string  $str  ������ϐ��̎Q��
 * @return  string|null
 */
function hsi(&$str, $alt = '', $quoteStyle = ENT_QUOTES, $charset = 'Shift_JIS')
{
    return (isset($str) && strlen($str) > 0) ? htmlspecialchars($str, $quoteStyle, $charset) : $alt;
}

/**
 * echo hs()
 *
 * @return  void
 */
function eh($str, $alt = '', $quoteStyle = ENT_QUOTES)
{
    echo hs($str, $alt, $quoteStyle);
}

/**
 * echo hs() �inotice��}������j
 *
 * @param   &string  $str  ������ϐ��̎Q��
 * @return  void
 */
function ehi(&$str, $alt = '', $quoteStyle = ENT_QUOTES, $charset = 'Shift_JIS')
{
    echo hs($str, $alt, $quoteStyle, $charset);
}

/**
 * ���݂��Ȃ��ϐ��� notice ���o�����ƂȂ��A�ϐ��̒l���擾����
 *
 * ���̊֐��Ŕz��̒��g���擾���悤�Ƃ���ƁA�z�񂻂̂��̂��쐬���Ă��܂����Ƃ�����̂ɒ��ӁB
 * �܂� $hoge �����݂��Ȃ����ɁAgeti($hoge['huga']) �Ƃ���ƁA $hoge �� array('huga' => null) �ƂȂ��Ă��܂��B
 *
 * @return  mixed
 */
function geti(&$var, $alt = null)
{
    return isset($var) ? $var : $alt;
}

/**
 * ���s��t���ĕ�������o�͂���Bcli(\n)��web(<br>)�ŏo�͂��ω�����B
 * �����̕�����͕�����邱�Ƃ��\�B�������Ȃ���Ή��s�������o�͂���B
 *
 * @return  void
 */
function echoln()
{
    $n = (php_sapi_name() == 'cli') ? "\n" : '<br>';
    
    if ($args = func_get_args()) {
        foreach ($args as $v) {
            echo $v . $n;
        }
    } else {
        echo $n;
    }
}

// }}}

// {{{ CONSTANTS

/**
 * �����̍ő�l�ƍŏ��l
 */
define('P2_INT_MAX', PHP_INT_MAX);
define('P2_INT_MIN', - PHP_INT_MAX - 1);

/**
 * �����Ƀ}�b�`���鐳�K�\��
 */
//define('P2_REGEX_KANJI', mb_convert_encoding('/[��-�]/u', 'UTF-8', 'SJIS-win'));
define('P2_REGEX_KANJI', '/[\\x{4e00}-\\x{9fa0}]/u');

/**
 *�������K���ȕ����������p���K�\��
 */
/*
define('P2_REGEX_WAKATI', mb_convert_encoding('/(' . implode('|', array(
    //'[��-�]+[��-��]*',
    //'[��-�]+',
    '[���O�l�ܘZ������\]+',
    '[��-�]+',
    '[��-��][��-��[�`�J�K]*',
    '[�@-��][�@-���[�`�J�K]*',
    //'[a-z][a-z_\\-]*',
    //'[0-9][0-9.]*',
    '[0-9a-z][0-9a-z_\\-]*',
)) . ')/u', 'UTF-8', 'SJIS-win'));
*/
define('P2_REGEX_WAKATI', '/(
#[\\x{4e00}-\\x{9fa0}]+[\\x{3041}-\\x{3093}]*|
#[\\x{4e00}-\\x{9fa0}]+|
[\\x{4e00}\\x{4e8c}\\x{4e09}\\x{56db}\\x{4e94}\\x{516d}\\x{4e03}\\x{516b}\\x{4e5d}\\x{5341}]+|
[\\x{4e01}-\\x{9fa0}]+|
[\\x{3041}-\\x{3093}][\\x{3041}-\\x{3093}\\x{30fc}\\x{301c}\\x{309b}\\x{309c}]*|
[\\x{30a1}-\\x{30f6}][\\x{30a1}-\\x{30f6}\\x{30fc}\\x{301c}\\x{309b}\\x{309c}]*|
#[a-z][a-z_\\-]*|
#[0-9][0-9.]*|
[0-9a-z][0-9a-z_\\-]*)/ux');

/**
 * UTF-8,NFD�̂Ђ炪�ȁE�J�^�J�i�̑����E�������Ƀ}�b�`���鐳�K�\��
 */
/*
define('P2_REGEX_NFD_KANA',
    str_replace(
        array('%u3099%', '%u309A%'),
        array(pack('C*', 0xE3, 0x82, 0x99), pack('C*', 0xE3, 0x82, 0x9A)),
        mb_convert_encoding(
            '/([����-����-����-�Ƃ�-�كE�J-�R�T-�\�^-�g�n-�z�T�R])%u3099%|([��-�كn-�z])%u309A%/u',
            'UTF-8',
            'SJIS-win'
        )
    )
);
*/
define('P2_REGEX_NFD_KANA', '/([\\x{3046}\\x{304b}-\\x{3053}\\x{3055}-\\x{305d}\\x{305f}-\\x{3068}\\x{306f}-\\x{307b}\\x{30a6}\\x{30ab}-\\x{30b3}\\x{30b5}-\\x{30bd}\\x{30bf}-\\x{30c8}\\x{30cf}-\\x{30db}\\x{309d}\\x{30fd}])\\x{3099}|([\\x{306f}-\\x{307b}\\x{30cf}-\\x{30db}])\\x{309a}/u');

// }}}
// {{{ p2h()

/**
* htmlspecialchars($string, ENT_QUOTES, 'Shift_JIS') �̃V���[�g�J�b�g
*
* @param    string $string
* @param    bool   $double_encode
* @return   string
*/
function p2h($string, $double_encode = true)
{
    return htmlspecialchars($string, ENT_QUOTES, 'Shift_JIS', $double_encode);
}

// }}}
// {{{ p2die()

/**
 * ���b�Z�[�W��\�����ďI��
 * �w�b�_���o�͂���Ă���ꍇ�A<body>�܂ł͏o�͍ςƌ��Ȃ�
 *
 * �I���X�e�[�^�X�R�[�h2��P2CommandRunner�ɃG���[���b�Z�[�W��
 * HTML�ł��邱�Ƃ�ʒm���邽��
 *
 * @param   string $err �G���[�T�v
 * @param   string $msg �ڍׂȐ���
 * @param   bool   $raw �ڍׂȐ������G�X�P�[�v���邩�ۂ�
 * @return  void
 */
function p2die($err = null, $msg = null, $raw = false)
{
    if (!defined('P2_CLI_RUN') && !headers_sent()) {
        header('Expires: ' . http_date(0)); // ���t���ߋ�
        header('Last-Modified: ' . http_date()); // ��ɏC������Ă���
        header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache'); // HTTP/1.0
        echo <<<EOH
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
{$GLOBALS['_conf']['extra_headers_ht']}
<title>rep2 error</title>
</head>
<body>
EOH;
    }

    echo '<h3>rep2 error</h3>';

    if ($err !== null) {
        echo '<p><strong>', p2h($err), '</strong></p>';
    }

    if ($msg !== null) {
        if ($raw) {
            echo $msg;
        } else {
            echo '<p>', nl2br(p2h($msg)), '</p>';
        }
    }

    if (true) {
        echo '<pre><em>backtrace:</em>';

        $p2_file_prefix = P2_BASE_DIR . DIRECTORY_SEPARATOR;
        $p2_base_dir_len = strlen(P2_BASE_DIR);
        $backtrace = debug_backtrace();
        $c = count($backtrace);

        foreach ($backtrace as $bt) {
            echo "\n";

            if (strpos($bt['file'], $p2_file_prefix) === 0) {
                $filename = '.' . substr($bt['file'], $p2_base_dir_len);
            } else {
                $filename = '(external)' . DIRECTORY_SEPARATOR . basename($bt['file']);
            }
            printf('  % 2d. %s (line %d)', $c--, $filename, $bt['line']);

            if (array_key_exists('function', $bt) && $bt['function'] !== '' && $bt['function'] !== 'p2die') {
                if (array_key_exists('class', $bt) && $bt['class'] !== '') {
                    printf(': %s%s%s()',
                           $bt['class'],
                           str_replace('>', '&gt;', $bt['type']),
                           $bt['function']
                           );
                } else {
                    printf(': %s()', $bt['function']);
                }
            }
        }

        echo '</pre>';
    }

    if (!defined('P2_CLI_RUN')) {
        echo '</body></html>';
    }
    exit(2);
}

// }}}
// {{{ http_date()

if (!function_exists('http_date')) {
    /**
     * pecl_http��http_date()��Pure PHP��
     *
     * @param   int $timestamp
     * @return  string
     */
    function http_date($timestamp = null)
    {
        if ($timestamp === null) {
            //return str_replace('+0000', 'GMT', gmdate(DATE_RFC1123));
            return gmdate('D, d M Y H:i:s \\G\\M\\T');
        }
        //return str_replace('+0000', 'GMT', gmdate(DATE_RFC1123, $timestamp));
        return gmdate('D, d M Y H:i:s \\G\\M\\T', $timestamp);
    }
}

// }}}
// {{{ ctype

/**
 * ctype�g�����W���[���֐���Pure PHP�� (cntrl,graph,print,punct,space�͊���)
 */
if (!extension_loaded('ctype')) {
    function ctype_alnum($str) { return (bool)preg_match('/^[0-9A-Za-z]+$/', $str); }
    function ctype_alpha($str) { return (bool)preg_match('/^[A-Za-z]+$/', $str); }
    function ctype_digit($str) { return (bool)preg_match('/^[0-9]+$/', $str); }
    function ctype_lower($str) { return (bool)preg_match('/^[a-z]+$/', $str); }
    function ctype_upper($str) { return (bool)preg_match('/^[A-Z]+$/', $str); }
    function ctype_xdigit($str) { return (bool)preg_match('/^[0-9A-Fa-f]+$/', $str); }
}

// }}}
// {{{ p2_scan_nullbyte()

/**
 * ���N�G�X�g�p�����[�^����NULL�o�C�g�����o������I������
 * array_walk_recursive() �p�R�[���o�b�N�֐�
 *
 * @param   mixed   $value 
 * @param   mixed   $key
 * @return  void
 */
function p2_scan_nullbyte($value, $key)
{
    if (is_string($value) && strpos($value, P2_NULLBYTE) !== false) {
        p2die('���N�G�X�g�p�����[�^��NULL�o�C�g���܂܂�Ă��܂��B');
    }
}

// }}}
// {{{ p2_scan_script_injection()

/**
 * ���̂܂�HTML�ɖ��ߍ��܂�� host, bbs, key, ls ��
 * HTML�̓��ꕶ�����܂܂�Ă�����I������
 *
 * @param   array   $request
 * @return  void
 */
function p2_scan_script_injection($request)
{
    foreach (array('host', 'bbs', 'key', 'ls') as $key) {
        if (array_key_exists($key, $request)) {
            $value = $request[$key];
            if (p2h($value) != $value) {
                p2die('���N�G�X�g�p�����[�^�ɕs���ȕ���������܂��B');
            }
        }
    }
}

// }}}
// {{{ p2_print_memory_usage()

/**
 * �������̎g�p�ʂ�\������
 *
 * @return  void
 */
function p2_print_memory_usage()
{
    if (function_exists('memory_get_usage')) {
        $usage = memory_get_usage();
    } elseif (function_exists('xdebug_memory_usage')) {
        $usage = xdebug_memory_usage();
    } else {
        $usage = -1;
    }
    $kb = $usage / 1024;
    $kb = number_format($kb, 2, '.', '');

    echo 'Memory Usage: ' . $kb . 'KiB';
}

// }}}
// {{{ p2_realpath()

/**
 * ���݂��Ȃ�(��������Ȃ�)�t�@�C���̐�΃p�X���擾����
 *
 * @param   string $path
 * @return  string
 */
function p2_realpath($path)
{
    if (file_exists($path)) {
        return realpath($path);
    }
    return File_Util::realPath($path);
}

// }}}
// {{{ p2_si2int()

/**
 * SI�ړ������̐��l�𐮐��ɕϊ�����
 *
 * @param   numeric $num
 * @param   string  $kmg
 * @return  int
 * @throws  OverflowException, UnderflowException
 */
function p2_si2int($num, $kmg)
{
    $real = p2_si2real($num, $kmg);
    if ($real > PHP_INT_MAX) {
        throw new OverflowException(sprintf('Integer overflow (%0.0f)', $real));
        //return PHP_INT_MAX;
    }
    if ($real < P2_INT_MIN) {
        throw new UnderflowException(sprintf('Integer underflow (%0.0f)', $real));
        //return P2_INT_MIN;
    }
    return (int)$real;
}

// }}}
// {{{ p2_si2real()

/**
 * SI�ړ������̐��l�������ɕϊ�����
 * �����ɂ�1000�{����̂����������A������1024�{����
 *
 * @param   numeric $num
 * @param   string  $kmg
 * @return  float
 */
function p2_si2real($num, $kmg)
{
    $num = (float)$num;
    switch ($kmg[0]) {
        case 'G': case 'g': $num *= 1024;
        case 'M': case 'm': $num *= 1024;
        case 'M': case 'k': $num *= 1024;
    }
    return $num;
}

// }}}
// {{{ p2_mb_basename()

/**
 * �}���`�o�C�g�Ή���basename()
 *
 * @param   string $path
 * @param   string $encoding
 * @return  string
 */
function p2_mb_basename($path, $encoding = 'SJIS-win')
{
    if (DIRECTORY_SEPARATOR != '/') {
        $path = str_replace(DIRECTORY_SEPARATOR, '/', $path);
    }
    if (!mb_substr_count($path, '/', $encoding)) {
        return $path;
    }
    $len = mb_strlen($path, $encoding);
    $pos = mb_strrpos($path, '/', $encoding);
    return mb_substr($path, $pos + 1, $len - $pos, $encoding);
}

// }}}
// {{{ p2_mb_ereg_count()

/**
 * �}���`�o�C�g���K�\���Ƀ}�b�`�����񐔂�Ԃ�
 *
 * @param   string $pattern
 * @param   string $string
 * @param   string $option
 * @return  int
 */
function p2_mb_ereg_count($pattern, $string, $option = null)
{
    if ($option === null) {
        if (!mb_ereg_search_init($string, $pattern)) {
            return false;
        }
    } else {
        if (!mb_ereg_search_init($string, $pattern, $option)) {
            return false;
        }
    }

    $i = 0;

    while (mb_ereg_search()) {
        $i++;
    }

    return $i;
}

// }}}
// {{{ p2_set_filtering_word()

/**
 * �t�B���^�}�b�`���O�p�̃O���[�o���ϐ���ݒ肷��
 *
 * @param   string $word
 * @param   string $method
 * @return  string
 */
function p2_set_filtering_word($word, $method = 'regex')
{
    $word_fm = StrCtl::wordForMatch($word, $method);

    if (strlen($word_fm) == 0) {
        $word_fm = null;
        $GLOBALS['word_fm'] = null;
        $GLOBALS['words_fm'] = array();
    } elseif ($method == 'just' || $method == 'regex') {
        $GLOBALS['word_fm'] = $word_fm;
        $GLOBALS['words_fm'] = array($word_fm);
    } elseif (P2_MBREGEX_AVAILABLE == 1) {
        $GLOBALS['word_fm'] = mb_ereg_replace('\\s+', '|', $word_fm);
        $GLOBALS['words_fm'] = mb_split('\\s+', $word_fm);
    } else {
        $GLOBALS['word_fm'] = preg_replace('/\\s+/', '|', $word_fm);
        $GLOBALS['words_fm'] = preg_split('/\\s+/', $word_fm);
    }

    return $word_fm;
}

// }}}
// {{{ p2_normalize()

if (extension_loaded('intl')) {
    /**
     * Normalizer�N���X���g�������K���֐�
     *
     * @param   string $str
     * @return  string
     */
    function p2_normalize($str)
    {
        return strtolower(Normalizer::normalize(mb_convert_encoding(
                $str, 'UTF-8', 'SJIS-win'), Normalizer::NFKC));
    }
} else {
    /**
     * �������K���Ȑ��K���֐�
     *
     * @param   string $str
     * @return  string
     */
    function p2_normalize($str)
    {
        return mb_strtolower(mb_convert_kana(mb_convert_encoding(
                $str, 'UTF-8', 'SJIS-win'), 'KVas', 'UTF-8'), 'UTF-8');
    }
}

// }}}
// {{{ p2_wakati()

/**
 * �������K���ȕ����������֐�
 *
 * @param   string $str
 * @return  array
 */
function p2_wakati($str)
{
    $str = preg_replace(P2_REGEX_WAKATI, '$0 ', p2_normalize($str));
    return preg_split('/\\s+/u', $str, -1, PREG_SPLIT_NO_EMPTY);
}

// }}}
// {{{ p2_get_highlighting_regex

/**
 * p2_wakati()�̌��ʓ��A�L�[���[�h�̔z�񂩂�n�C���C�g�p�̐��K�\���𐶐�����
 *
 * @param   array $words
 * @return  string
 */
function p2_get_highlighting_regex(array $words)
{
    $featured_words = array_filter($words, '_p2_get_highlighting_regex_filter');
    if (count($featured_words) == 0) {
        $featured_words = $words;
    }
    //rsort($featured_words, SORT_STRING);

    $pattern = mb_convert_encoding(implode(' ', $featured_words), 'SJIS-win', 'UTF-8');
    return str_replace(' ', '|', StrCtl::wordForMatch($pattern, 'or'));

}

// }}}
// {{{ _p2_get_highlighting_regex_filter

/**
 * p2_get_highlighting_regex()����Ăяo�����R�[���o�b�N�֐�
 *
 * @param   string $str
 * @return  bool
 */
function _p2_get_highlighting_regex_filter($str)
{
    if (preg_match(P2_REGEX_KANJI, $str)) {
        return true;
    } elseif (mb_strlen($str, 'UTF-8') > 1 && preg_match(P2_REGEX_WAKATI, $str)) {
        return true;
    } else {
        return false;
    }
}

// }}}
// {{{ p2_combine_nfd_kana()

/**
 * Safari ����A�b�v���[�h���ꂽ�t�@�C�����̕���������␳����֐�
 * ����+���_�E����+�����_���ꕶ���ɂ܂Ƃ߂� (NFD �Ő��K�����ꂽ ���� �� NFC �ɂ���)
 * ���o�͂̕����R�[�h��UTF-8
 *
 * @param   string $str
 * @return  string
 */
function p2_combine_nfd_kana($str)
{
    if (extension_loaded('intl')) {
        return Normalizer::normalize($str, Normalizer::NFC);
    }
    return preg_replace_callback(P2_REGEX_NFD_KANA, '_p2_combine_nfd_kana', $str);
}

// }}}
// {{{ _p2_combine_nfd_kana()

/**
 * p2_combine_nfd_kana()����Ăяo�����R�[���o�b�N�֐�
 *
 * @param   array $m
 * @return  string
 */
function _p2_combine_nfd_kana($m)
{
    if ($m[1]) {
        $C = unpack('C*', $m[1]);
        $C[3] += 1;
    } elseif ($m[2]) {
        $C = unpack('C*', $m[2]);
        $C[3] += 2;
    } else {
        return $m[0]; // ���肦�Ȃ�
    }

    return pack('C*', $C[1], $C[2], $C[3]);
}

// }}}
// {{{ p2_correct_css_fonts()

/**
 * �X�^�C���V�[�g�̃t�H���g�w��𒲐�����
 *
 * @param string|array $fonts
 * @return string
 */
function p2_correct_css_fontfamily($fonts)
{
    if (is_string($fonts)) {
        $fonts = preg_split('/(["\'])?\\s*,\\s*(?(1)\\1)/', trim($fonts, " \t\"'"));
    } elseif (!is_array($fonts)) {
        return '';
    }
    $fonts = '"' . implode('","', $fonts) . '"';
    $fonts = preg_replace('/"(serif|sans-serif|cursive|fantasy|monospace)"/', '\\1', $fonts);
    return trim($fonts, '"');
}

// }}}
// {{{ p2_correct_css_color()

/**
 * �X�^�C���V�[�g�̐F�w��𒲐�����
 *
 * @param   string $color
 * @return  string
 */
function p2_correct_css_color($color)
{
    return preg_replace('/^#([0-9A-F])([0-9A-F])([0-9A-F])$/i', '#$1$1$2$2$3$3', $color);
}

// }}}
// {{{ p2_escape_css_url()

/**
 * �X�^�C���V�[�g��URL���G�X�P�[�v����
 *
 * CSS�œ��ɈӖ��̂���g�[�N���ł���󔒕����A�V���O���N�H�[�g�A
 * �_�u���N�H�[�g�A���ʁA�o�b�N�X���b�V����URL�G���R�[�h����
 *
 * @param   string $url
 * @return  string
 */
function p2_escape_css_url($url)
{
    if (strpos($url, chr(0)) !== false) {
        return '';
    }
    return str_replace(array( "\t",  "\n",  "\r",   ' ',   '"',   "'",   '(',   ')',  '\\'),
                       array('%09', '%0A', '%0D', '%20', '%22', '%27', '%28', '%29', '%5C'),
                       $url);
}

// }}}
// {{{ p2_stream_eof()

/**
 * �^�C���A�E�g�`�F�b�N��feof()
 *
 * @param   stream  $fp
 * @param   boolean &$timed_out
 * @return  boolean
 */
function p2_stream_eof($fp, &$timed_out = false)
{
    $info = stream_get_meta_data($fp);
    $timed_out = $info['timed_out'];
    if (feof($fp) || $timed_out) {
        return true;
    } else {
        return false;
    }
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