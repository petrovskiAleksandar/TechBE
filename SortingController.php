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
    });
    $reviews = array_values($reviews);

    $sortByRating = function ($first, $second) use ($orderByRating){
        if ($orderByRating === 'Highest first') {
            return $first['rating'] < $second['rating'];
        }

        return $first['rating'] > $second['rating'];
    };

    $sortByText = function ($first, $second) use ($prioritizeByText) {
        if ($prioritizeByText === 'Yes') {
            return strlen($second['reviewText']) > 0 && strlen($first['reviewText']) === 0;
        }
    };

    $sortByDate = function ($first, $second) use ($orderByDate) {
        if ($orderByDate === 'Newest first') {
            return $second['reviewCreatedOnDate'] > $first['reviewCreatedOnDate'];
        }

        return $second['reviewCreatedOnDate'] < $first['reviewCreatedOnDate'];
    };

    function sortArray ($reviews, $condition) {
        $localReviews = $reviews;

        for ($i = 0; $i < count($localReviews); $i++)
        {
            $swapped = false;

            for ($j = 0; $j < count($localReviews) - $i - 1; $j++) {
                if ($condition($localReviews[$j], $localReviews[$j + 1])) {
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

    $reviews = sortArray($reviews, $sortByDate);
    $reviews = sortArray($reviews, $sortByRating);
    $reviews = sortArray($reviews, $sortByText);

    // echo <<<TEXT
    // <table style="padding: 100px">
    //     <tr>
    //         <th>Rating</th>
    //         <th>Review Text</th>
    //         <th>Created on</th>
    //     </tr>
    // TEXT;

    foreach ($reviews as $review) {
        $reviewRating = $review['rating'];
        $reviewText = $review['reviewText'];
        $reviewDate = $review['reviewCreatedOnTime'];

        // echo <<<TEXT
        //         <tr style="text-align: center">
        //             <td style="width: 100px;">$reviewRating</td>
        //             <td style="width: 100px;">$reviewText</td>
        //             <td style="width: 200px;">$reviewDate</td>
        //         </tr>
        // TEXT;
        echo $review['rating'] . ' - ' .$review['reviewText'] .$review['reviewCreatedOnTime'] .'<br>';
    }

    // echo '</table>';
?>