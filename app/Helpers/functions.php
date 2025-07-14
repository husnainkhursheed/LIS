<?php

function getAllProfileIds($profiles)
{
    $ids = [];
    foreach ($profiles as $profile) {
        $ids[] = $profile->id;
        if ($profile->subProfiles && $profile->subProfiles->isNotEmpty()) {
            $ids = array_merge($ids, getAllProfileIds($profile->subProfiles));
        }
    }
    return $ids;
}

function getSubProfilesRecursive($profile)
{
    $subProfiles = [];
    foreach ($profile->subProfiles as $sub) {
        $subProfiles[] = $sub;
        $subProfiles = array_merge($subProfiles, getSubProfilesRecursive($sub));
    }
    return $subProfiles;
}
