<?php
 session_start();
?>
<?php
class logging{

    private $db; 
    private $host; 
    private $user; 
    private $pass; 
    
    public function __construct() {
        $this->host = "mysql:dbname=library;host=localhost";
        $this->user = "root";
        $this->pass = "";
        $this->db = new PDO($this->host, $this->user, $this->pass);
    }
    
    public function logout($mail) {
         $select = "UPDATE `users` SET `logged`='0' WHERE `mail`='$mail'";
         $res = $this->db->query($select);
         return '1';    
    }
}
class books{
     private $db; 
    private $host; 
    private $user; 
    private $pass; 
    
    public function __construct() {
        $this->host = "mysql:dbname=library;host=localhost";
        $this->user = "root";
        $this->pass = "";
        $this->db = new PDO($this->host, $this->user, $this->pass);
    }
    
     public function return_book($bname,$author,$isbn,$mail){
            $delete="DELETE FROM `rented` WHERE `isbn`='$isbn' AND `author`='$author' AND `bname`='$bname' AND `mail`='$mail';";
            $res=$this->db->query($delete);
                
            $select="SELECT `amount`, `isbn` FROM `books` WHERE `isbn`='$isbn'";
            $res = $this->db->query($select);
            $wynik=$res->fetchAll();
            foreach($wynik as $w){
            if($w['isbn']==$isbn){
                $a=$w['amount'];
                $a=$a+1;
                $select1 = "UPDATE `books` SET `amount`='$a' WHERE `isbn`='$isbn'";
                $res1 = $this->db->query($select1);
                     }
            }
            
            $select2="SELECT `books_ct`, `mail` FROM `users` WHERE `mail`='$mail'";
            $res2 = $this->db->query($select2);
            $wynik2=$res2->fetchAll();
            foreach($wynik2 as $w2){
            if($w2['mail']==$mail){
                $c=$w2['books_ct'];
                $c=$c-1;
                $select3 = "UPDATE `users` SET `books_ct`='$c' WHERE `mail`='$mail'";
                $res3 = $this->db->query($select3);
                     }
            }  
     }
}
class permit{
    private $db; 
    private $host; 
    private $user; 
    private $pass; 
    
    public function __construct() {
        $this->host = "mysql:dbname=library;host=localhost";
        $this->user = "root";
        $this->pass = "";
        $this->db = new PDO($this->host, $this->user, $this->pass);
    }
    
    public function access($mail) {
       $select = "SELECT `logged`, `access` FROM `users` WHERE `mail`='$mail'";
       $res = $this->db->query($select);
       $wynik=$res->fetchAll();
       
       foreach($wynik as $w){
            if(($w['logged']=='1')&&($w['access']=='1')){
                return '1';
            }else if(($w['logged']=='1')&&($w['access']=='2')) {
                return '2';
            }else {
                return '0';
            }      
        }
         
    }
}
?>
<?php 
$access=new permit;
$permit=$access->access($_SESSION["mail"]);
if($permit=='2'){?>



<html>
    <head>
        <!-- tutaj dodamy skrypty i css-->
        <link rel="stylesheet" type="text/css" href="../script/style.css"/>
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>

    </head>
    <body>
        <div class="content">
            <!-- Górna część serwisu -->
            <div class="top">
                <!-- logo serwisu -->
                <div class="logo">

                </div>
                <!-- lewa część górna serwisu -->
                <div class="top_left">
                    <!--Wyszukiwarka serwisu -->
                    <div class="wyszukiwarka">
                        <span id="login">
                            <div class="login">
                                 <form action="" method="POST">
                                     <label>Welcome! You`r logged. Status: user.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><input class="submit" type="submit" name="logout" value="LOGOUT"> 
                </form>
            </div>
                        </span>
                        <span id="koszyk"></span>
                    </div>

                    <!-- Menu serwisu -->
                    <div class="menu">
                        <ul>
                            <li><a href="logged_employee.php">Home</a></li>
                            <li><a href="add_book_html.php">Add Book</a></li>
                            <li><a href="return_html.php">Return Book</a></li>
                            <li><a href="contact.php">Contact</a></li>
                            <div class="info" style="display:none;" title="php info">
               <span> Zamknij okno</span>
            </div>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- koniec góry serwisu -->
            <div class="clear"></div>
            <!-- Środek serwisu -->
            <div class="center">
                <div class="center_left"></div>
                <div class="center_middle">
                    <div id="register">
                       <?php
if(isset($_POST['return'])){
    $return_book = new books;
    if (isset($_POST['bname'])&&($_POST['bname'])
        &&isset($_POST['author'])&&($_POST['author'])
        &&isset($_POST['isbn'])&&($_POST['isbn'])
        &&isset($_POST['mail'])&&($_POST['mail'])){
        
         
             $return_book->return_book($_POST['bname'],$_POST['author'],$_POST['isbn'],$_POST['mail']);
             echo '</br>GRATULUJEMY</br>WYBRANA KSIAZKA ZOSTALA ZWROCONA</br>';
         
}
}
             ?>
                            <form action="redirect3.php" method="POST">
                    <input type="submit" name="redirect" value="return next book"/>
                </form>
            </div>
                </div>
                <div class="center_right"></div>
            </div>
            <!-- koniec środkowej części serwisu -->
            <div class="clear"></div>
            <!-- dolna część serwisu -->
            <div class="bottom">
                <div class="sitemap"></div>
                <div class="stopka"></div>
            </div>
            
        </div>
    </body>
</html>
<?php

}else{
    print 'NO ACCESS!!!!NO ACCESS!!!!NO ACCESS!!!!NO ACCESS!!!!NO ACCESS!!!!</br>
           NO ACCESS!!!!NO ACCESS!!!!NO ACCESS!!!!NO ACCESS!!!!NO ACCESS!!!!</br>';
}
?>

 <?php


    $logging=new logging();
    if(isset($_POST['logout'])){
            $logging->logout($_SESSION["mail"]);
            header("Location: ../index.php"); 
        }
    ?> 