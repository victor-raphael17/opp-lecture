<?php

$rows = $db->query("SELECT * FROM files WHERE folder_id = 42");

$files = [];
foreach ($rows as $row) {
    $files[] = new File($row['name'], $row['size_bytes'], $row['mime_type']);
}

# Now there are 3 File objects alive at the same time, each with different data:
#
# $files[0] → report.pdf,   1,536,000 bytes, application/pdf
# $files[1] → profile.png,    845,000 bytes, image/png
# $files[2] → notes.txt,        1,200 bytes, text/plain
