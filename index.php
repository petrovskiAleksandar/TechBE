<!DOCTYPE HTML>

<html>
    <body>
        <?php
            include 'RandomController.php';
        ?>
        <form action="" method="POST">
            <select name="prioritizeByText">
                <option>
                    Yes
                </option>
                <option>
                    No
                </option>
            </select>
            <br>
            <select name="orderByRating">
                <option>
                    Highest first
                </option>
                <option>
                    Lowest first
                </option>
            </select>
            <br>
            <select name="orderByDate">
                <option>
                    Newest first
                </option>
                <option>
                    Oldest first
                </option>
            </select>
            <br>
            <select name="minimumRating">
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

            <input type="submit" name="filter" value="Filter">
        </form>
    </body>
</html>
