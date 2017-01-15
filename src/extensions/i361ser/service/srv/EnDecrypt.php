<?php
class EnDecrypt
{
	var $iv; //偏移量
	
	function DES( $key, $iv=0 ) {
	//key长度8例如:1234abcd
		$this->key = $key;
		if( $iv == 0 ) {
			$this->iv = $key; //默认以$key 作为 iv
		} else {
			$this->iv = $iv; //mcrypt_create_iv ( mcrypt_get_block_size (MCRYPT_DES, MCRYPT_MODE_CBC), MCRYPT_DEV_RANDOM );
		}
	}
	
	function encrypt($str,$key) {
	//加密，返回大写十六进制字符串
		$size = mcrypt_get_block_size ( MCRYPT_DES, MCRYPT_MODE_CBC );
		$str = $this->pkcs5Pad ( $str, $size );
		return strtoupper( bin2hex( mcrypt_cbc(MCRYPT_DES, $this->key, $str, MCRYPT_ENCRYPT, $this->iv ) ) );
	}
	
	function decrypt($str,$key) {
	//解密
		$strBin = $this->hex2bin( strtolower( $str ) );
		$str = mcrypt_cbc( MCRYPT_DES, $this->key, $strBin, MCRYPT_DECRYPT, $this->iv );
		$str = $this->pkcs5Unpad( $str );
		return $str;
	}
	
	function hex2bin($hexData) {
		$binData = "";
		for($i = 0; $i < strlen ( $hexData ); $i += 2) {
			$binData .= chr ( hexdec ( substr ( $hexData, $i, 2 ) ) );
		}
		return $binData;
	}

	function pkcs5Pad($text, $blocksize) {
		$pad = $blocksize - (strlen ( $text ) % $blocksize);
		return $text . str_repeat ( chr ( $pad ), $pad );
	}
	
	function pkcs5Unpad($text) {
		$pad = ord ( $text {strlen ( $text ) - 1} );
		if ($pad > strlen ( $text ))
			return false;
		if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)
			return false;
		return substr ( $text, 0, - 1 * $pad );
	}
	
	
    # --- 加密 ---
    # 密钥应该是随机的二进制数据，
    # 开始使用 scrypt, bcrypt 或 PBKDF2 将一个字符串转换成一个密钥
    # 密钥是 16 进制字符串格式
    function AesEncode($plaintext,$keys){
          $key =mb_convert_encoding($keys, "UTF-8", "auto");
         # 显示 AES-128, 192, 256 对应的密钥长度：
         #16，24，32 字节。
         //$key_size =  strlen($key);
         # 为 ECB 模式创建随机的初始向量
         //$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
         //$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
         # 创建和 AES 兼容的密文（Rijndael 分组大小 = 128）
         # 仅适用于编码后的输入不是以 00h 结尾的
         # （因为默认是使用 0 来补齐数据）
        $plaintext=mb_convert_encoding($plaintext, "UTF-8", "auto");
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_ECB);
         # 将初始向量附加在密文之后，以供解密时使用
        //$ciphertext =$ciphertext;
        # 对密文进行 base64 编码
        $ciphertext_base64 = base64_encode($ciphertext);
        return  $ciphertext_base64;
    }
    
    function AesDecode($ciphertext_base64,$keys){
          $key =mb_convert_encoding($keys,"UTF-8");
          //$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
          # === 警告 ===
          # 密文并未进行完整性和可信度保护，
          # 所以可能遭受 Padding Oracle 攻击。
          # --- 解密 ---
          $ciphertext_dec = base64_decode($ciphertext_base64);
          # 初始向量大小，可以通过 mcrypt_get_iv_size() 来获得
          //$iv_dec = substr($ciphertext_dec, 0, $iv_size);
          # 获取除初始向量外的密文
          //$ciphertext_dec = substr($ciphertext_dec, $iv_size);
          # 可能需要从明文末尾移除 0
          $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_ECB);
          return  $plaintext_dec;
    }
}

?>