<header class="w-full h-[70px] flex items-center justify-center shadow-md fixed top-0 bg-white z-50 box-border">
        <div class="flex items-center justify-center">
            <div>
                <a href="home.php"><img src="./img/logo.png" alt="" class="max-h-[70px] w-auto border-r"></a>
            </div>
            <nav>
                <a href="products.php" class="px-5 py-6 duration-200 hover:bg-[#ef4444] hover:text-white">Products</a>
                <div class="inline-block">
                    <a href="products.php" class="px-5 py-6 duration-200 hover:bg-[#ef4444] hover:text-white peer">Categories</a>
                    <nav class="hidden duration-200 shadow-lg peer-hover:flex flex-col absolute top-[70px] w-40 hover:flex z-50">
                        <?php
                        if(isset($_SESSION['username'])){
                            $username = $_SESSION['username'];
                        }
                        $sql_query = "SELECT * FROM categories";
                        $result = mysqli_query($conn, $sql_query);
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<a href='products.php?category=".$row['name']."' class='bg-gray-50 p-2 max-w-40 text-wrap duration-200 hover:bg-[#ef4444] hover:text-white'>" . ucfirst($row['name']) . "</a>";
                            }
                        }
                        ?>
                    </nav>
                </div>
                <div class="inline-block">
                    <a href="products.php" class="px-5 py-6 duration-200 hover:bg-[#ef4444] hover:text-white peer">Occasions</a>
                    <nav class="hidden duration-200 shadow-lg peer-hover:flex flex-col absolute top-[70px] w-40 hover:flex z-50">
                        <?php
                        $sql_query = "SELECT * FROM occasions";
                        $result = mysqli_query($conn, $sql_query);
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<a href='products.php?occasion=".$row['name']."' class='bg-gray-50 p-2 max-w-40 text-wrap duration-200 hover:bg-[#ef4444] hover:text-white'>" . ucfirst($row['name']) . "</a>";
                            }
                        }
                        ?>
                    </nav>
                </div>
                <a href="products.php?blog" class="px-5 py-6 duration-200 hover:bg-[#ef4444] hover:text-white">Blogs</a>
            </nav>
            <form action="search.php" method="GET" class="search_form z-50 group">
                <button class="border-x"><i
                        class="fa-solid fa-magnifying-glass text-xl p-5  duration-200 hover:bg-[#ef4444] hover:text-white"></i></button>
                <input type="text" name="search" id="search" placeholder="Search" required
                    class="focus:outline-none focus:block hidden border border-black p-3 absolute -bottom-12 bg-white z-50 group-hover:block">
            </form>
            <a href="<?= isset($_SESSION['username']) ? 'cart.php' : 'login.php' ?>" class="px-5 py-5 duration-200 flex items-center justify-center hover:bg-[#ef4444] hover:text-white relative group">
                <i class="fa-solid fa-bag-shopping text-2xl mr-3"></i>
                Cart
                <?php
                if(isset($_SESSION['username'])){
                    $sql_query_user_id = "SELECT user_id from accounts where username = '$username'";
                    $result = mysqli_query($conn, $sql_query_user_id);
                    $user_id = mysqli_fetch_assoc($result)['user_id'];
    
                    $sql_query_count = "SELECT COUNT(*) as count FROM carts where user_id = $user_id";
                    $result = mysqli_query($conn, $sql_query_count);
                    if ($result) {
                        $items = mysqli_fetch_assoc($result);
                        if ($items['count'] > 0) {
                            echo "<div class='bg-red-500 w-fit text-white rounded-full px-2 absolute top-3 left-7 group-hover:bg-red-600'>" . $items['count'] . "</div>";
                        } else {
                            echo "<div class='bg-red-500 w-fit text-white rounded-full px-2 absolute top-3 left-7 group-hover:bg-red-600'>0</div>";
                        }
                    }
                }else{
                    echo "<div class='bg-red-500 w-fit text-white rounded-full px-2 absolute top-3 left-7 group-hover:bg-red-600'>0</div>";
                }
                ?>
            </a>
            <?php
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                echo '<div calss="inline-block"><div class="px-5 py-5 duration-200 hover:bg-[#ef4444] hover:text-white peer"><i class="fa-solid fa-user text-2xl mr-2"></i>' . $username . '</div>
                            <nav class="hidden duration-200 shadow-lg peer-hover:flex flex-col absolute top-[70px] w-40 hover:flex z-50">
                                <a href="change_pass.php" class="bg-gray-50 p-2 max-w-40 text-wrap duration-200 hover:bg-[#ef4444] hover:text-white">Change Password</a>
                                <a href="login.php" class="bg-gray-50 p-2 max-w-40 text-wrap duration-200 hover:bg-[#ef4444] hover:text-white">Logout</a>
                            </nav>
                        </div>';
            } else {
                echo '<a href="login.php" class="px-5 py-6 duration-200 hover:bg-[#ef4444] hover:text-white">Login</a>';
            }
            ?>
        </div>
    </header>