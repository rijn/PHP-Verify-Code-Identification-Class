<?php

/**
 * Verify Code class
 *
 * @author Rijn
 * @link [https://github.com/rijn/PHP-Verify-Code-Identification-Class]
 */

ob_implicit_flush();
if (ob_get_level() == 0)
{
	ob_start();
}

//error_reporting(0);
set_time_limit(0);

class VerifyCode {

	public $Resource = array();
	public $k = 0;

	/**
	 * This was image binaryzation function
	 *
	 * @author Rijn
	 * @param image verify code
	 * @param width height
	 * @return image
	 */
	public function Binaryzation($image, $width, $height)
	{
		$im = imagecreate($width, $height);
		$black = imagecolorallocate($im, 0, 0, 0);
		$white = imagecolorallocate($im, 255, 255, 255);
		for ($i = 0; $i < $width; $i++)
		{
			for ($j = 0; $j < $height; $j++)
			{
				$pixelrgb = imagecolorat($image, $i, $j);
				$cols     = imagecolorsforindex($image, $pixelrgb);
				$r        = $cols['red'];
				$g        = $cols['green'];
				$b        = $cols['blue'];
				//echo ("$i,$j=>$r,$g,$b<br/>");
				if ($b - $r > 90 && $b - $g > 90)
				{
					imagesetpixel($im, $i, $j, $black);
				}
				else
				{
					imagesetpixel($im, $i, $j, $white);
				}
			}
		}

		return $im;
	}

	/**
	 * This was image binaryzation function
	 *
	 * @author Rijn
	 * @param image verify code
	 * @param width height
	 * @return image
	 */
	public function Binaryzation2($image, $width, $height)
	{
		$im = imagecreate($width, $height);
		$black = imagecolorallocate($im, 0, 0, 0);
		$white = imagecolorallocate($im, 255, 255, 255);
		for ($i = 0; $i < $width; $i++)
		{
			for ($j = 0; $j < $height; $j++)
			{
				$pixelrgb = imagecolorat($image, $i, $j);
				$cols     = imagecolorsforindex($image, $pixelrgb);
				$r        = $cols['red'];
				$g        = $cols['green'];
				$b        = $cols['blue'];
				//echo ("$i,$j=>$r,$g,$b<br/>");
				if ($r < 170)
				{
					imagesetpixel($im, $i, $j, $black);
				}
				else
				{
					imagesetpixel($im, $i, $j, $white);
				}
			}
		}

		return $im;
	}

	/**
	 * Erosion
	 *
	 * @author Rijn
	 * @param image
	 * @return image
	 */
	public function Erosion($image, $width, $height)
	{
		$im = imagecreate($width, $height);
		$black = imagecolorallocate($im, 0, 0, 0);
		$white = imagecolorallocate($im, 255, 255, 255);
		for ($i = 0; $i < $width; $i++)
		{
			for ($j = 0; $j < $height; $j++)
			{
				$count = 0;
				for ($di = -1; $di <= 1; $di++)
				{
					for ($dj = -1; $dj <= 1; $dj++)
					{
						$pixelrgb = imagecolorat($image, $i + $di, $j + $dj);
						$cols     = imagecolorsforindex($image, $pixelrgb);
						$r        = $cols['red'];
						if ($r < 127)
						{
							$count++;
						}
					}
				}
				if ($count > 5 && $i > 0 && $i < $width && $j > 0 && $j < $height)
				{
					imagesetpixel($im, $i, $j, $black);
				}
				else
				{
					imagesetpixel($im, $i, $j, $white);
				}
			}
		}

		return $im;
	}

	/**
	 * Integrate image through x
	 *
	 * @author Rijn
	 * @param image
	 * @return array
	 */
	public function x_cWave($image, $width, $height)
	{
		$wave = array();
		$peak = array();
		for ($i = 0; $i < $width; $i++)
		{
			$count = 0;
			for ($j = 0; $j < $height; $j++)
			{
				$pixelrgb = imagecolorat($image, $i, $j);
				$cols     = imagecolorsforindex($image, $pixelrgb);
				$r        = $cols['red'];
				if ($r < 127)
				{
					$count++;
				}
			}
			array_push($wave, $count);
		}

		for ($i = 0; $i < $width + 1; $i++)
		{
			if (($wave[$i - 1] > $wave[$i] && $wave[$i] < $wave[$i + 1]) || ($wave[$i - 1] >= $wave[$i] && $wave[$i] < $wave[$i + 1]) || ($wave[$i - 1] > $wave[$i] && $wave[$i] <= $wave[$i + 1]))
			{
				array_push($peak, $wave[$i]);
			}
			else
			{
				array_push($peak, $height - 1);
			}
		}

		$split = array();
		for ($i = 0, $count = 0; $i < $height && $count < 5; $i++)
		{
			for ($j = 0; $j < count($peak) && $count < 5; $j++)
			{
				if ($peak[$j] == $i)
				{
					$flag = true;
					for ($k = 0; $k < count($split); $k++)
					{
						if (abs($split[$k] - $j) < 7)
						{
							$flag = false;
						}
					}
					if ($flag)
					{
						array_push($split, $j);
						$count++;
					}
				}
			}
		}

		//print_r($split);

		return $split;
	}

	/**
	 * Rotate image to find thinest position
	 *
	 * @author Rijn
	 * @param image
	 * @return image
	 */
	public function Thinest($image, $x0, $x1, $height)
	{
		$im = imagecreate($x1 - $x0, $height);
		$black = imagecolorallocate($im, 0, 0, 0);
		$white = imagecolorallocate($im, 255, 255, 255);
		for ($i = $x0; $i < $x1; $i++)
		{
			for ($j = 0; $j < $height; $j++)
			{
				$pixelrgb = imagecolorat($image, $i, $j);
				$cols     = imagecolorsforindex($image, $pixelrgb);
				$r        = $cols['red'];
				if ($r < 127)
				{
					imagesetpixel($im, $i - $x0, $j, $black);
				}
				else
				{
					imagesetpixel($im, $i - $x0, $j, $white);
				}
			}
		}

		$rem = null;
		$d   = $x1 - $x0 + 100;
		for ($angle = -45; $angle <= 45; $angle++)
		{
			$newwidth  = ($x1 - $x0) / cos(deg2rad($angle));
			$newheight = $height / cos(deg2rad($angle));
			$resize    = imagecreatetruecolor($newwidth, $newheight);
			$white     = imagecolorallocate($resize, 255, 255, 255);
			imagefill($resize, 0, 0, $white);

			imagecopyresampled($resize, $im, 0, 0, 0, 0, $newwidth, $newheight, $x1 - $x0, $height);

			$temp = imagerotate($resize, $angle, $white);

			$flag = false;
			$min  = 0;
			while ( ! $flag && $min < imagesx($temp))
			{
				$flag = false;
				for ($j = 0; $j < imagesy($temp); $j++)
				{
					$pixelrgb = imagecolorat($temp, $min, $j);
					$cols     = imagecolorsforindex($temp, $pixelrgb);
					$r        = $cols['red'];
					if ($r < 230)
					{
						$flag = true;
					}
				}
				$min++;
			}
			$flag = false;
			$max  = imagesx($temp);
			while ( ! $flag && $max > 0)
			{
				$flag = false;
				for ($j = 0; $j < imagesy($temp); $j++)
				{
					$pixelrgb = imagecolorat($temp, $max, $j);
					$cols     = imagecolorsforindex($temp, $pixelrgb);
					$r        = $cols['red'];
					if ($r < 230)
					{
						$flag = true;
					}
				}
				$max--;
			}
			if ($max - $min + 1 < $d)
			{
				$d = $max - $min + 1;
				//echo ($d);
				$rem = imagecreatetruecolor($max - $min + 1, imagesy($temp));
				$white = imagecolorallocate($rem, 255, 255, 255);
				imagefill($rem, 0, 0, $white);
				imagecopyresized($rem, $temp, 0, 0, $min - 1, 0, $max - $min + 1, imagesy($temp), $max - $min + 1, imagesy($temp));

			}
			//echo ($min.",$max");
			//return $temp;
		}
		//echo ($rem);
		return $rem;
	}

	/**
	 * Trim image
	 *
	 * @author Rijn
	 * @param image
	 * @return image
	 */
	public function Trim($image)
	{
		$th = 230;

		$flag = false;
		$x0   = 0;
		while ( ! $flag && $x0 < imagesx($image))
		{
			$flag = false;
			for ($j = 0; $j < imagesy($image); $j++)
			{
				$pixelrgb = imagecolorat($image, $x0, $j);
				$cols     = imagecolorsforindex($image, $pixelrgb);
				$r        = $cols['red'];
				if ($r < $th)
				{
					$flag = true;
				}
			}
			$x0++;
		}
		$x0--;

		$flag = false;
		$x1   = imagesx($image);
		while ( ! $flag && $x1 > 0)
		{
			$flag = false;
			for ($j = 0; $j < imagesy($image); $j++)
			{
				$pixelrgb = imagecolorat($image, $x1, $j);
				$cols     = imagecolorsforindex($image, $pixelrgb);
				$r        = $cols['red'];
				if ($r < $th)
				{
					$flag = true;
				}
			}
			$x1--;
		}
		$x1++;

		$flag = false;
		$y0   = 0;
		while ( ! $flag && $y0 < imagesy($image))
		{
			$flag = false;
			for ($j = 0; $j < imagesx($image); $j++)
			{
				$pixelrgb = imagecolorat($image, $j, $y0);
				$cols     = imagecolorsforindex($image, $pixelrgb);
				$r        = $cols['red'];
				if ($r < $th)
				{
					$flag = true;
				}
			}
			$y0++;
		}
		$y0--;

		$flag = false;
		$y1   = imagesy($image);
		while ( ! $flag && $y1 > 0)
		{
			$flag = false;
			for ($j = 0; $j < imagesx($image); $j++)
			{
				$pixelrgb = imagecolorat($image, $j, $y1);
				$cols     = imagecolorsforindex($image, $pixelrgb);
				$r        = $cols['red'];
				if ($r < $th)
				{
					$flag = true;
				}
			}
			$y1--;
		}
		$y1++;

		$result = imagecreatetruecolor($x1 - $x0, $y1 - $y0);
		$white  = imagecolorallocate($result, 255, 255, 255);
		imagefill($result, 0, 0, $white);
		imagecopyresized($result, $image, 0, 0, $x0, $y0, $x1 - $x0, $y1 - $y0, $x1 - $x0, $y1 - $y0);

		return $result;
	}

	/**
	 * This was image learning function
	 *
	 * @author Rijn
	 * @param image verify code
	 * @param string code
	 * @return true
	 */
	public function Init_Image($image, $string)
	{
		list($width, $height, $type, $attr) = getimagesize($image);
		$img = imagecreatefromgif($image);

		if (false)
		{
			$result = imagecreatetruecolor($width, $height * 4);
			$white  = imagecolorallocate($result, 255, 255, 255);
			imagefill($result, 0, 0, $white);

			imagecopy($result, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
		}

		$img = $this->Binaryzation($img, $width, $height);

		if (false)
		{
			imagecopy($result, $img, 0, $height, 0, 0, imagesx($img), imagesy($img));
		}

		$img = $this->Erosion($img, $width, $height);

		if (false)
		{
			imagecopy($result, $img, 0, $height * 2, 0, 0, imagesx($img), imagesy($img));
		}

		$split = $this->x_cWave($img, $width, $height);
		sort($split);
		$chars = array();
		for ($i = 0; $i < 4; $i++)
		{
			//echo ($split[$i]."-".$split[$i + 1]."<br/>");
			$chars[$i] = $this->Thinest($img, $split[$i], $split[$i + 1], $height);
			$chars[$i] = $this->Binaryzation2($chars[$i], imagesx($chars[$i]), imagesy($chars[$i]));
			$chars[$i] = $this->Trim($chars[$i]);
		}

		for ($i = 0; $i < 4; $i++)
		{
			if (false)
			{
				imagecopy($result, $chars[$i], $split[$i], $height * 3, 0, 0, imagesx($chars[$i]), imagesy($chars[$i]));
			}

			if ('*' != $string[$i] && imagesx($chars[$i]) > 4 && imagesy($chars[$i]) > 4)
			{

				$this->Resource[$this->k] = array(
					'image' => $chars[$i],
					'key'   => $string[$i],
				);
				$this->k++;
			}
		}

		//print_r($this->Resource);

		if (false)
		{
			$this->view($result);
		}

		return true;
	}

	/**
	 * This was image compare function
	 *
	 * @author Rijn
	 * @param image a
	 * @param image b
	 * @return float
	 */
	public function Calc_Percentage($imageA, $imageB)
	{
		$widthA  = imagesx($imageA);
		$widthB  = imagesx($imageB);
		$heightA = imagesy($imageA);
		$heightB = imagesy($imageB);
		$countA  = 0;
		$countB  = 0;
		for ($x = 0; $x < $widthA && $x < $widthB; $x++)
		{
			for ($y = 0; $y < $heightA && $y < $heightB; $y++)
			{
				if (imagecolorat($imageA, $x, $y) == imagecolorat($imageB, $x, $y))
				{
					$countA++;
				}
				$countB++;
			}
		}

		return $countA / $countB;
	}

	/**
	 * This was identify verify code function.
	 *
	 * @author Rijn
	 * @param image verify code
	 * @return string
	 */
	public function Recognize_Image($image)
	{
		$result = array();
		$text   = "";

		list($width, $height, $type, $attr) = getimagesize($image);
		$img = imagecreatefromgif($image);

		$img   = $this->Binaryzation($img, $width, $height);
		$img   = $this->Erosion($img, $width, $height);
		$split = $this->x_cWave($img, $width, $height);
		sort($split);

		$chars = array();
		for ($i = 0; $i < 4; $i++)
		{
			$chars[$i] = $this->Thinest($img, $split[$i], $split[$i + 1], $height);
			$chars[$i] = $this->Binaryzation2($chars[$i], imagesx($chars[$i]), imagesy($chars[$i]));
			$chars[$i] = $this->Trim($chars[$i]);
		}
		//$this->view($img);
		//$this->view($chars[0]);

		for ($k = 0; $k < 4; $k++)
		{
			$result = array();

			for ($i = 0; $i < count($this->Resource); $i++)
			{
				$key = $this->Resource[$i]['key'];
				$p   = $this->Calc_Percentage($chars[$k], $this->Resource[$i]['image']);
				array_push($result,
					(object) array(
						'key' => $key,
						'p'   => $p,
					)
				);
			}

			for ($i = 0; $i < count($result) - 1; $i++)
			{
				for ($j = $i + 1; $j < count($result); $j++)
				{
					if ($result[$i]->p < $result[$j]->p)
					{
						$temp = $result[$i];
						$result[$i] = $result[$j];
						$result[$j] = $temp;
					}
				}
			}

			$text .= $result[0]->key;
			//echo ($result[0]->key."->".$result[0]->p."<br/><br/>");

		}
		return $text;
	}

	public function Output_Database()
	{
		$result = imagecreatetruecolor(600, 620);
		$white  = imagecolorallocate($result, 255, 255, 255);
		$black  = imagecolorallocate($result, 0, 0, 0);
		imagefill($result, 0, 0, $white);
		imagestring($result, 2, 5, 600, "Database count = ".count($this->Resource), $black);

		if (false)
		{
			for ($i = 0; $i < count($this->Resource) - 1; $i++)
			{
				for ($j = $i + 1; $j < count($this->Resource); $j++)
				{
					if ($this->Resource[$i]['key'] > $this->Resource[$j]['key'])
					{
						$temp = $this->Resource[$i];
						$this->Resource[$i] = $this->Resource[$j];
						$this->Resource[$j] = $temp;

					}
				}
			}
		}

		for ($i = 0; $i < count($this->Resource); $i++)
		{
			$image = $this->Resource[$i]['image'];
			$key   = $this->Resource[$i]['key'];
			if ('*' != $key)
			{
				imagecopyresized($result, $image, (int) ($i / 20) * 30 + 5, (int) ($i % 20) * 30 + 5, 0, 0, imagesx($image), imagesy($image), imagesx($image), imagesy($image));
			}
			imagechar($result, 2, (int) ($i / 20) * 30 + 20, (int) ($i % 20) * 30 + 5, $key, $black);
		}
		$this->view($result);
	}

	public function view($image)
	{
		header('Content-Type: image/jpeg');
		imagejpeg($image, NULL, 100);
	}

}
if (true)
{
	echo ("importing...<br/>");
	ob_flush();
	flush();
}
/* Initialize object */
$object = new VerifyCode();

/* import data */
$dir = "./data/";
$fileArray[] = NULL;
if (false != ($handle = opendir($dir)))
{
	$i = 0;
	while (false !== ($file = readdir($handle)))
	{
		if ("." != $file && ".." != $file && strpos($file, "."))
		{
			$fileArray[$i] = basename($data.$file, ".gif");
			if (100 == $i)
			{
				break;
			}
			if (true)
			{
				echo ("importing $i => ".$fileArray[$i]."...<br/>");
				ob_flush();
				flush();
			}
			$i++;
		}
	}
	closedir($handle);
}
if (true)
{
	echo ("<br/><br/>processing...<br/>");
	ob_flush();
	flush();
}
$k = 0;
foreach ($fileArray as $key => $value)
{
	//echo ("$value<br/>");
	$object->Init_Image($dir.$value.".gif", $value);
	$k++;
	if (true)
	{
		echo ("processing $value $k / $i...<br/>");
		ob_flush();
		flush();
	}
}

/* check database */
if (false)
{
	$object->Output_Database();
}

echo ("import success<br/>database count = ".count($object->Resource)."<br/><br/>");
ob_flush();
flush();

/* request resource */
function curl_request($url, $post = '', $cookie_file = '', $fetch_cookie = 0, $referer = '', $timeout = 10)
{

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("Expect:"));
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
	curl_setopt($curl, CURLOPT_REFERER, $referer);
	if ($post)
	{
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
	}
	if ($fetch_cookie)
	{
		curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);
	}
	else
	{
		curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file);
	}
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($curl);
	if (curl_errno($curl))
	{
		return false;
	}

	return $data;
}

$cookie_file = tempnam('./temp', 'cookie');
$s = 0;
for ($k = 1; $k <= 100; $k++)
{

	echo ("request code $k &nbsp;&nbsp;&nbsp;");
	ob_flush();
	flush();

	$url = "http://202.119.225.34/default2.aspx";
	$code_url = "http://202.119.225.34/CheckCode.aspx";
	$refreer = "http://202.119.225.34/default2.aspx";

	$viewresult = curl_request($url, '', $cookie_file, true, $referer, 5);
	$image = curl_request($code_url, '', $cookie_file, false, $referer, 5);
	//echo($image);

	$fp = fopen("./temp/$k.gif", "w");
	fwrite($fp, $image);
	fclose($fp);

	$result = $object->Recognize_Image("./temp/$k.gif");

	echo ("<img src=\"./temp/$k.gif\">&nbsp;&nbsp;&nbsp;$result&nbsp;&nbsp;&nbsp;");
	ob_flush();
	flush();

	$viewresult = iconv('gb2312', 'utf-8', $viewresult);
	$pattern    = '/<input type="hidden" name="__VIEWSTATE" value="(.*?)" \/>/is';
	preg_match_all($pattern, $viewresult, $matches);

	$post = array(
		__VIEWSTATE => @$matches[1][0],
		txtUserName => "B1401",
		TextBox2 => "1234",
		txtSecretCode => $result,
		RadioButtonList1 => "",
		Button1 => "",
		lbLanguage => "",
		hidPdrs => "",
		hidsc => "",
	);

	$checkresult = curl_request($url, $post, $cookie_file, false, $referer, 5);
	$checkresult = iconv('gb2312', 'utf-8', $checkresult);

	if (strstr($checkresult, "验证码不正确"))
	{
		echo ("false");
	}
	else
	{
		echo ("true");
		/*$fp = fopen("./learn/$result.gif", "w");
		fwrite($fp, $image);
		fclose($fp);*/
		$s++;
	}
	echo ("&nbsp;&nbsp;&nbsp;$s / $k => ".($s / $k * 100)."%<br/>");
	ob_flush();
	flush();
}

unlink($cookie_file);
ob_end_flush();
