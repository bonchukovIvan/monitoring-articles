<?php

setlocale(LC_ALL, 'uk_UA.utf8');

$html = file_get_contents('https://sumdu.edu.ua');

$dom = new DOMDocument();
$dom->loadHTML(mb_encode_numericentity(
    htmlspecialchars_decode(
        htmlentities($html, ENT_NOQUOTES, 'UTF-8', false)
        ,ENT_NOQUOTES
    ), [0x80, 0x10FFFF, 0, ~0],
    'UTF-8'
));

$xpath = new DOMXPath($dom);
$newsNodes = $xpath->query("//div[@class='api-news__date']");
$eventsNodes = $xpath->query("//div[@class='api-events__date api-events__date--simple api-events__date--inline']");
// print_r($newsNodes);
// print_r($eventsNodes);
?>

    <?php
    // foreach ($newsNodes as $dateNode) {
    //     $dateText = trim($dateNode->textContent);

    //     $dateTimestamp = strtotime($dateText);
    //     $date_f = strtotime(cyrillic_to_english_date($dateText));

    //     echo cyrillic_to_english_date($dateText)."\n";

    //     echo "News Date: $dateText\n";
    //     if ($date_f >= strtotime('-10 days')) {
    //         echo  "Новина молодша за 10 днів".  "\n\n";
    //     } else {
    //         echo  "Новина старша старша 10 днів".  "\n\n";
    //     }

    // }
    ?> 
    <div class="nodes">

        <div class="node-list__title">
            <h2>NEWS</h2>
        </div>
        <div class="nodes-list node-events">
            <?php foreach ($eventsNodes as $dateNode) :?>
                <div class="node-list__item">
                    <div class="node-list__date">
                        <?php echo $dateNode->nodeValue; ?>
                    </div>
                </div>   
            <?php endforeach; ?>
        </div>

        <div class="node-list__title">
            <h2>NEWS</h2>
        </div>
        <div class="nodes-list node-news">
            <?php foreach ($newsNodes as $dateNode) :?>
                <div class="node-list__item">
                    <div class="node-list__date">
                        <?php echo $dateNode->nodeValue; ?>
                    </div>
                </div>    
            <?php endforeach; ?>
        </div>

    </div>

<?php
// print_r($dateNode);
// $dateText = trim($dateNode->textContent);

// $dateTimestamp = strtotime($dateText);
// $date_f = strtotime(cyrillic_to_english_date($dateText));

// echo cyrillic_to_english_date($dateText)."\n";

// echo "Event Date: $dateText\n";
// if ($date_f >= strtotime('-10 days')) {
//     echo  "Новина молодша за 10 днів".  "\n\n";
// } else {
//     echo  "Новина старша старша 10 днів".  "\n\n";
// }
//   echo date('d.m.Y H:i:s', strtotime('-10 days'))  .  "\n\n";
// echo  "\n";

// echo date('d.m.Y H:i:s', strtotime("10 September 2000"))  .  "\n\n";
function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
 
    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}
function cyrillic_to_english_date($dateString) {
    // Check if the string contains Cyrillic characters
    if (preg_match('/\p{Cyrillic}/u', $dateString)) {
        // Map Cyrillic month names to English month names
        $cyrillicMonths = array(
            'січня' => 'January',
            'лютого' => 'February',
            'березня' => 'March',
            'квітня' => 'April',
            'травня' => 'May',
            'червня' => 'June',
            'липня' => 'July',
            'серпня' => 'August',
            'вересня' => 'September',
            'жовтня' => 'October',
            'листопада' => 'November',
            'грудня' => 'December'
        );

        // Replace Cyrillic month names with English month names
        $englishDate = str_replace(array_keys($cyrillicMonths), array_values($cyrillicMonths), $dateString);

        return $englishDate;
    }

    // If the string doesn't contain Cyrillic characters, return it as is
    return $dateString;
}