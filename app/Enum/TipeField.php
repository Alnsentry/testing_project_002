<?php

namespace App\Enum;

enum TipeField:string
{
case Teks = 'teks';
case UploadFile = 'uploadfile';
case Number = 'number';
case Email = 'email';
case URL = 'url';

public function label() {
	return match($this) {
		self::Teks => 'teks',
		self::UploadFile => 'uploadfile',
		self::Number => 'number',
		self::Email => 'email',
		self::URL => 'url'
	};
}

}