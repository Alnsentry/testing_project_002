<?php

namespace App\Enum;

enum TipeTeks:string
{
case FormFilling = 'formfilling';
case RedirectWA = 'redirectwa';
case Option = 'option';
case Text = 'text';

public function label() {
	return match($this) {
		self::FormFilling => 'formfilling',
		self::RedirectWA => 'redirectwa',
		self::Option => 'option',
		self::Text => 'text'
	};
}

}