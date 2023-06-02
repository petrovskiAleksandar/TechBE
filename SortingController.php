<?php
    $reviewsJson = file_get_contents('reviews.json');
    $reviews = json_decode($reviewsJson, true);

    $prioritizeByText = '';
    $orderByRating = '';
    $orderByDate = '';
    $minimumRating = 1;

    if (isset($_POST['filter']))
    {
        echo $_POST['prioritizeByText'] . '<br/>';
        $prioritizeByText = $_POST['prioritizeByText'];
        echo $_POST['orderByRating'] . '<br/>';
        $orderByRating = $_POST['orderByRating'];
        echo $_POST['orderByDate'] . '<br/>';
        $orderByDate = $_POST['orderByDate'];
        echo $_POST['minimumRating'] . '<br/>';
        $minimumRating = (int) $_POST['minimumRating'];
    }

    $reviews = array_filter($reviews, function ($review) use ($minimumRating) {
        return (int)$review['rating'] >= $minimumRating;
    });
    $reviews = array_values($reviews);

    $sortByRating = function ($first, $second) use ($orderByRating)
    {
        if ($orderByRating === 'Highest first') {
            return $first['rating'] < $second['rating'];
        }

        return $first['rating'] > $second['rating'];
    };

    $sortByText = function ($first, $second) use ($prioritizeByText)
    {
        if ($prioritizeByText === 'Yes') {
            return strlen($second['reviewText']) > 0 && strlen($first['reviewText']) === 0;
        }
    };

    $sortByDate = function ($first, $second) use ($orderByDate)
    {
        if ($orderByDate === 'Newest first') {
            return $first['reviewCreatedOnTime'] < $second['reviewCreatedOnTime'];
        }

        return $first['reviewCreatedOnTime'] > $second['reviewCreatedOnTime'];
    };

    $sortIfRatingIsEqual = function ($first, $second) use ($sortByDate) {
        $first['rating'] === $second['rating'] && $sortByDate($first, $second);
    };
    
    $sortFunction = function ($first, $second) use ($prioritizeByText, $sortByRating, $sortIfRatingIsEqual)
    {
        if ($prioritizeByText === 'Yes') {
            return (
                (
                    strlen($second['reviewText']) > 0 &&
                    strlen($first['reviewText']) > 0 &&
                    (
                        $sortIfRatingIsEqual($first, $second) ||
                        $sortByRating($first, $second)
                    )
                ) ||
                (
                    strlen($second['reviewText']) === 0 &&
                    strlen($first['reviewText']) === 0 &&
                    (
                        $sortIfRatingIsEqual($first, $second) ||
                        $sortByRating($first, $second)
                    )
                ) ||
                (
                    strlen($second['reviewText']) > 0 &&
                    strlen($first['reviewText']) === 0
                )
            );
        }

        return (
                $sortIfRatingIsEqual($first, $second) ||
                $sortByRating($first, $second)
            ) ||
            $sortByRating($first, $second);
    };

    for ($i = 0; $i < count($reviews); $i++)
    {
        $swapped = false;

        for ($j = 0; $j < count($reviews) - $i - 1; $j++)
        {
            if ($sortFunction($reviews[$j], $reviews[$j + 1]))
            {
                $temp = $reviews[$j];
                $reviews[$j] = $reviews[$j + 1];
                $reviews[$j + 1] = $temp;

                $swapped = true;
            }
        }

        if ($swapped === false)
        {
            break;
        }
    }


    echo <<<TEXT
    <table style="padding: 100px">
        <tr>
            <th>Rating</th>
            <th>Review Text</th>
            <th>Created on</th>
        </tr>
    TEXT;

    foreach ($reviews as $review)
    {
        $reviewRating = $review['rating'];
        $reviewText = $review['reviewText'];
        $reviewDate = $review['reviewCreatedOnTime'];

        echo <<<TEXT
                <tr style="text-align: center">
                    <td style="width: 100px;">$reviewRating</td>
                    <td style="width: 100px;">$reviewText</td>
                    <td style="width: 200px;">$reviewDate</td>
                </tr>
        TEXT;

    }

    echo '</table>';
?>