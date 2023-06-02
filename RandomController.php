<?php
    $reviewsJson = file_get_contents('reviews.json');
    $reviews = json_decode($reviewsJson, true);

    $prioritizeByText = '';
    $orderByRating = '';
    $orderByDate = '';
    $minimumRating = 1;

    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';

    if (isset($_POST['filter'])) {
        echo $_POST['prioritizeByText'] . '<br/>';
        $prioritizeByText = $_POST['prioritizeByText'];
        echo $_POST['orderByRating'] . '<br/>';
        $orderByRating = $_POST['orderByRating'];
        echo $_POST['orderByDate'] . '<br/>';
        $orderByDate = $_POST['orderByDate'];
        echo $_POST['minimumRating'] . '<br/>';
        $minimumRating = (int) $_POST['minimumRating'];
    }



    $reviews = array_filter($reviews, function ($review) {
        return (int)$review['rating'] >= 3;
    }, ARRAY_FILTER_USE_BOTH);

    $reviews = array_values($reviews);
    $reviewsLength = count($reviews);

    function orderByRating ($first, $second, $orderByRating) {
        if ($orderByRating === 'Highest first') {
            return $first['rating'] < $second['rating'];
        }

        return $first['rating'] > $second['rating'];
    }

    for ($i = 0; $i < $reviewsLength; $i++)
    {
        $swapped = false;

        for ($j = 0; $j < $reviewsLength - $i - 1; $j++) {
            if (orderByRating($reviews[$j], $reviews[$j + 1], $orderByRating)) {
                $temp = $reviews[$j];
                $reviews[$j] = $reviews[$j + 1];
                $reviews[$j + 1] = $temp;

                $swapped = true;
            }
        }

        if ($swapped === false) {
            break;
        }
    }

    foreach ($reviews as $review) {
        echo $review['rating'] . ' - ' .$review['reviewText'] .'<br>';
    }
?>