<?php
// Booking capacity per time slot
define('CAPACITY_PER_SLOT', 10);

// Working hours for slots (inclusive start, exclusive end)
define('OPEN_HOUR', 10); // 10:00
define('CLOSE_HOUR', 22); // 22:00

function generate_time_slots(): array {
	$slots = [];
	for ($h = OPEN_HOUR; $h < CLOSE_HOUR; $h++) {
		$slots[] = sprintf('%02d:00', $h);
	}
	return $slots;
}










