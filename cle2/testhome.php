
//html
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>CLE 2</title>
</head>

<header>

    <nav>
        <div class="logo"><img src="images/logo_144.png" alt=""></div>

        <div class="home"><a href="">Home</a></div>
        <div class="dag"><a href="">Hoe ziet een dag eruit </a></div>
        <div class="fotos"><a href="">Foto's</a></div>
        <div class="afspraak"><a href="">Afspraak maken</a> </div>
        <div class="contact"><a href="">Contact en locatie</a></div>
        <div class="inlog"><a href="">Inlog</a></div>



    </nav>
</header>

<body>



</body>

</html>

//CSS

*{
border: #7c0909 solid 1px;
box-sizing: border-box;
}

/*desktop*/
@media only screen and (min-width: 769px) {

body{

background-color: rgb(196, 199, 196);

}



nav{
display: flex;
flex-direction: row;
justify-content: space-between;
align-items: center;

}

nav a{
display: flex;
text-decoration: none;

}

.logo img{
height: 60px;
width: 100%;
}

}