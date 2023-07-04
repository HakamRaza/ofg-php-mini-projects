<!DOCTYPE HTML>
<html>

<head>
    <title>Question One</title>
</head>

<body>

    <?php
    // define variables and set to empty values
    $userInput = "";
    $inputArray = $outputArr = [];

    // import class
    require './CharMath.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($_POST["userInput"])) {
            $userInput = "";
        } else {
            $userInput = test_input($_POST["userInput"]);
            $inputArray = explode(',', $userInput);
            $charMath = new CharMath();
            $charMath->setInputs($inputArray);
            $outputArr = $charMath->get();
        }
    }

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    ?>

    <h2>Minimum Number of Deletions on Repeating Characters Form.</h2>
    <h3>Example</h3>
    <p>Input of : `AAAA,BBBBB,ABABABAB,AAABBB` will result in `3,4,0,4`</p>
    <br>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <h3>Fill in the box below with comma seperate values.</h3>
        <textarea name="userInput" rows="5" cols="40"><?php echo $userInput; ?></textarea>
        <br><br>

        <input type="submit" name="submit" value="Submit">
    </form>

    <?php
    if (count($inputArray) > 0) {
        echo "<br>";
        echo "<h2>Your Input:</h2>";
        echo "<br>";
        echo "<p>" . implode('<br>', $inputArray) . "</p>";
        echo "<h2>Output</h2>";
        echo "<p>" . implode('<br>', $outputArr) . "</p>";
    } else {
        echo "<br>";
        echo "No Input";
    }
    ?>

</body>

</html>