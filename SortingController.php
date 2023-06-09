<?php
class SortFunctionality {
    private $reviews;
    private $prioritizeByTextValue;
    private $orderByRatingValue;
    private $orderByDateValue;
    private $minimumRatingValue;


    public function __construct($reviews)
    {
        $this->reviews = $reviews;
        $this->prioritizeByTextValue = '';
        $this->orderByRatingValue = '';
        $this->orderByDateValue = '';
        $this->minimumRatingValue = 1;
    }

    private function setFilterData ()
    {
        if (isset($_POST['filter']))
        {  
            $this->prioritizeByTextValue = $_POST['prioritizeByText'];
            $this->orderByRatingValue = $_POST['orderByRating'];
            $this->orderByDateValue = $_POST['orderByDate'];
            $this->minimumRatingValue = (int) $_POST['minimumRating'];

            echo '<b>Active Filters:</b> <br>';
            echo ' prioritizeByText: ' . $_POST['prioritizeByText'] . '<br>';
            echo ' orderByRating: ' . $_POST['orderByRating'] . '<br>';
            echo 'orderByDate: ' . $_POST['orderByDate'] . '<br>';
            echo 'Minimum rating: ' . $_POST['minimumRating'] . '<br>';
        }

        $this->reviews = array_values(array_filter($this->reviews, function ($review) {
            return (int)$review['rating'] >= $this->minimumRatingValue;
        }));
    }

    private function sortByRating ($firstElement, $secondElement)
    {
        if ($this->orderByRatingValue === 'None') {
            return;
        }

        if ($this->orderByRatingValue === 'Highest first')
        {
            return $firstElement['rating'] < $secondElement['rating'];
        }

        return $firstElement['rating'] > $secondElement['rating'];
    }

    private function sortByDate ($firstElement, $secondElement)
    {
        if ($this->orderByDateValue === 'Newest first')
        {
            return $firstElement['reviewCreatedOnTime'] < $secondElement['reviewCreatedOnTime'];
        }

        return $firstElement['reviewCreatedOnTime'] > $secondElement['reviewCreatedOnTime'];
    }

    private function sortByText ($firstElement, $secondElement)
    {
        return (
            (
                strlen($secondElement['reviewText']) === 0 &&
                strlen($firstElement['reviewText']) === 0
            ) ||
            (
                strlen($secondElement['reviewText']) > 0 &&
                strlen($firstElement['reviewText']) > 0
            )
        );
    }

    private function sortEquallyRated ($firstElement, $secondElement)
    {
        if ($this->orderByDateValue === 'None') {
            return;
        }

        return (
            $firstElement['rating'] === $secondElement['rating'] &&
            $this->sortByDate($firstElement, $secondElement)
        );
    }

    private function priorityFunction ($firstElement, $secondElement)
    {
        if ($this->prioritizeByTextValue === 'Yes')
        {
            return (
                (
                    $this->sortByText($firstElement, $secondElement) &&
                    (
                        $this->sortEquallyRated($firstElement, $secondElement) ||
                        $this->sortByRating($firstElement, $secondElement)
                    )
                ) ||
                (
                    strlen($secondElement['reviewText']) > 0 &&
                    strlen($firstElement['reviewText']) === 0
                )
            );
        }

        return $this->sortEquallyRated($firstElement, $secondElement) || $this->sortByRating($firstElement, $secondElement);
    }

    public function sortArray ()
    {
        $this->setFilterData();

        for ($i = 0; $i < count($this->reviews); $i++)
        {
            $swapped = false;
    
            for ($j = 0; $j < count($this->reviews) - $i - 1; $j++)
            {
                if ($this->priorityFunction($this->reviews[$j], $this->reviews[$j + 1]))
                {
                    $temp = $this->reviews[$j];
                    $this->reviews[$j] = $this->reviews[$j + 1];
                    $this->reviews[$j + 1] = $temp;
    
                    $swapped = true;
                }
            }
    
            if ($swapped === false)
            {
                break;
            }
        }
    }

    public function listReviews ()
    {
        echo '
        <table>
            <tr>
                <th>Rating</th>
                <th>Review Text</th>
                <th>Created on</th>
            </tr>
        ';

        foreach ($this->reviews as $review)
        {
            $reviewRating = $review['rating'];
            $reviewText = $review['reviewText'];
            $reviewDate = $review['reviewCreatedOnTime'];

            echo "
                <tr>
                    <td>$reviewRating</td>
                    <td>$reviewText</td>
                    <td>$reviewDate</td>
                </tr>
            ";
        }

        echo '</table>';
    }
}

$reviewsJson = file_get_contents('reviews.json');
$reviews = json_decode($reviewsJson, true);

$sortFunctionality = new SortFunctionality($reviews);

$sortFunctionality->sortArray();
$sortFunctionality->listReviews();
?>