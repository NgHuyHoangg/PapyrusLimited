<?php
include_once "./db.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./output.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Search</title>
</head>

<body class="m-0 p-0">
    <?php
    include_once "./header.php";
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['product_id'])) {
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            $product_id = $_POST['product_id'];
            $sql_query_find_product = "SELECT * from carts where product_id = $product_id and user_id = $user_id";
            $result = mysqli_query($conn, $sql_query_find_product);
            if ($result) {
                if (!mysqli_fetch_assoc($result)['product_id']) {
                    $sql_query_price = "SELECT price from products where product_id = $product_id";
                    $result = mysqli_query($conn, $sql_query_price);
                    $price = mysqli_fetch_assoc($result)['price'];
                    $sql_query = "INSERT into carts value ($user_id, $product_id, 1, $price)";
                    $result = mysqli_query($conn, $sql_query);
                    if ($result) {
                        echo "<div class='z-50 fixed top-1/2 left-1/2 -translate-x-[50%] -translate-y-[50%] grid grid-cols-1 text-center bg-white w-96 h-auto p-3 shadow-2xl'>
                                <h1 class='text-2xl my-5'>Product added to cart successfully</h1>
                                <div class='mb-0 flex items-center justify-evenly'>
                                    <a href='home.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Continue shopping</a>
                                    <a href='cart.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Go to cart</a>
                                </div>
                            </div>";
                    }
                } else {
                    $sql_query_price = "SELECT price from products where product_id = $product_id";
                    $result = mysqli_query($conn, $sql_query_price);
                    $price = mysqli_fetch_assoc($result)['price'];
                    $sql_query = "UPDATE carts set quantity = quantity + 1, price = price + $price where product_id = $product_id";
                    $result = mysqli_query($conn, $sql_query);
                    if ($result) {
                        echo "<div class='z-50 fixed top-1/2 left-1/2 -translate-x-[50%] -translate-y-[50%] grid grid-cols-1 text-center bg-white w-96 h-auto p-3 shadow-2xl'>
                                <h1 class='text-2xl my-5'>Product added to cart successfully</h1>
                                <div class='mb-0 flex items-center justify-evenly'>
                                    <a href='home.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Continue shopping</a>
                                    <a href='cart.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Go to cart</a>
                                </div>
                            </div>";
                    }
                }
            }
        } else {
            echo "<div class='z-50 fixed top-1/2 left-1/2 -translate-x-[50%] -translate-y-[50%] grid grid-cols-1 text-center bg-white w-96 h-auto p-3 shadow-2xl'>
                                <h1 class='text-2xl my-5'>Login to buy</h1>
                                <div class='mb-0 flex items-center justify-evenly'>
                                    <a href='login.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Login</a>
                                    <a href='home.php' class='bg-red-500 text-white p-3 hover:bg-red-600 w-[45%]'>Continue shopping</a>
                                </div>
                            </div>";
        }
    }
    ?>

    <div class="mt-28 px-48 flex flex-col w-full min-h-screen">
        <?php
            if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['search'])) {
                $search = $_GET['search'];
                $sql_query = "SELECT p.product_id as id, pi.image_url as img, p.name as name, p.price as price from product_images pi
                            join products p on pi.product_id = p.product_id
                            join categories c on p.catagory_id = c.category_id
                            where stock > 0 and p.name like '%$search%' group by p.product_id";
                $result = mysqli_query($conn, $sql_query);
                if ($result) {
                    $count = mysqli_num_rows($result);
                }
        ?>
        <h1 class="text-3xl font-semibold mb-5">There are <?= $count ?> matching search results</h1>
        <div class=" grid grid-cols-4 gap-5 text-center">
            <?php
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class=" border-2 flex justify-center p-3 relative h-80 w-60 group">
                                         <a href="product_detail.php?product_id=' . $row["id"] . '">
                                             <img src="' . $row['img'] . '" alt="" class="w-40 h-40 object-center object-cover inline-block mb-3">
                                             <p>' . $row['name'] . '</p>
                                             <p class="absolute bottom-2 left-1/2 -translate-x-[50%] font-medium text-xl text-red-500">' . $row['price'] . '<u class="text-base">đ</u></p>
                                         </a>
                                         <div class="absolute shadow-lg flex bg-white top-1/2 left-1/2 -translate-x-[50%] -translate-y-[20%]  opacity-0 duration-500 ease-in-out group-hover:opacity-100 group-hover:-translate-y-[50%]">
                                             <a href="product_detail.php?product_id=' . $row["id"] . '" class="h-10 w-10 flex items-center justify-center border-r hover:text-red-500"><i class="fa-solid fa-eye"></i></a>
                                             <form method="POST" action="">
                                                 <input type="hidden" name="product_id" value="' . $row["id"] . '">
                                                 <button class="add_to_cart h-10 w-10  hover:text-red-500"><i class="fa-solid fa-bag-shopping"></i></button>
                                             </form>
                                         </div>
                                    </div>';
                    }
                }
            }
            ?>

        </div>
    </div>

    <?php
    include_once "./footer.php";
    ?>
</body>

</html>