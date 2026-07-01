<?php

$response = [];
foreach ($files as $file) {
    $response[] = [
        'name'      => $file->name(),
        'size'      => $file->humanReadableSize(), // formats ITS OWN bytes
        'extension' => $file->extension(),         // reads ITS OWN name
        'image'     => $file->isImage(),           // looks at ITS OWN mime (to show a thumbnail)
    ];
}

return new JsonResponse($response, 200);
