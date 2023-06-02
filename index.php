<!DOCTYPE HTML>

<html>
    <body>
        <form id="filter-form" method="POST">
            <label>Prioritze by text:</label>
            <select id="text-filter" name="prioritizeByText">
                <option>
                    Yes
                </option>
                <option>
                    No
                </option>
            </select>
            <br>
            <label>Prioritze by text:</label>
            <select id="rating-filter" name="orderByRating">
                <option>
                    Highest first
                </option>
                <option>
                    Lowest first
                </option>
            </select>
            <br>
            <label>Order by date:</label>
            <select id="date-filter" name="orderByDate">
                <option>
                    Newest first
                </option>
                <option>
                    Oldest first
                </option>
            </select>
            <br>
            <label>Minimum rating:</label>
            <select id="min-rating" name="minimumRating">
                <option>
                    1
                </option>
                <option>
                    2
                </option>
                <option>
                    3
                </option>
                <option>
                    4
                </option>
                <option>
                    5
                </option>
            </select>
            <br>
            <input type="submit" name="filter" value="filter">
        </form>
        <?php
            include 'SortingController.php';
        ?>
    </body>
</html>
<style>
body {
    font-family: sans-serif;
}

table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}

select {
    display: block;
    width: 100%;
    max-width: 250px;
    padding: 10px;
    font-weight: 400;
    color: #212529;
    background-color: #fff;
    background-size: 16px 12px;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    appearance: none;
}

input {
    display: inline-block;
    font-weight: 400;
    line-height: 1.5;
    color: #fff;
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    cursor: pointer;
    user-select: none;
    background-color: #3a77e0;
    border: 1px solid transparent;
    padding: .375rem .75rem;
    margin-bottom: 10px;
    font-size: 1rem;
    border-radius: .25rem;
}
</style>