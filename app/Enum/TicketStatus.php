<?php

namespace App\Enum;

enum TicketStatus:string
{
case Pending = 'Pending';
case Approve = 'Approve';
case Reject = 'Reject';
case OnProcess = 'On Process';
case Complete = 'Complete';
case Reschedule = 'Reschedule';

public function label() {
	return match($this) {
		self::Pending => 'Pending',
		self::Approve => 'Approve',
		self::Reject => 'Reject',
		self::OnProcess => 'On Process',
		self::Complete => 'Complete',
		self::Reschedule => 'Reschedule'
	};
}

}