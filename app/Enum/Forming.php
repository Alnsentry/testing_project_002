<?php

namespace App\Enum;

enum Forming:string
{
case Feedback = 'feedback';
case Feedback_WA = 'feedback_wa';

public function label() {
	return match($this) {
		self::Feedback => 'feedback',
		self::Feedback_WA => 'feedback_wa'
	};
}

}