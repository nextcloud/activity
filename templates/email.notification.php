<?php
/** @var OC_L10N $l */
/** @var array $_ */
$l = $_['overwriteL10N'];

print_unescaped($l->t('Hello %s,', array($_['username'])));
p("\n");
p("\n");

if ((int) $_['timeframe'] === \OCA\Activity\UserSettings::EMAIL_SEND_HOURLY) {
	print_unescaped($l->t('You are receiving this email because in the last hour the following things happened at %s', array($_['owncloud_installation'])));
} else if ((int) $_['timeframe'] === \OCA\Activity\UserSettings::EMAIL_SEND_DAILY) {
	print_unescaped($l->t('You are receiving this email because in the last day the following things happened at %s', array($_['owncloud_installation'])));
} else {
	print_unescaped($l->t('You are receiving this email because in the last week the following things happened at %s', array($_['owncloud_installation'])));
}
p("\n");
p("\n");

foreach ($_['activities'] as $activityData) {
	print_unescaped($l->t('* %1$s - %2$s', $activityData));
	p("\n");
}
if ($_['skippedCount']) {
	print_unescaped($l->n('* and %n more ', '* and %n more ', $_['skippedCount']));
	p("\n");
}
p("\n");
