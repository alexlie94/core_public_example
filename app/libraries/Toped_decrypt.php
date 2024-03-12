<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use \phpseclib3\Crypt\PublicKeyLoader;

class Toped_decrypt
{

	public function __construct()
	{
		$this->ci = &get_instance();
	}

	public function loadRsaPrivateKeyPem()
	{
		$kunciPrivat = '
    	-----BEGIN PRIVATE KEY-----
		MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCOfPzHNv6RbRRo
		dgPwoCHirI18tvjAZp9u51fEUJEb32uLIo6lJL1bi2YiigcOdg+2kwMVgmxGaTLx
		s6q6EoBaYFeCZiEA1DBB+mMCQLRpnr96Jszp37/nuAG8iyAhiLxoIgCr2h7ZWh6H
		alUOi+I+g8Scd3VX14VA+0NjYddaW3H4oAZtczziKex/KLuy5dmchGaPz2rfud+a
		RYPlBw2/ofNQ0150cMGmYg7oJw/Fx0ZFU/4ufcSmpn1h410au0MoiYxCclx67qoM
		NK95IoQAEBhzqBoaCBIEdnV1DH+DjUdSSmnt1I/8ZiUZHsSLvhyM9FPh6SeiYFmP
		aCTtUmJDAgMBAAECggEAC6MgoAKZUvZGMuvkkXv6mIFAQv3MniYjglLJo8Jyv4SF
		S4VEgi9YecgOTObaAa/Zw68JEvImdq+z1fK3EGBfdEInE4LU0jUCRfk1qeLv+Spa
		eBcAKF+6VCn+llAEjUiWHqkcRezYFSdbk/K2xKvfC/0GC0NkZYq4GuEKlWzRwlLT
		ZJ6zSN7Ox8YKHBIU9TnZ+zBps8Em5r4gY1jE410asLRr1sazZjGsz3H73glLLC02
		jATS3CHf1Ja2vWjsLnWr5+nJdc8kt+4WmI7hwk5Gznn5pcS/78gK3DVGXLyn/uW+
		pYuapOSgGSpcSYbFbffWv8wu1iiav/9+Kcqoba9XKQKBgQC1cm3FAwXnTsvPkP8Z
		duxom2b+o/V3g90BgPkGKwAMMrIa/VkwjXaDvGdkYRbJ5Oz1w6SsxFB/X0pQwJNt
		DvUo/Dnx+keKhINwnFRQrqDJjXw3p4baiAGcUtvC0PF7Q4A8+OMRtHqNauD3Negv
		MiaX32BcWpUt368Ee8tVvfJx6wKBgQDJCKq9NyajGeSQPRgB+LB4P5VdMyVqetSD
		65FN9sT6DPeprylv4txZXI+u/6Fr2vOUuOiZFljLxe9fogWbwStD4Aovqx5iL0u0
		UwmC3k5Oxj1NpHw7Q68MDaq9pBSrEnWRETH1mkMsKjzFDcDnh/jo61PMkAka5oc8
		YjoCgxvjCQKBgDfN5ay97UbimRRRh+ORC8xwXxDZ5FjJmWMpA22+M9mMjfJ3EMM5
		/sUtYK8inRui6K39adwo1IkYCYckczMLcqMUT834J6CWeMZdjiDyVEko1pdXUsZL
		JhOp+CTGlj5O2FXoRg3f8AEmaeIeFjbNucjjzoMY6OCe9vjuCe9o5/PZAoGAYht/
		r42tpmUpfRbDk5q5DEYDb5NZTCibc7HPnJu0L0+Kwd2AvGCbJmUcncuVDFyzCL5g
		jO0x8mgrVKFuWT+hDKPWdMLutf3c1Si8+Ifi8WCfZDfEhOuYDqrQy3G6wG3mwCAD
		IJfi9je6pnP600MxPwIxSCB2wREFqHtLUhSH1HkCgYAIHAOzAcAuM0x2s4cbVZnf
		WOvj6QuzOSAScZh5CZziz880/EV27USvWYGxTQ5cBD9arH29NLsY4etAta3p8hnj
		dwm9IgHgcwzuhL2cVljAzwfX2iWeeogiuvHHOwbelfXUs1RxqA/zpmkLcewFQDHd
		I1ZIAe7h3zUAUpE1Or4k8Q==
		-----END PRIVATE KEY-----
	';

		// Menghapus spasi di depan setiap baris
		$kunciPrivatTanpaSpasi = preg_replace('/^\s+/m', '', $kunciPrivat);
		return $kunciPrivatTanpaSpasi;
	}

	public function rsaDecryptionOaepSha256($privateKey, $decodesecret)
	{
		$rsa = PublicKeyLoader::load($privateKey)
			->withHash('sha256')
			->withMGFHash('sha256');
		return $rsa->decrypt($decodesecret);
	}

	public function getContent($secret, $content)
	{
		// pre($this->loadRsaPrivateKeyPem());
		$decryptedsecret 	= $this->rsaDecryptionOaepSha256($this->loadRsaPrivateKeyPem(), base64_decode($secret));
		$bcontent 		 	= base64_decode($content);
		$bnonce 		 	= substr($bcontent, strlen($bcontent) - 12, strlen($bcontent));
		$bcipher 			= substr($bcontent, 0, strlen($bcontent) - 12);

		// default tag
		$taglength 			= 16;
		$tag 				= substr($bcipher, strlen($bcipher) - $taglength, strlen($bcipher));
		$acipher 			= substr($bcipher, 0, strlen($bcipher) - $taglength);

		$result = openssl_decrypt(
			$acipher,
			"aes-256-gcm",
			$decryptedsecret,
			OPENSSL_RAW_DATA,
			$bnonce,
			$tag
		);
		return json_decode($result);
	}
}
