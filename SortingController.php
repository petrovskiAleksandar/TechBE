<?php
    $reviewsJson = file_get_contents('reviews.json');
    $reviews = json_decode($reviewsJson, true);

    $prioritizeByTextValue = '';
    $orderByRatingValue = '';
    $orderByDateValue = '';
    $minimumRatingValue = 1;

    if (isset($_POST['filter']))
    {
        echo $_POST['prioritizeByText'] . '<br/>';
        $prioritizeByTextValue = $_POST['prioritizeByText'];
        echo $_POST['orderByRating'] . '<br/>';
        $orderByRatingValue = $_POST['orderByRating'];
        echo $_POST['orderByDate'] . '<br/>';
        $orderByDateValue = $_POST['orderByDate'];
        $minimumRatingValue = (int) $_POST['minimumRating'];
    }

    $reviews = array_filter($reviews, function ($review) use ($minimumRatingValue) {
        return (int)$review['rating'] >= $minimumRatingValue;
    });
    $reviews = array_values($reviews);

    $sortByRating = function ($first, $second) use ($orderByRatingValue)
    {
        if ($orderByRatingValue === 'Highest first') {
            return $first['rating'] < $second['rating'];
        }

        return $first['rating'] > $second['rating'];
    };

    $sortByDate = function ($first, $second) use ($orderByDateValue)
    {
        if ($orderByDateValue === 'Newest first') {
            return $first['reviewCreatedOnTime'] < $second['reviewCreatedOnTime'];
        }

        return $first['reviewCreatedOnTime'] > $second['reviewCreatedOnTime'];
    };

    $sortByText = function ($first, $second)
    {
        return (
            (
                strlen($second['reviewText']) === 0 &&
                strlen($first['reviewText']) === 0
            ) ||
            (
                strlen($second['reviewText']) > 0 &&
                strlen($first['reviewText']) > 0
            )
        );
    };

    $sortRatingAndDate = function ($first, $second) use ($sortByDate, $sortByRating)
    {
        return (
            (
                $first['rating'] === $second['rating'] &&
                $sortByDate($first, $second)
            ) || 
            $sortByRating($first, $second)
        );
    };

    $priorityFunction = function ($first, $second) use ($prioritizeByTextValue, $sortByRating, $sortByText, $sortRatingAndDate)
    {
        if ($prioritizeByTextValue === 'Yes')
        {
            return (
                (
                    $sortByText($first, $second) &&
                    $sortRatingAndDate($first, $second)
                ) ||
                (
                    strlen($second['reviewText']) > 0 &&
                    strlen($first['reviewText']) === 0
                )
            );
        }

        return $sortRatingAndDate($first, $second) || $sortByRating($first, $second);
    };

    for ($i = 0; $i < count($reviews); $i++)
    {
        $swapped = false;

        for ($j = 0; $j < count($reviews) - $i - 1; $j++)
        {
            if ($priorityFunction($reviews[$j], $reviews[$j + 1]))
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
                <tr>
                    <td>$reviewRating</td>
                    <td>$reviewText</td>
                    <td>$reviewDate</td>
                </tr>
        TEXT;

    }
?>