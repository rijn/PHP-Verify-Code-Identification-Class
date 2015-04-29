<?php

/**
 * Verify Code class
 *
 * @author Rijn
 * @link [https://github.com/rijn/PHP-Verify-Code-Identification-Class]
 */

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
		for ($i = 0; $i < $width; $i++)
		{
			for ($j = 0; $j < $height; $j++)
			{
				$rgb = imagecolorat($image, $i, $j);
				$r   = ($rgb >> 16) & 0xFF;
				$g   = ($rgb >> 8) & 0xFF;
				$b   = $rgb & 0xFF;
				//echo("$i,$j=>$r,$g,$b<br/>");
				if ($r < 170 || $g < 170 || $b < 170)
				{
					$color = imagecolorallocate($image, 0, 0, 0);
				}
				else
				{
					$color = imagecolorallocate($image, 255, 255, 255);
				}

				imagesetpixel($image, $i, $j, $color);
			}
		}
		return $image;
	}

	/**
	 * This was area detecting function
	 *
	 * @author Rijn
	 * @param image verify code
	 * @param parameters
	 * @return array
	 */
	public function Detect_Area($image, $top, $left, $bottom, $right, $i, $j)
	{
		$rgb = imagecolorat($image, $i, $j);
		$r   = ($rgb >> 16) & 0xFF;
		$g   = ($rgb >> 8) & 0xFF;
		$b   = $rgb & 0xFF;
		if ($r + $g + $b < 10)
		{
			if ($top > $i)
			{
				$top = $i;
			}

			if ($bottom < $i)
			{
				$bottom = $i;
			}

			if ($left > $j)
			{
				$left = $j;
			}

			if ($right < $j)
			{
				$right = $j;
			}

			$color = imagecolorallocate($image, 255, 255, 255);
			imagesetpixel($image, $i, $j, $color);
			if ($i < 104)
			{
				$array = $this->Detect_Area($image, $top, $left, $bottom, $right, $i + 1, $j);
				if ($top > $array['top'])
				{
					$top = $array['top'];
				}

				if ($bottom < $array['bottom'])
				{
					$bottom = $array['bottom'];
				}

				if ($left > $array['left'])
				{
					$left = $array['left'];
				}

				if ($right < $array['right'])
				{
					$right = $array['right'];
				}

			}
			if ($i > 0)
			{
				$array = $this->Detect_Area($image, $top, $left, $bottom, $right, $i - 1, $j);
				if ($top > $array['top'])
				{
					$top = $array['top'];
				}

				if ($bottom < $array['bottom'])
				{
					$bottom = $array['bottom'];
				}

				if ($left > $array['left'])
				{
					$left = $array['left'];
				}

				if ($right < $array['right'])
				{
					$right = $array['right'];
				}

			}
			if ($j < 29)
			{
				$array = $this->Detect_Area($image, $top, $left, $bottom, $right, $i, $j + 1);
				if ($top > $array['top'])
				{
					$top = $array['top'];
				}

				if ($bottom < $array['bottom'])
				{
					$bottom = $array['bottom'];
				}

				if ($left > $array['left'])
				{
					$left = $array['left'];
				}

				if ($right < $array['right'])
				{
					$right = $array['right'];
				}

			}
			if ($j > 0)
			{
				$array = $this->Detect_Area($image, $top, $left, $bottom, $right, $i, $j - 1);
				if ($top > $array['top'])
				{
					$top = $array['top'];
				}

				if ($bottom < $array['bottom'])
				{
					$bottom = $array['bottom'];
				}

				if ($left > $array['left'])
				{
					$left = $array['left'];
				}

				if ($right < $array['right'])
				{
					$right = $array['right'];
				}

			}
		}
		return array(
			'image'  => $image,
			'top'    => $top,
			'bottom' => $bottom,
			'left'   => $left,
			'right'  => $right,
		);
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
		$img = imagecreatefromjpeg($image);
		$img = $this->Binaryzation($img, $width, $height);
		$backup_img = imagecreate($width, $height);
		imagecopy($backup_img, $img, 0, 0, 0, 0, $width, $height);
		$s = 0;
		for ($i = 0; $i < $width; $i++)
		{
			$j   = $height / 2;
			$rgb = imagecolorat($img, $i, $j);
			$r   = ($rgb >> 16) & 0xFF;
			$g   = ($rgb >> 8) & 0xFF;
			$b   = $rgb & 0xFF;
			if ($r + $g + $b < 10)
			{
				$array = $this->Detect_Area($img, $i, $j, $i, $j, $i, $j);
				$img   = $array['image'];
				$this->Resource[$this->k] = array(
					'image' => null,
					'x0'    => $array['left'],
					'y0'    => $array['top'],
					'x1'    => $array['right'],
					'y1'    => $array['bottom'],
					'key'   => $string[$s],
				);
				$this->Resource[$this->k]['image'] = imagecreate($array['bottom'] - $array['top'] + 1,
					$array['right'] - $array['left'] + 1);
				imagecopy($this->Resource[$this->k]['image'], $backup_img, 0, 0, $array['top'], $array['left'],
					$array['bottom'] - $array['top'] + 1, $array['right'] - $array['left'] + 1);

				$this->k++;
				$s++;
			}

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
		$img = imagecreatefromjpeg($image);

		$img = $this->Binaryzation($img, $width, $height);
		$backup_img = imagecreate($width, $height);
		imagecopy($backup_img, $img, 0, 0, 0, 0, $width, $height);
		$k = 0;
		for ($i = 0; $i < $width; $i++)
		{
			$j   = $height / 2;
			$rgb = imagecolorat($img, $i, $j);
			$r   = ($rgb >> 16) & 0xFF;
			$g   = ($rgb >> 8) & 0xFF;
			$b   = $rgb & 0xFF;
			if ($r + $g + $b < 10)
			{
				$array = $this->Detect_Area($img, $i, $j, $i, $j, $i, $j);
				$img   = $array['image'];
				$result[$k] = array(
					'image'  => null,
					'x0'     => $array['left'],
					'y0'     => $array['top'],
					'x1'     => $array['right'],
					'y1'     => $array['bottom'],
					'result' => null,
				);
				$result[$k]['image'] = imagecreate($array['bottom'] - $array['top'] + 1,
					$array['right'] - $array['left'] + 1);
				imagecopy($result[$k]['image'], $backup_img, 0, 0, $array['top'], $array['left'],
					$array['bottom'] - $array['top'] + 1, $array['right'] - $array['left'] + 1);

				$temp = array();
				$temp['percentage'] = 0;
				foreach ($this->Resource as $key => $value)
				{

					$temp_per = $this->Calc_Percentage($value['image'], $result[$k]['image']);

					//echo("$k * $key => $temp_per <br/>");
					if ($temp_per > $temp['percentage'])
					{
						$temp['percentage'] = $temp_per;
						$temp['result'] = $value['key'];
					}
					//echo("per:$temp_per<br/>");
				}

				$result[$k]['result'] = $temp['result'];
				$text .= $temp['result'];

				$k++;
			}

		}

		return $text;
	}

}
