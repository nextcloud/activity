<?php

// Cron job for sending emails and pruning the activity list
\OC::$server->getJobList()->add('OCA\Activity\BackgroundJob\EmailNotification');
\OC::$server->getJobList()->add('OCA\Activity\BackgroundJob\ExpireActivities');
