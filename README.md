# PHP-Verify-Code-Identification-Class
A simple php class for verification code identification.

**This class is too slow to use in a prod env, please use other libraries such as [tesseract](https://github.com/tesseract-ocr/tesseract) instead.**

## Usage
* Import the class file and create the instance of the class

```
include "VC/VC.php";
$object = new VerifyCode();
```

* Teach the program with some image sample

```
$object->Init_Image("VC/1.jpg", array('g', '4', 'J', 'p'));
$object->Init_Image("VC/2.jpg", array('B', 'H', 'd', '7'));
$object->Init_Image("VC/3.jpg", array('T', 'F', '5', 'H'));
...
```

* Identify the unknown verify code

```
echo $object->Recognize_Image("verifyCode.jpg");
```

## Postscript
* It's just a identification program for very simple verify code.
* If want to speed it up, you can write a cache file instead of teaching the program everytime.
