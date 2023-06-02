<?php
    $reviewsJson = file_get_contents('reviews.json');
    $reviews = json_decode($reviewsJson, true);

    $prioritizeByText = '';
    $orderByRating = '';
    $orderByDate = '';
    $minimumRating = 1;

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



    $reviews = array_filter($reviews, function ($review) use ($minimumRating){
        return (int)$review['rating'] >= $minimumRating;
    }, ARRAY_FILTER_USE_BOTH);

    $reviews = array_values($reviews);

    function orderByRating ($first, $second, $orderByRating) {
        if ($orderByRating === 'Highest first') {
            return $first['rating'] < $second['rating'];
        }

        return $first['rating'] > $second['rating'];
    }

    function prioritizeByText ($first, $second, $prioritizeByText) {
        if ($prioritizeByText === 'Yes') {
            return strlen($second['reviewText']) > 0 && strlen($first['reviewText']) === 0;
        }
    }

    function orderByDate ($first, $second, $orderByDate) {
        if ($orderByDate === 'Newest first') {
            return $second['reviewCreatedOnDate'] > $first['reviewCreatedOnDate'];
        }

        return $second['reviewCreatedOnDate'] < $first['reviewCreatedOnDate'];
    }

    function sortArray ($reviews, $condition, $conditionValue) {
        $localReviews = $reviews;

        for ($i = 0; $i < count($localReviews); $i++)
        {
            $swapped = false;

            for ($j = 0; $j < count($localReviews) - $i - 1; $j++) {
                if ($condition($localReviews[$j], $localReviews[$j + 1], $conditionValue)) {
                    $temp = $localReviews[$j];
                    $localReviews[$j] = $localReviews[$j + 1];
                    $localReviews[$j + 1] = $temp;

                    $swapped = true;
                }
            }

            if ($swapped === false) {
                break;
            }
        }

        return $localReviews;
    }

    $reviews = sortArray($reviews, 'orderByDate', $orderByDate);
    $reviews = sortArray($reviews, 'orderByRating', $orderByRating);
    $reviews = sortArray($reviews, 'prioritizeByText', $prioritizeByText);

    foreach ($reviews as $review) {
        echo $review['rating'] . ' - ' .$review['reviewText'] .$review['reviewCreatedOnTime'] .'<br>';
    }
?>