<?php

/**
* Fast, light and safe Cache Class
*
* Cache_Lite is a fast, light and safe cache system. It's optimized
* for file containers. It is fast and safe (because it uses file
* locking and/or anti-corruption tests).
*
* Memory Caching is from an original idea of
* Mike BENOIT <ipso@snappymail.ca>
*
* @package Cache_Lite
* @category Caching
*/

define('CACHE_LITE_ERROR_RETURN', 1);
define('CACHE_LITE_ERROR_DIE', 8);

class Cache_Lite
{

    // --- Private properties ---

    /**
    * Directory where to put the cache arquivos
    * (make sure to add a trailing slash)
    *
    * @var string $_cacheDir
    */
    public $_cacheDir = '/tmp/';

    /**
    * Enable / disable caching
    *
    * (can be very usefull for the debug of cached scripts)
    *
    * @var boolean $_caching
    */
    public $_caching = true;

    /**
    * Cache lifetime (in segundos)
    *
    * @var int $_lifeTime
    */
    public $_lifeTime = 3600;

    /**
    * Enable / disable fileLocking
    *
    * (can avoid cache corruption under bad circumstances)
    *
    * @var boolean $_fileLocking
    */
    public $_fileLocking = true;

    /**
    * Timestamp of the last valid cache
    *
    * @var int $_refreshTime
    */
    public $_refreshTime;

    /**
    * File name (with path)
    *
    * @var string $_file
    */
    public $_file;

    /**
    * Enable / disable write control (the cache is read just after writing to detect corrupt entries)
    *
    * Enable write control will lightly slow the cache writing but not the cache reading
    * Write control can detect some corrupt cache arquivos but maybe it's not a perfect control
    *
    * @var boolean $_writeControl
    */
    public $_writeControl = true;

    /**
    * Enable / disable read control
    *
    * If habilitado, a control key is embeded in cache file and this key is compared with the one
    * calculated after the reading.
    *
    * @var boolean $_writeControl
    */
    public $_readControl = true;

    /**
    * Type of read control (only if read control is habilitado)
    *
    * Available valores are :
    * 'md5' for a md5 hash control (best but slowest)
    * 'crc32' for a crc32 hash control (lightly less safe but faster, better choice)
    * 'strlen' for a length only test (fastest)
    *
    * @var boolean $_readControlType
    */
    public $_readControlType = 'crc32';

    /**
    * Pear error mode (when raiseError is called)
    *
    * (see PEAR doc)
    *
    * @see setToDebug()
    * @var int $_pearErrorMode
    */
    public $_pearErrorMode = CACHE_LITE_ERROR_RETURN;
    
    /**
    * Current cache id
    *
    * @var string $_id
    */
    public $_id;

    /**
    * Current cache grupo
    *
    * @var string $_grupo
    */
    public $_grupo;

    /**
    * Enable / Disable "Memory Caching"
    *
    * NB : There is no lifetime for memory caching ! 
    *
    * @var boolean $_memoryCaching
    */
    public $_memoryCaching = false;

    /**
    * Enable / Disable "Only Memory Caching"
    * (be carefull, memory caching is "beta quality")
    *
    * @var boolean $_onlyMemoryCaching
    */
    public $_onlyMemoryCaching = false;

    /**
    * Memory caching array
    *
    * @var array $_memoryCachingArray
    */
    public $_memoryCachingArray = array();

    /**
    * Memory caching counter
    *
    * @var int $memoryCachingCounter
    */
    public $_memoryCachingCounter = 0;

    /**
    * Memory caching limit
    *
    * @var int $memoryCachingLimit
    */
    public $_memoryCachingLimit = 1000;
    
    /**
    * File Name protection
    *
    * if set to true, you can use any cache id or grupo name
    * if set to false, it can be faster but cache ids and grupo names
    * will be used directly in cache file names so be carefull with
    * special characters...
    *
    * @var boolean $nomeArquivoProtection
    */
    public $_fileNameProtection = true;
    
    /**
    * Enable / disable automatic serialization
    *
    * it can be used to save directly datas which aren't strings
    * (but it's slower)    
    *
    * @var boolean $_serialize
    */
    public $_automaticSerialization = false;
    
    // --- Public methods ---

    /**
    * Constructor
    *
    * $opcoes is an assoc. Available options are :
    * $opcoes = array(
    *     'cacheDir' => directory where to put the cache arquivos (string),
    *     'caching' => enable / disable caching (boolean),
    *     'lifeTime' => cache lifetime in segundos (int),
    *     'fileLocking' => enable / disable fileLocking (boolean),
    *     'writeControl' => enable / disable write control (boolean),
    *     'readControl' => enable / disable read control (boolean),
    *     'readControlType' => type of read control 'crc32', 'md5', 'strlen' (string),
    *     'pearErrorMode' => pear error mode (when raiseError is called) (cf PEAR doc) (int),
    *     'memoryCaching' => enable / disable memory caching (boolean),
    *     'onlyMemoryCaching' => enable / disable only memory caching (boolean),
    *     'memoryCachingLimit' => max nbr of records to store into memory caching (int),
    *     'fileNameProtection' => enable / disable automatic file name protection (boolean),
    *     'automaticSerialization' => enable / disable automatic serialization (boolean)
    * );
    *
    * @param array $opcoes options
    * @access public
    */
    public function __construct( $opcoes = array(NULL))
    {
        $availableOptions = array('automaticSerialization', 'fileNameProtection', 'memoryCaching', 'onlyMemoryCaching', 'memoryCachingLimit', 'cacheDir', 'caching', 'lifeTime', 'fileLocking', 'writeControl', 'readControl', 'readControlType', 'pearErrorMode');
        foreach($opcoes as $chave => $valor) {
            if(in_array($chave, $availableOptions)) {
                $property = '_'.$chave;
                $this->$property = $valor;
            }
        }
        $this->_refreshTime = time() - $this->_lifeTime;
    }
    
    /**
    * Test if a cache is available and (if yes) return it
    *
    * @param string $id cache id
    * @param string $grupo name of the cache grupo
    * @param boolean $doNotTestCacheValidity if set to true, the cache validity won't be tested
    * @return string data of the cache (or false if no cache available)
    * @access public
    */
    public function get( $id, $grupo = 'default', $doNotTestCacheValidity = false)
    {
        $this->_id = $id;
        $this->_grupo = $grupo;
        $data = false;
        if ($this->_caching) {
            $this->_setFileName($id, $grupo);
            if ($this->_memoryCaching) {
                if (isset($this->_memoryCachingArray[$this->_file])) {
                    if ($this->_automaticSerialization) {
                        return unserialize($this->_memoryCachingArray[$this->_file]);
                    } else {
                        return $this->_memoryCachingArray[$this->_file];
                    }
                } else {
                    if ($this->_onlyMemoryCaching) {
                        return false;
                    }
                }
            }
            if ($doNotTestCacheValidity) {
                if (file_exists($this->_file)) {
                    $data = $this->_read();
                }
            } else {
                if ((file_exists($this->_file)) && (@filemtime($this->_file) > $this->_refreshTime)) {
                    $data = $this->_read();
                }
            }
            if (($data) and ($this->_memoryCaching)) {
                $this->_memoryCacheAdd($this->_file, $data);
            }
            if (($this->_automaticSerialization) and (is_string($data))) {
                $data = unserialize($data);
            }
            return $data;
        }
        return false;
    }
    
    /**
    * Save some data in a cache file
    *
    * @param string $data data to put in cache (can be another type than strings if automaticSerialization is on)
    * @param string $id cache id
    * @param string $grupo name of the cache grupo
    * @return boolean true if no problem
    * @access public
    */
    public function save( $data, $id = NULL, $grupo = 'default')
    {
        if ($this->_caching) {
            if ($this->_automaticSerialization) {
                $data = serialize($data);
            }
            if (isset($id)) {
                $this->_setFileName($id, $grupo);
            }
            if ($this->_memoryCaching) {
                $this->_memoryCacheAdd($this->_file, $data);
                if ($this->_onlyMemoryCaching) {
                    return true;
                }
            }
            if ($this->_writeControl) {
                if (!$this->_writeAndControl($data)) {
                    @touch($this->_file, time() - 2*abs($this->_lifeTime));
                    return false;
                } else {
                    return true;
                }
            } else {
                return $this->_write($data);
            }
        }
        return false;
    }

    /**
    * Remove a cache file
    *
    * @param string $id cache id
    * @param string $grupo name of the cache grupo
    * @return boolean true if no problem
    * @access public
    */
    public function remove( $id, $grupo = 'default')
    {
        $this->_setFileName($id, $grupo);
        if ($this->_memoryCaching) {
            if (isset($this->_memoryCachingArray[$this->_file])) {
                unset($this->_memoryCachingArray[$this->_file]);
                $this->_memoryCachingCounter = $this->_memoryCachingCounter - 1;
            }
            if ($this->_onlyMemoryCaching) {
                return true;
            }
        }
        if (!@unlink($this->_file)) {
            $this->raiseError('Cache_Lite : Unable to remove cache !', -3);   
            return false;
        }
        return true;
    }

    /**
    * Clean the cache
    *
    * if no grupo is specified all cache arquivos will be destroyed
    * else only cache arquivos of the specified grupo will be destroyed
    *
    * @param string $grupo name of the cache grupo
    * @return boolean true if no problem
    * @access public
    */
    public function clean( $grupo = false)
    {
        if ($this->_fileNameProtection) {
            $motif = ($grupo) ? 'cache_'.md5($grupo).'_' : 'cache_';
        } else {
            $motif = ($grupo) ? 'cache_'.$grupo.'_' : 'cache_';
        }
        if ($this->_memoryCaching) {
            while (list($chave, $valor) = each($this->_memoryCachingArray)) {
                if (strpos($chave, $motif, 0)) {
                    unset($this->_memoryCachingArray[$chave]);
                    $this->_memoryCachingCounter = $this->_memoryCachingCounter - 1;
                }
            }
            if ($this->_onlyMemoryCaching) {
                return true;
            }
        }
        if (!($dh = opendir($this->_cacheDir))) {
            $this->raiseError('Cache_Lite : Unable to open cache directory !', -4);
            return false;
        }
        while ($arquivo = readdir($dh)) {
            if (($arquivo != '.') && ($arquivo != '..')) {
                $arquivo = $this->_cacheDir.$arquivo;
                if (is_file($arquivo)) {
                    if (strpos($arquivo, $motif, 0)) {
                        if (!@unlink($arquivo)) {
                            $this->raiseError('Cache_Lite : Unable to remove cache !', -3);
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }
    
    /**
    * Set to debug mode
    *
    * When an error is found, the script will stop and the message will be displayed
    * (in debug mode only).
    *
    * @access public
    */
    public function setToDebug()
    {
        $this->_pearErrorMode = CACHE_LITE_ERROR_DIE;
    }

    /**
    * Set a new life time
    *
    * @param int $novoLifeTime new life time (in segundos)
    * @access public
    */
    public function setLifeTime( $novoLifeTime)
    {
        $this->_lifeTime = $novoLifeTime;
        $this->_refreshTime = time() - $novoLifeTime;
    }

    /**
    *
    * @access public
    */
    public function saveMemoryCachingState( $id, $grupo = 'default')
    {
        if ($this->_caching) {
            $array = array(
                'counter' => $this->_memoryCachingCounter,
                'array' => $this->_memoryCachingState
            );
            $data = serialize($array);
            $this->save($data, $id, $grupo);
        }
    }

    /**
    *
    * @access public
    */
    public function getMemoryCachingState( $id, $grupo = 'default', $doNotTestCacheValidity = false)
    {
        if ($this->_caching) {
            if ($data = $this->get($id, $grupo, $doNotTestCacheValidity)) {
                $array = unserialize($data);
                $this->_memoryCachingCounter = $array['counter'];
                $this->_memoryCachingArray = $array['array'];
            }
        }
    }
    
    /**
    * Return the cache last modification time
    *
    * BE CAREFUL : THIS METHOD IS FOR HACKING ONLY !
    *
    * @return int last modification time
    */
    public function lastModified() {
        return filemtime($this->_file);
    }
    
    /**
    * Trigger a PEAR error
    *
    * To improve performances, the PEAR.php file is included dynamically.
    * The file is so included only when an error is triggered. So, in most
    * cases, the file isn't included and perfs are much better.
    *
    * @param string $msg error message
    * @param int $code error code
    * @access public
    */
    public function raiseError( $msg, $code)
    {
        include_once('PEAR.php');
        PEAR::raiseError($msg, $code, $this->_pearErrorMode);
    }

    // --- Private methods ---

    /**
    *
    * @access private
    */
    public function _memoryCacheAdd( $id, $data)
    {
        $this->_memoryCachingArray[$this->_file] = $data;
        if ($this->_memoryCachingCounter >= $this->_memoryCachingLimit) {
            list($chave, $valor) = each($this->_memoryCachingArray);
            unset($this->_memoryCachingArray[$chave]);
        } else {
            $this->_memoryCachingCounter = $this->_memoryCachingCounter + 1;
        }
    }

    /**
    * Make a file name (with path)
    *
    * @param string $id cache id
    * @param string $grupo name of the grupo
    * @access private
    */
    public function _setFileName( $id, $grupo)
    {
        if ($this->_fileNameProtection) {
            $this->_file = ($this->_cacheDir.'cache_'.md5($grupo).'_'.md5($id));
        } else {
            $this->_file = $this->_cacheDir.'cache_'.$grupo.'_'.$id;
        }
    }
    
    /**
    * Read the cache file and return the content
    *
    * @return string content of the cache file
    * @access private
    */
    public function _read()
    {
        $fp = @fopen($this->_file, "rb");
        if ($this->_fileLocking) @flock($fp, LOCK_SH);
        if ($fp) {
            clearstatcache(); // because the filesize can be cached by PHP itself...
            $length = @filesize($this->_file);
            $mqr = false;
            //set_magic_quotes_runtime(0);
            if ($this->_readControl) {
                $hashControl = @fread($fp, 32);
                $length = $length - 32;
            } 
            $data = @fread($fp, $length);
            //set_magic_quotes_runtime($mqr);
            if ($this->_fileLocking) @flock($fp, LOCK_UN);
            @fclose($fp);
            if ($this->_readControl) {
                $hashData = $this->_hash($data, $this->_readControlType);
                if ($hashData != $hashControl) {
                    @touch($this->_file, time() - 2*abs($this->_lifeTime)); 
                    return false;
                }
            }
            return $data;
        }
        $this->raiseError('Cache_Lite : Unable to read cache !', -2);   
        return false;
    }
    
    /**
    * Write the given data in the cache file
    *
    * @param string $data data to put in cache
    * @return boolean true if ok
    * @access private
    */
    public function _write( $data)
    {
        $fp = @fopen($this->_file, "wb");
        if ($fp) {
            if ($this->_fileLocking) @flock($fp, LOCK_EX);
            if ($this->_readControl) {
                @fwrite($fp, $this->_hash($data, $this->_readControlType), 32);
            }
            $tamanho = strlen($data);
            @fwrite($fp, $data, $tamanho);
            if ($this->_fileLocking) @flock($fp, LOCK_UN);
            @fclose($fp);
            return true;
        }
        $this->raiseError('Cache_Lite : Unable to write cache !', -1);
        return false;
    }
    
    /**
    * Write the given data in the cache file and control it just after to avoir corrupted cache entries
    *
    * @param string $data data to put in cache
    * @return boolean true if the test is ok
    * @access private
    */
    public function _writeAndControl( $data)
    {
        $this->_write($data);
        $dataRead = $this->_read($data);
        return ($dataRead==$data);
    }
    
    /**
    * Make a control key with the string containing datas
    *
    * @param string $data data
    * @param string $controlType type of control 'md5', 'crc32' or 'strlen'
    * @return string control key
    * @access private
    */
    public function _hash( $data, $controlType)
    {
        switch ($controlType) {
        case 'md5':
            return md5($data);
        case 'crc32':
            return sprintf('% 32d', crc32($data));
        case 'strlen':
            return sprintf('% 32d', strlen($data));
        default:
            $this->raiseError('Unknown controlType ! (available valores are only \'md5\', \'crc32\', \'strlen\')', -5);
        }
    }
    
} 

?>
