<?php

/**
 * QR CODE Libraries
 *
 * @author Prasetyo.Adi (adi_broklak@yahoo.com)
 * @since September, 2015
 * @version 1.0
 * @package Code Igniter
 * @subpackage Libraries
 */
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Qrcode
{

	private $_instance;

	protected static $_path      = "libraries/Qr/data",  /* You must set path to data files. */
		$_imagePath = "libraries/Qr/image", /* You must set path to QRCode frame images. */
		$_versionUR = 40;      /* Upper limit for version. */

	/**
	 * Mask information (array with mask number and mask pattern)
	 * @var array
	 */
	protected $_maskData = array();

	/*
     * QR mode
     */
	const QR_MODE_NUM   = 1;    // numeric mode (0-9),  characters are encoded to 10bit length (max 7089 characters)
	const QR_MODE_AN    = 2;    // alpha-numeric mode (0-9A-Z $%*+-./:), 2 characters are encoded to 11 bit length (max 4296 characters)
	const QR_MODE_8     = 4;    // 8-bit data mode (max 2953 characters)

	/*
     * Error Correcting Code levels (Reed Salomon)
     * L...H -> 0...3 would probably make more sense but the ecc data files (qrv_x.dat) 
     * are named according the constants below
     */
	const QR_ECC_L = 1; // 7% of codewords can be restored
	const QR_ECC_M = 0; // 15% of codewords can be restored
	const QR_ECC_Q = 3; // 25% of codewords can be restored
	const QR_ECC_H = 2; // 30% of codewords can be restored

	/**
	 * Format information includes EC level and mask pattern indicator in a 15 bit long.
	 * - first 2 bits are EC level
	 * - next 3 bits define the mask pattern
	 * - last 10 bits is error correcting data which is Bose-Chaudhuri-Hocquenghem(BCH)(15,5)
	 * example: 0x77c4 = 111011111000100
	 * @var array
	 */
	public static $formatInformation = array(
		self::QR_ECC_L => array('0x77c4', '0x72f3', '0x7daa', '0x789d', '0x662f', '0x6318', '0x6c41', '0x6976'),
		self::QR_ECC_M => array('0x5412', '0x5125', '0x5e7c', '0x5b4b', '0x45f9', '0x40ce', '0x4f97', '0x4aa0'),
		self::QR_ECC_Q => array('0x355f', '0x3068', '0x3f31', '0x3a06', '0x24b4', '0x2183', '0x2eda', '0x2bed'),
		self::QR_ECC_H => array('0x1689', '0x13be', '0x1ce7', '0x19d0', '0x0762', '0x0255', '0x0d0c', '0x083b')
	);

	/**
	 * ECC levels
	 * @var array
	 */
	public static $eccLevels = array(
		'L' => self::QR_ECC_L,
		'M' => self::QR_ECC_M,
		'Q' => self::QR_ECC_Q,
		'H' => self::QR_ECC_H
	);


	/*
     * Default padding around symbol
     */
	// const DEFAULT_PADDING = 4;

	public static $_dataString, //URL encoded data
		$_errorCorrect, //L or M or Q or H   (default M)
		$_moduleSize, //(default PNG:4 JPEG:8)
		$_version, //1-40 or Auto select if you do not set
		$_imageType, //J:jpeg image , other: PNG image
		$_structureAppend_n,
		$_structureAppend_m,
		$_structureAppend_parity,
		$_structureAppend_originalData,
		$_dataCounter,
		$_dataValue,
		$_dataBits,
		$_eccLevel;

	public static $maxDataBits = array(
		self::QR_ECC_L => array(
			152, 272, 440, 640, 864, 1088, 1248, 1552, 1856, 2192,               // ECC L
			2592, 2960, 3424, 3688, 4184, 4712, 5176, 5768, 6360, 6888,
			7456, 8048, 8752, 9392, 10208, 10960, 11744, 12248, 13048, 13880,
			14744, 15640, 16568, 17528, 18448, 19472, 20528, 21616, 22496, 23648
		),
		self::QR_ECC_M => array(
			128, 224, 352, 512, 688, 864, 992, 1232, 1456, 1728,                 // ECC M
			2032, 2320, 2672, 2920, 3320, 3624, 4056, 4504, 5016, 5352,
			5712, 6256, 6880, 7312, 8000, 8496, 9024, 9544, 10136, 10984,
			11640, 12328, 13048, 13800, 14496, 15312, 15936, 16816, 17728, 18672
		),
		self::QR_ECC_Q => array(
			104, 176, 272, 384, 496, 608, 704, 880, 1056, 1232,                  // ECC Q
			1440, 1648, 1952, 2088, 2360, 2600, 2936, 3176, 3560, 3880,
			4096, 4544, 4912, 5312, 5744, 6032, 6464, 6968, 7288, 7880,
			8264, 8920, 9368, 9848, 10288, 10832, 11408, 12016, 12656, 13328
		),
		self::QR_ECC_H => array(
			72, 128, 208, 288, 368, 480, 528, 688, 800, 976,                     // ECC H
			1120, 1264, 1440, 1576, 1784, 2024, 2264, 2504, 2728, 3080,
			3248, 3536, 3712, 4112, 4304, 4768, 5024, 5288, 5608, 5960,
			6344, 6760, 7208, 7688, 7888, 8432, 8768, 9136, 9776, 10208
		)
	);

	/**
	 * Table with the capacity of symbols
	 * See Table 1 (pp.13) and Table 12-16 (pp.30-36), JIS X0510:2004.
	 * Extracted from libqrencode 3.0.3 (license LGPL 2.1) by Kentaro Fukuchi <fukuchi@megaui.net>
	 * 
	 * width: width of matrix code (modules)
	 *
	 * @var array
	 */
	public static $_matrixCapacity = array(
		1  => array('width' =>  21, 'words' =>    26, 'remainder' =>  0, 'ec' => array(7,   10,   13,   17)), // version 1
		2  => array('width' =>  25, 'words' =>    44, 'remainder' =>  7, 'ec' => array(10,   16,   22,   28)),
		3  => array('width' =>  29, 'words' =>    70, 'remainder' =>  7, 'ec' => array(15,   26,   36,   44)),
		4  => array('width' =>  33, 'words' =>   100, 'remainder' =>  7, 'ec' => array(20,   36,   52,   64)),
		5  => array('width' =>  37, 'words' =>   134, 'remainder' =>  7, 'ec' => array(26,   48,   72,   88)), // 5
		6  => array('width' =>  41, 'words' =>   172, 'remainder' =>  7, 'ec' => array(36,   64,   96,  112)),
		7  => array('width' =>  45, 'words' =>   196, 'remainder' =>  0, 'ec' => array(40,   72,  108,  130)),
		8  => array('width' =>  49, 'words' =>   242, 'remainder' =>  0, 'ec' => array(48,   88,  132,  156)),
		9  => array('width' =>  53, 'words' =>   292, 'remainder' =>  0, 'ec' => array(60,  110,  160,  192)),
		10 => array('width' =>  57, 'words' =>   346, 'remainder' =>  0, 'ec' => array(72,  130,  192,  224)), //10
		11 => array('width' =>  61, 'words' =>   404, 'remainder' =>  0, 'ec' => array(80,  150,  224,  264)),
		12 => array('width' =>  65, 'words' =>   466, 'remainder' =>  0, 'ec' => array(96,  176,  260,  308)),
		13 => array('width' =>  69, 'words' =>   532, 'remainder' =>  0, 'ec' => array(104,  198,  288,  352)),
		14 => array('width' =>  73, 'words' =>   581, 'remainder' =>  3, 'ec' => array(120,  216,  320,  384)),
		15 => array('width' =>  77, 'words' =>   655, 'remainder' =>  3, 'ec' => array(132,  240,  360,  432)), //15
		16 => array('width' =>  81, 'words' =>   733, 'remainder' =>  3, 'ec' => array(144,  280,  408,  480)),
		17 => array('width' =>  85, 'words' =>   815, 'remainder' =>  3, 'ec' => array(168,  308,  448,  532)),
		18 => array('width' =>  89, 'words' =>   901, 'remainder' =>  3, 'ec' => array(180,  338,  504,  588)),
		19 => array('width' =>  93, 'words' =>   991, 'remainder' =>  3, 'ec' => array(196,  364,  546,  650)),
		20 => array('width' =>  97, 'words' =>  1085, 'remainder' =>  3, 'ec' => array(224,  416,  600,  700)), //20
		21 => array('width' => 101, 'words' =>  1156, 'remainder' =>  4, 'ec' => array(224,  442,  644,  750)),
		22 => array('width' => 105, 'words' =>  1258, 'remainder' =>  4, 'ec' => array(252,  476,  690,  816)),
		23 => array('width' => 109, 'words' =>  1364, 'remainder' =>  4, 'ec' => array(270,  504,  750,  900)),
		24 => array('width' => 113, 'words' =>  1474, 'remainder' =>  4, 'ec' => array(300,  560,  810,  960)),
		25 => array('width' => 117, 'words' =>  1588, 'remainder' =>  4, 'ec' => array(312,  588,  870, 1050)), //25
		26 => array('width' => 121, 'words' =>  1706, 'remainder' =>  4, 'ec' => array(336,  644,  952, 1110)),
		27 => array('width' => 125, 'words' =>  1828, 'remainder' =>  4, 'ec' => array(360,  700, 1020, 1200)),
		28 => array('width' => 129, 'words' =>  1921, 'remainder' =>  3, 'ec' => array(390,  728, 1050, 1260)),
		29 => array('width' => 133, 'words' =>  2051, 'remainder' =>  3, 'ec' => array(420,  784, 1140, 1350)),
		30 => array('width' => 137, 'words' =>  2185, 'remainder' =>  3, 'ec' => array(450,  812, 1200, 1440)), //30
		31 => array('width' => 141, 'words' =>  2323, 'remainder' =>  3, 'ec' => array(480,  868, 1290, 1530)),
		32 => array('width' => 145, 'words' =>  2465, 'remainder' =>  3, 'ec' => array(510,  924, 1350, 1620)),
		33 => array('width' => 149, 'words' =>  2611, 'remainder' =>  3, 'ec' => array(540,  980, 1440, 1710)),
		34 => array('width' => 153, 'words' =>  2761, 'remainder' =>  3, 'ec' => array(570, 1036, 1530, 1800)),
		35 => array('width' => 157, 'words' =>  2876, 'remainder' =>  0, 'ec' => array(570, 1064, 1590, 1890)), //35
		36 => array('width' => 161, 'words' =>  3034, 'remainder' =>  0, 'ec' => array(600, 1120, 1680, 1980)),
		37 => array('width' => 165, 'words' =>  3196, 'remainder' =>  0, 'ec' => array(630, 1204, 1770, 2100)),
		38 => array('width' => 169, 'words' =>  3362, 'remainder' =>  0, 'ec' => array(660, 1260, 1860, 2220)),
		39 => array('width' => 173, 'words' =>  3532, 'remainder' =>  0, 'ec' => array(720, 1316, 1950, 2310)),
		40 => array('width' => 177, 'words' =>  3706, 'remainder' =>  0, 'ec' => array(750, 1372, 2040, 2430)) //40
	);

	public static $max_codewords = array(
		0,
		26, 44, 70, 100, 134, 172, 196, 242,
		292, 346, 404, 466, 532, 581, 655, 733, 815, 901, 991, 1085, 1156,
		1258, 1364, 1474, 1588, 1706, 1828, 1921, 2051, 2185, 2323, 2465,
		2611, 2761, 2876, 3034, 3196, 3362, 3532, 3706
	);

	public static $matrix_remain_bit = array(
		0,
		0, 7, 7, 7, 7, 7, 0, 0, 0, 0, 0, 0, 0, 3, 3, 3, 3, 3, 3, 3,
		4, 4, 4, 4, 4, 4, 4, 3, 3, 3, 3, 3, 3, 3, 0, 0, 0, 0, 0, 0
	);

	public function __construct()
	{
		// $this->_instance = get_instance();
	}

	public function setDataString($value)
	{
		self::$_dataString = $value;
	}

	public function setErrorCorrect($value)
	{
		self::$_errorCorrect = $value;
	}


	/**
	 * Set ECC level
	 * @param string $level
	 */
	public function setEccLevel($value)
	{
		if ($value != '') {
			if (array_key_exists($value, self::$eccLevels)) {
				// value specified in letter format ('L', 'M', ...)
				self::$_eccLevel = self::$eccLevels[strtoupper($value)];
			} else if (in_array($value, self::$eccLevels)) {
				// value specified in numeric format (0,1,...)
				self::$_eccLevel = $value;
			}
		} else {
			die("Invalid value for the ECC Level");
		}
	}

	public function setModuleSize($value)
	{
		if ($value > 0) {
			self::$_moduleSize = $value;
		} else {
			if (self::$_imageType == "jpeg") {
				self::$_moduleSize = 8;
			} else {
				self::$_moduleSize = 4;
			}
		}
	}

	public function setVersion($value)
	{
		self::$_version = $value;
	}

	public function setImageType($value)
	{
		if (strtolower($value) == "j") {
			self::$_imageType = "jpeg";
		} else {
			self::$_imageType = "png";
		}
	}

	public function setStructureAppendN($value)
	{
		self::$_structureAppend_n = $value;
	}

	public function setStructureAppendM($value)
	{
		self::$_structureAppend_m = $value;
	}

	public function setStructureAppendParity($value)
	{
		self::$_structureAppend_parity = $value;
	}

	public function setStructureAppendOriginalData($value)
	{
		self::$_structureAppend_originalData = $value;
	}

	/**
	 * Function that identifies the QR mode
	 * @return string
	 */
	protected function _identifyMode()
	{
		if (preg_match('/[^0-9]/', self::$_dataString)) { // if contains non-numerical characters
			if (preg_match('/[^0-9A-Z \$\*\%\+\-\.\/\:]/', self::$_dataString)) {
				return self::QR_MODE_8;
			} else {
				return self::QR_MODE_AN;
			}
		} else {
			return self::QR_MODE_NUM;
		}
	}

	/**
	 * Retrieve matrix capacity remainder
	 * @param int $version
	 */
	public static function getMatrixCapacityRemainder($version)
	{
		return self::$_matrixCapacity[$version]['remainder'];
	}


	/**
	 * Retrieve matrix capacity words
	 * @param int $version
	 */
	public static function getMatrixCapacityWords($version)
	{
		return self::$_matrixCapacity[$version]['words'];
	}

	protected function _getMatrixDimension($version = null)
	{
		if (is_null($version)) {
			$version = self::$_version;
		}
		return 17 + ($version << 2);
	}


	/**
	 * Mask selection (http://www.swetake.com/qr/qr5_en.html)
	 * If the density of one color is too high or a pattern similar to "finder patterns" 
	 * appears, the decoder application will have trouble decoding.
	 * To prevent this, we select a mask from 8 different patterns (000 ... 111).
	 * 
	 * Of the 8 masks, select the one that (probably) gives the best result (lowest weight).
	 * For each mask, 4 aspects are measured and evaluated:
	 *   CHARACTERISTICS                                        CONDITION                           WEIGHT
	 * - concatenation of same color in a line or a column      count of modules=(5+i)              3+i
	 * - module block of same color                             block size 2*2                      3
	 * - 1:1:3:1:1(dark:bright:dark:bright:dark)pattern in a line or a column                       40
	 * - percentage of dark modules                             from 50�(5+k)% to 50�(5+(k+1))%     10*k
	 *
	 * @param array $matrix_content
	 * @return array Array countaining mask number and mask pattern
	 */
	private function _selectBestMaskPattern($matrix_content, $byte_num)
	{
		$matrix_dimension = $this->_getMatrixDimension();
		$min_demerit_score = 0;
		$hor_master = "";
		$ver_master = "";

		for ($k = 0; $k < $matrix_dimension; $k++) {
			for ($l = 0; $l < $matrix_dimension; $l++) {
				$hor_master = $hor_master . chr($matrix_content[$l][$k]);
				$ver_master = $ver_master . chr($matrix_content[$k][$l]);
			}
		}

		$all_matrix = pow($matrix_dimension, 2);

		for ($i = 0; $i < 8; $i++) {
			$demerit_n1 = 0;
			$ptn_temp = array();
			$bit = 1 << $i;
			$bit_r = (~$bit) & 255;
			$bit_mask = str_repeat(chr($bit), $all_matrix);
			$hor = $hor_master & $bit_mask;
			$ver = $ver_master & $bit_mask;

			$ver_shift1 = $ver . str_repeat(chr(170), $matrix_dimension);
			$ver_shift2 = str_repeat(chr(170), $matrix_dimension) . $ver;
			$ver_shift1_0 = $ver . str_repeat(chr(0), $matrix_dimension);
			$ver_shift2_0 = str_repeat(chr(0), $matrix_dimension) . $ver;
			$ver_or = chunk_split(~($ver_shift1 | $ver_shift2), $matrix_dimension, chr(170));
			$ver_and = chunk_split(~($ver_shift1_0 & $ver_shift2_0), $matrix_dimension, chr(170));

			$hor = chunk_split(~$hor, $matrix_dimension, chr(170));
			$ver = chunk_split(~$ver, $matrix_dimension, chr(170));
			$hor = $hor . chr(170) . $ver;

			$n1_search = "/" . str_repeat(chr(255), 5) . "+|" . str_repeat(chr($bit_r), 5) . "+/";
			$n3_search = chr($bit_r) . chr(255) . chr($bit_r) . chr($bit_r) . chr($bit_r) . chr(255) . chr($bit_r);

			$demerit_n3 = substr_count($hor, $n3_search) * 40;
			$demerit_n4 = floor(abs(((100 * (substr_count($ver, chr($bit_r)) / ($byte_num))) - 50) / 5)) * 10;

			$n2_search1 = "/" . chr($bit_r) . chr($bit_r) . "+/";
			$n2_search2 = "/" . chr(255) . chr(255) . "+/";
			$demerit_n2 = 0;
			preg_match_all($n2_search1, $ver_and, $ptn_temp);
			foreach ($ptn_temp[0] as $str_temp) {
				$demerit_n2 += (strlen($str_temp) - 1);
			}
			$ptn_temp = array();
			preg_match_all($n2_search2, $ver_or, $ptn_temp);
			foreach ($ptn_temp[0] as $str_temp) {
				$demerit_n2 += (strlen($str_temp) - 1);
			}
			$demerit_n2 *= 3;

			$ptn_temp = array();

			preg_match_all($n1_search, $hor, $ptn_temp);
			foreach ($ptn_temp[0] as $str_temp) {
				$demerit_n1 += (strlen($str_temp) - 2);
			}

			$demerit_score = $demerit_n1 + $demerit_n2 + $demerit_n3 + $demerit_n4;
			// mask with lower score is better
			if ($demerit_score <= $min_demerit_score || $i == 0) {
				$mask_number = $i;
				$min_demerit_score = $demerit_score;
			}
		}

		return array(
			'number' => $mask_number,
			'pattern' => 1 << $mask_number
		);
	}

	public function initialize()
	{
		$rawlDataString     = rawurldecode(self::$_dataString);
		$dataLength         = strlen($rawlDataString);

		// self::$_path        = APPPATH . self::$_path;
		// self::$_imagePath   = APPPATH . self::$_imagePath; 

		if (empty(self::$_imageType))
			$this->setImageType("png");

		if (empty(self::$_moduleSize))
			$this->setModuleSize(4);

		if (empty(self::$_version))
			$this->setVersion(2);

		// if( empty(self::$_errorCorrect) )
		//   $this->setErrorCorrect('M');

		if (empty(self::$_eccLevel))
			$this->setEccLevel('M');

		if (empty(self::$_structureAppend_n))
			$this->setStructureAppendN(0);

		if (empty(self::$_structureAppend_m))
			$this->setStructureAppendM(0);

		self::$_dataCounter = 0;

		if ($dataLength <= 0) {
			trigger_error("QRCode: Data do not exist.", E_USER_ERROR);
			exit;
		}

		if ((self::$_structureAppend_n > 1 && self::$_structureAppend_n <= 16) && (self::$_structureAppend_m > 0 && self::$_structureAppend_m <= 16)) {
			self::$_dataValue = array(
				3,
				(self::$_structureAppend_m - 1),
				(self::$_structureAppend_n - 1)
			);

			self::$_dataBits = array(
				3,
				4,
				4
			);

			$originalDataLength = strlen(self::$_structureAppend_originalData);
			if ($originalDataLength > 1) {
				self::$_structureAppend_parity = 0;
				for ($i = 0; $i < $originalDataLength; ++$i) {
					self::$_structureAppend_parity = (self::$_structureAppend_parity ^ ord(substr(self::$_structureAppend_originalData, $i, 1)));
				}
			}

			self::$_dataValue[] = self::$_structureAppend_parity;
			self::$_dataBits[]  = 8;

			self::$_dataCounter = 4;
		}

		self::$_dataBits[self::$_dataCounter] = 4;

		//Determine Encode Mode
		$mode = self::_identifyMode();

		switch ($mode) {
			case self::QR_MODE_8: // 8 byte mode 

				$codeword_num_plus                      = array(
					0,  // not used
					0, 0, 0, 0, 0, 0, 0, 0, 0,
					8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8,
					8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8
				);

				self::$_dataValue[self::$_dataCounter]  = 4;
				++self::$_dataCounter;

				self::$_dataValue[self::$_dataCounter]  = $dataLength;
				self::$_dataBits[self::$_dataCounter]   = 8;   /* #version 1-9 */

				$codeword_num_counter_value             = self::$_dataCounter;

				++self::$_dataCounter;

				for ($i = 0; $i < $dataLength; ++$i) {
					self::$_dataValue[self::$_dataCounter] = ord(substr(self::$_dataString, $i, 1));
					self::$_dataBits[self::$_dataCounter]  = 8;
					++$this_dataCounter;
				}

				break;

			case self::QR_MODE_AN: // aplhanumeric mode  
				$codeword_num_plus                      = array(
					0,  // not used
					0, 0, 0, 0, 0, 0, 0, 0, 0,
					2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
					4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4
				);

				self::$_dataValue[self::$_dataCounter]  = 2;
				++self::$_dataCounter;

				self::$_dataValue[self::$_dataCounter]  = $dataLength;
				self::$_dataBits[self::$_dataCounter]   = 9;  /* #version 1-9 */

				$codeword_num_counter_value             = self::$_dataCounter;

				/*
                Next: encode source data to binary representation.  
                In alphanumeric mode, each character is converted into a value according the hash matrix below.
                Next we consider delimited data by 2 characters. First value is multiplied by 45, and second value is added to it. 
                Result value is encoded in 11 bit long binary representation.
                When length of delimited data is 1, 6 bit long is used.
                */
				$alphanumeric_character_hash            = array(
					"0" => 0, "1" => 1, "2" => 2, "3" => 3, "4" => 4, "5" => 5, "6" => 6, "7" => 7, "8" => 8, "9" => 9,
					"A" => 10, "B" => 11, "C" => 12, "D" => 13, "E" => 14, "F" => 15, "G" => 16, "H" => 17, "I" => 18,
					"J" => 19, "K" => 20, "L" => 21, "M" => 22, "N" => 23, "O" => 24, "P" => 25, "Q" => 26, "R" => 27,
					"S" => 28, "T" => 29, "U" => 30, "V" => 31, "W" => 32, "X" => 33, "Y" => 34, "Z" => 35,
					" " => 36, "$" => 37, "%" => 38, "*" => 39, "+" => 40, "-" => 41, "." => 42, "/" => 43, ":" => 44
				);

				++self::$_dataCounter;
				for ($i = 0; $i < $dataLength; ++$i) {
					if (($i % 2) == 0) {
						self::$_dataValue[self::$_dataCounter]  = $alphanumeric_character_hash[substr(self::$_dataString, $i, 1)];
						self::$_dataBits[self::$_dataCounter]   = 6;
					} else {
						self::$_dataValue[self::$_dataCounter]  = (self::$_dataValue[self::$_dataCounter] * 45) + $alphanumeric_character_hash[substr(self::$_dataString, $i, 1)];
						self::$_dataBits[self::$_dataCounter]   = 11;
						++self::$_dataCounter;
					}
				}

				break;

			case self::QR_MODE_NUM: //numeric mode 

				$codeword_num_plus = array(
					0, // not used
					0, 0, 0, 0, 0, 0, 0, 0, 0,
					2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2,
					4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4
				);

				self::$_dataValue[self::$_dataCounter]  = 1;
				++self::$_dataCounter;

				self::$_dataValue[self::$_dataCounter]  = $dataLength;
				self::$_dataBits[self::$_dataCounter]   = 10;   /* #version 1-9 */

				$codeword_num_counter_value             = self::$_dataCounter;

				/*
                Next: encode source data to binary representation.
                In numeric mode, data is delimited by 3 digits.
                For example, "123456" is delimited "123" and "456", and first data is "123", second data is "456".
                Each data block is encoded in 10bit long binary representation.
                
                When length of delimited data is 1 or 2, 4bit long or 7bit long are used in each case.
                For example,"9876" is delimited "987" in 10 bit long and "6" in 4 bit long.
                Its result is "1111011011 0110"
                */
				for ($i = 0; $i < $dataLength; ++$i) {

					if (($i % 3) == 0) {
						self::$_dataValue[self::$_dataCounter]  = substr(self::$_dataString, $i, 1);
						self::$_dataBits[self::$_dataCounter]   = 4;
					} else {
						self::$_dataValue[self::$_dataCounter]  = (self::$_dataValue[self::$_dataCounter] * 10) + substr(self::$_dataString, $i, 1);

						if (($i % 3) == 1) {
							self::$_dataBits[self::$_dataCounter] = 7;
						} else {
							self::$_dataBits[self::$_dataCounter] = 10;
							++self::$_dataCounter;
						}
					}
				}

				break;

			default:
				break;
		}


		if (@self::$_dataBits[self::$_dataCounter] > 0) {
			++self::$_dataCounter;
		}

		// Total number of bits set so far
		$total_data_bits = array_sum(self::$_dataBits);

		@$ec = self::$eccLevels[self::$_errorCorrect];
		if (!$ec)
			$ec = 0;

		if (!is_numeric(self::$_version)) {
			self::$_version = 0;
		}


		if (!self::$_version) {
			self::$_version = 1;
			for ($i = 0; $i < 40; ++$i) {
				if (($this->maxDataBits[self::$_eccLevel][$i]) >= ($total_data_bits + $codeword_num_plus[self::$_version])) {
					break;
				}

				++self::$_version;
			}
		}

		$max_data_bits = self::$maxDataBits[self::$_eccLevel][(self::$_version - 1)];

		if (self::$_version > self::$_versionUR) {
			trigger_error("QRcode : too large version.", E_USER_ERROR);
			exit;
		}

		$total_data_bits                              += $codeword_num_plus[self::$_version];
		self::$_dataBits[$codeword_num_counter_value] += $codeword_num_plus[self::$_version];

		$max_codewords      = self::$max_codewords[self::$_version];
		$matrix_dimension   = self::_getMatrixDimension();
		$max_data_codewords = ($max_data_bits >> 3);

		/* Read QR version data file: Start */
		$byte_num   = self::getMatrixCapacityRemainder(self::$_version) + ($max_codewords << 3);
		$byte_num_or = $byte_num;
		$filename   = APPPATH . self::$_path . "/qrv" . self::$_version . "_" . $ec . ".dat";
		// echo $filename, '<br/>';
		if ($fp = fopen($filename, "rb")) {
			$matx               = fread($fp, $byte_num);
			$maty               = fread($fp, $byte_num);
			$masks              = fread($fp, $byte_num);
			$fi_x               = fread($fp, 15);
			$fi_y               = fread($fp, 15);
			$rs_ecc_codewords   = ord(fread($fp, 1));
			$rso                = fread($fp, 128);
			fclose($fp);
		} else {
			die("{$filename} could not be opened");
		}

		$matrix_x_array         = unpack("C*", $matx);
		$matrix_y_array         = unpack("C*", $maty);
		$mask_array             = unpack("C*", $masks);
		$rs_block_order         = unpack("C*", $rso);

		$format_information_x2  = unpack("C*", $fi_x);
		$format_information_y2  = unpack("C*", $fi_y);

		$format_information_x1  = array(0, 1, 2, 3, 4, 5, 7, 8, 8, 8, 8, 8, 8, 8, 8);
		$format_information_y1  = array(8, 8, 8, 8, 8, 8, 8, 8, 7, 5, 4, 3, 2, 1, 0);
		/* Read QR version data file: End */

		$filename           = APPPATH . self::$_path . "/rsc{$rs_ecc_codewords}.dat";
		// echo $filename;
		$fp0                = fopen($filename, "rb");

		for ($i = 0; $i < 256; ++$i)
			$rs_cal_table_array[$i] = fread($fp0, $rs_ecc_codewords);

		fclose($fp0);

		/* Set Terminator: Start */
		if ($total_data_bits <= $max_data_bits - 4) {
			self::$_dataValue[self::$_dataCounter]  = 0;
			self::$_dataBits[self::$_dataCounter]   = 4;
		} else {
			if ($total_data_bits < $max_data_bits) {
				self::$_dataValue[self::$_dataCounter]  = 0;
				self::$_dataBits[self::$_dataCounter]   = $max_data_bits - $total_data_bits;
			} else {
				if ($total_data_bits > $max_data_bits) {
					trigger_error("QRcode : Overflow error", E_USER_ERROR);
					exit;
				}
			}
		}
		/* Set Terminator: End */


		/* Divide data by 8bit: Start */
		$codewords_counter  = 0;
		$codewords[0]       = 0;
		$remaining_bits     = 8;
		for ($i = 0; $i <= self::$_dataCounter; ++$i) {
			$buffer      = @self::$_dataValue[$i];
			$buffer_bits = @self::$_dataBits[$i];

			$flag = 1;
			while ($flag) {
				if ($remaining_bits > $buffer_bits) {
					$codewords[$codewords_counter]  = ((@$codewords[$codewords_counter] << $buffer_bits) | $buffer);
					$remaining_bits                 -= $buffer_bits;
					$flag = 0;
				} else {
					$buffer_bits                    -= $remaining_bits;
					$codewords[$codewords_counter]  = (($codewords[$codewords_counter] << $remaining_bits) | ($buffer >> $buffer_bits));

					if ($buffer_bits == 0) {
						$flag = 0;
					} else {
						$buffer = ($buffer & ((1 << $buffer_bits) - 1));
						$flag = 1;
					}

					++$codewords_counter;
					if ($codewords_counter < $max_data_codewords - 1) {
						$codewords[$codewords_counter] = 0;
					}
					$remaining_bits = 8;
				}
			}
		}

		if ($remaining_bits <> 8) {
			$codewords[$codewords_counter] = $codewords[$codewords_counter] << $remaining_bits;
		} else {
			--$codewords_counter;
		}
		/* Divide data by 8bit: End */

		/* Set Padding Character:Start */
		if ($codewords_counter < ($max_data_codewords - 1)) {
			$flag = 1;

			while ($codewords_counter < ($max_data_codewords - 1)) {
				++$codewords_counter;
				$codewords[$codewords_counter] = ($flag == 1) ? 236 : 17;
				$flag = $flag * (-1);
			}
		}

		/* Set Padding Character:End */


		/* RS-ECC prepare */


		/* RS-ECC preparation: Start */
		$i = $j = 0;
		$rs_block_number = 0;
		$rs_temp[0]      = "";

		while ($i < $max_data_codewords) {

			@$rs_temp[$rs_block_number] .= chr($codewords[$i]);
			++$j;

			if ($j >= $rs_block_order[$rs_block_number + 1] - $rs_ecc_codewords) {
				$j = 0;
				++$rs_block_number;
				$rs_temp[$rs_block_number] = "";
			}
			++$i;
		}
		/* RS-ECC preparation: End */


		/* RS-ECC main: Start */
		$rs_block_number    = 0;
		$rs_block_order_num = count($rs_block_order);

		while ($rs_block_number < $rs_block_order_num) {

			$rs_codewords       = $rs_block_order[$rs_block_number + 1];
			$rs_data_codewords  = $rs_codewords - $rs_ecc_codewords;

			$rstemp             = $rs_temp[$rs_block_number] . str_repeat(chr(0), $rs_ecc_codewords);
			$padding_data       = str_repeat(chr(0), $rs_data_codewords);

			$j                  = $rs_data_codewords;

			while ($j > 0) {
				$first = ord(substr($rstemp, 0, 1));

				if ($first) {
					$left_chr   = substr($rstemp, 1);
					$cal        = $rs_cal_table_array[$first] . $padding_data;
					$rstemp     = $left_chr ^ $cal;
				} else {
					$rstemp     = substr($rstemp, 1);
				}

				--$j;
			}

			$codewords = array_merge($codewords, unpack("C*", $rstemp));
			++$rs_block_number;
		}
		/* RS-ECC main: End */


		/* Flash Matrix:Start */
		for ($i = 0; $i < $matrix_dimension; ++$i) {
			for ($j = 0; $j < $matrix_dimension; ++$j) {
				$matrix_content[$i][$j] = 0;
			}
		}
		/* Flash Matrix:End */


		/* Attach actual data: Start */
		// for($i = 0; $i < $max_codewords; ++$i) {
		//     $codeword_i = @$codewords[$i];
		//     for($j = 8; $j > 0; --$j) {
		//         $codeword_bits_number = ($i << 3) + $j;
		//         $codeword_i           = $codeword_i >> 1;
		//         $matrix_content[ $matrix_x_array[$codeword_bits_number] ][ $matrix_y_array[$codeword_bits_number] ] = ((255*($codeword_i & 1)) ^ $mask_array[$codeword_bits_number] ); 
		//     }
		// } 

		$i = 0;
		while ($i < $max_codewords) {
			$codeword_i = $codewords[$i];
			$j = 8;
			while ($j >= 1) {
				$codeword_bits_number = ($i << 3) +  $j;
				$matrix_content[$matrix_x_array[$codeword_bits_number]][$matrix_y_array[$codeword_bits_number]] = ((255 * ($codeword_i & 1)) ^ $mask_array[$codeword_bits_number]);
				$codeword_i = $codeword_i >> 1;
				$j--;
			}
			$i++;
		}

		$matrix_remain = $this->getMatrixCapacityRemainder(self::$_version);
		while ($matrix_remain) {
			$remain_bit_temp = $matrix_remain + ($max_codewords << 3);
			$matrix_content[$matrix_x_array[$remain_bit_temp]][$matrix_y_array[$remain_bit_temp]]  =  (0 ^ $mask_array[$remain_bit_temp]);
			--$matrix_remain;
		}
		/* Attach actual data: End */

		$this->_maskData = $this->_selectBestMaskPattern($matrix_content, $byte_num);


		/* Adding Information Data: Start */
		$format_information = self::$formatInformation;
		// pre($format_information)
		// pre($this->_maskData);
		$symbol_format_info = str_pad(decbin(hexdec($format_information[self::$_eccLevel][$this->_maskData['number']])), 15, '0', STR_PAD_LEFT);

		for ($i = 0; $i < 15; $i++) {
			$content = substr($symbol_format_info, $i, 1);
			$matrix_content[$format_information_x1[$i]][$format_information_y1[$i]] = $content * 255;
			$matrix_content[$format_information_x2[$i + 1]][$format_information_y2[$i + 1]] = $content * 255;
		}

		/* Adding Information Data: End */


		$mib                = $this->_getMatrixDimension() + 8;
		$qrcode_image_size  = $mib * self::$_moduleSize;

		if ($qrcode_image_size > 1480) {
			trigger_error("QRcode : Too large image size", E_USER_ERROR);
			exit;
		}

		$output_image       = imageCreate($qrcode_image_size, $qrcode_image_size);
		self::$_imagePath   = APPPATH . self::$_imagePath . "/qrv" . self::$_version . ".png";
		$base_image         = imageCreateFromPNG(self::$_imagePath);

		$col = array(
			ImageColorAllocate($base_image, 255, 255, 255),
			ImageColorAllocate($base_image, 0, 0, 0)
		);

		$i   = 4;
		$ii  = 0;
		$mxe = 4 + $this->_getMatrixDimension();
		while ($i < $mxe) {
			$j  = 4;
			$jj = 0;

			while ($j < $mxe) {
				if ($matrix_content[$ii][$jj] & $this->_maskData['pattern']) {
					imageSetPixel($base_image, $i, $j, $col[1]);
				}

				++$j;
				++$jj;
			}

			++$i;
			++$ii;
		}

		/* Output Image: Start */
		// var_dump(($base_image));
		// exit;
		Header("Content-type: image/" . self::$_imageType);
		// pre(array(self::$_dataString,
		// self::$_errorCorrect,
		// self::$_moduleSize,
		// self::$_version,
		// self::$_imageType,
		// self::$_structureAppend_n,
		// self::$_structureAppend_m));
		ImageCopyResized($output_image, $base_image, 0, 0, 0, 0, $qrcode_image_size, $qrcode_image_size, $mib, $mib);
		// var_dump($base_image);
		if (self::$_imageType == "jpeg") {
			imagejpeg($output_image);
		} else {
			imagepng($output_image);
		}
		/* Output Image: End */
	}
}
