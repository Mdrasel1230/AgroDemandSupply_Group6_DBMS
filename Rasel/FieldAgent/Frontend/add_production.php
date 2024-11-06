<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Production Data</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<style>
/* Inline CSS to apply the background image to the entire body */
body {
    background-image: url('css/bg_picture.jpg');  /* Adjust the path if needed */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    color: #333;
    min-height: 100vh;
    margin: 0;
    font-family: Arial, sans-serif;
}



nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    background: rgba(0, 0, 0, 0.6); /* Dark background for the nav */
    border-radius: 8px;
}

nav ul li {
    margin: 0 10px;
}

nav ul li a {
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    display: block;
    font-weight: bold;
}

nav ul li a:hover {
    background-color: rgba(255, 255, 255, 0.3);
}
</style>
    <header>
        <h1>Add Production Data</h1>
    </header>
    
<?php include 'navbar.html'; ?>
    <section>
        <form>
            <label for="cropType">Crop Type:</label>
            <input type="text" id="cropType" name="cropType">
            <label for="yield">Yield:</label>
            <input type="number" id="yield" name="yield">
            <button type="submit">Add Data</button>
        </form>
    </section>
</body>
</html>
