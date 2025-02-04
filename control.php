<?php
include_once('../admin/model.php');
class control extends model
{
    function __construct()
    {
        session_start();
        model:: __construct();
        $path=$_SERVER['PATH_INFO'];

        switch($path)
        {
            case '/about':
            include_once('about.php');
            break;

            case '/profile':
            $where=array("username"=>$_SESSION['username']);
            $run=$this->select_where_join2('customer_details','city','customer_details.city_id=city.city_id',$where);
            $fetch=$run->fetch_object();
            include_once('profile.php');
            break;

            case '/contact':
            if(isset($_REQUEST['submit']))
            {
                $name=$_REQUEST['name'];
                $email=$_REQUEST['email'];
                $contact=$_REQUEST['contact'];
                $message=$_REQUEST['message'];

                $fullMessage = '<html><body>';
                $fullMessage = $fullMessage."<br/><br/>";
                $fullMessage = $fullMessage."".$from." sent a message using Inquiry Form";
                $fullMessage = $fullMessage."<br/><br/>";
                $fullMessage = $fullMessage."<table align=left border=1>";

                $fullMessage = $fullMessage."<tr ><td width=200px> Name</td> <td width=200px>".$name."</td></tr>";
            
                $fullMessage = $fullMessage."<tr ><td width=200px>E-Mail</td> <td width=200px>".$email."</td></tr>";


                
                $fullMessage = $fullMessage."<tr ><td width=200px>Subject</td> <td width=200px>".$contact."</td></tr>";


        
                $fullMessage = $fullMessage."<tr ><td width=200px>Message</td> <td width=200px>".$message."</td></tr>";
                        
                $fullMessage = $fullMessage.'</table></body></html>';

                $admin="khushignc@gmail.com";
                $to=$admin;
                $from=$email;
                $subject="New inquiry";
                mail($to,$subject,$fullMessage);

                $from1=$admin;
                $to1=$email;
                $subject1="Thanks for Inquiry";
                $fullMessage1 = '<html><body><br/>Thank you for contact us. <br/>We will get back you shortly.';
		        $fullMessage1 = $fullMessage1.'</body></html>';
                mail($to1,$subject1,$fullMessage1);

                echo '<br/><div style="color:#ff0000;margin-left:40px">';
                echo "Thank you for your Inquiry Us, We will in touch with you less then 12 hours.";
                echo "</div><br/>";

                $name="";
                $email="";
                $contact="";
                $message="";
            }
            include_once('contact.php');
            break;

            case '/truck':
            if(isset($_REQUEST['cate_id']))
            {
                $cate_id=$_REQUEST['cate_id'];
                $where=array("cate_id"=>$cate_id);
                $run=$this->select_where('category',$where);
                $fetch=$run->fetch_object();

            }
            include_once('truck.php');
            break;

            case '/category':
            $fetcharr=$this->selectall('category');
            include_once('category.php');
            break;

            case '/feedback':
            if(isset($_REQUEST['submit'])) 
            {
                $cust_id=$_REQUEST['cust_id'];
                $comment=$_REQUEST['comment'];
                $date=$_REQUEST['date'];

                $arr=array("cust_id"=>$cust_id,"comment"=>$comment,"date"=>$date);
                $res=$this->insert('feedback',$arr);
				if($res)
				{
					echo  "<script>alert('Feedback Registered')</script>";
				}
				else
				{
					echo "<script>alert('Not success')</script>";
				}
            }
            include_once('feedback.php');
            break;    
            
            case '/index':
            $fetcharr=$this->selectall('category');
            include_once('index.php');
            break;

            
            case '/booking':
            $fetcharr=$this->selectall('goods_type');
            if(isset($_REQUEST['cate_id']))
            {
                $cate_id=$_REQUEST['cate_id'];
				$where=array("cate_id"=>$cate_id);
				$run=$this->select_where('category',$where);
				$fetch=$run->fetch_object();
            }
            include_once('booking.php');
            break;
            
            case '/trackorder':
            include_once('trackorder.php');
            break; 

            case '/trackorderans':
            $booking_id=$_REQUEST['booking_id'];
            $where=array("booking_id"=>$booking_id);
            $run=$this->select_where('tracking',$where);
            $fetch=$run->fetch_object();
            include_once('trackorderans.php');
            break;

            case '/pricelist':
            $fetcharr=$this->selectall('city_rate');
            include_once('pricelist.php');
            break; 

            case '/invoice':
            if(isset($_REQUEST['booking_id']))
            {
                $booking_id=$_REQUEST['booking_id'];
                $where=array("booking_id"=>$booking_id);
                $run=$this->select_where_join5('invoice','branch','invoice.branch_id=branch.branch_id','parcel','invoice.parcel_id=parcel.parcel_id',$where);
                $fetch=$run->fetch_object();

                $where=array("cust_id"=>$_SESSION['cust_id']);
                $r=$this->select_where('customer_details',$where);
                $f=$r->fetch_object();

                $data=array("booking_id"=>$booking_id);
                $ru=$this->select_where_join2('booking','goods_type','booking.gt_id=goods_type.gt_id',$data);
                $fe=$ru->fetch_object();

                $where=array("booking_id"=>$booking_id);
                $var=$this->select_where('tracking',$where);
                $var1=$var->fetch_object();
            }
            include_once('invoice.php');
            break;

            case '/order':
            $where=array("cust_id"=>$_SESSION['cust_id']);
            $fetcharr=$this->select_where_join4('booking','category','booking.cate_id=category.cate_id',$where);
            include_once('order.php');
            break;

            case '/editprofile':
            $fetcharr=$this->selectall('city');
            if(isset($_REQUEST['edit_cust_id']))
            {
                $cust_id=$_REQUEST['edit_cust_id'];
				$where=array("cust_id"=>$cust_id);
				$run=$this->select_where('customer_details',$where);
				$fetch=$run->fetch_object();
				
				if(isset($_REQUEST['submit']))
				{
					$cust_name=$_REQUEST['cust_name'];
                    $city_id=$_REQUEST['city_id'];
                    $cust_add=$_REQUEST['cust_add'];
					$contact_no=$_REQUEST['contact_no'];
					$email=$_REQUEST['email'];
					$username=$_REQUEST['username'];
					
					$arr=array("cust_name"=>$cust_name,"city_id"=>$city_id,"cust_add"=>$cust_add,"contact_no"=>$contact_no,"email"=>$email,"username"=>$username);
					$res=$this->update('customer_details',$arr,$where);
					if($res)
					{
						echo "<script>
						alert('Update Success');
						window.location='profile';
						</script>";
					}
				}
            }
            include_once('editprofile.php');
            break;

            case '/signup':
            $fetcharr=$this->selectall('city');
            if(isset($_REQUEST['submit']))
            {
                $city_id=$_REQUEST['city_id'];
                $cust_name=$_REQUEST['cust_name'];
                $cust_add=$_REQUEST['cust_add'];
                $contact_no=$_REQUEST['contact_no'];
                $email=$_REQUEST['email'];
                $password=$_REQUEST['password'];
                $username=$_REQUEST['username'];

                $arr=array("city_id"=>$city_id,"cust_name"=>$cust_name,"cust_add"=>$cust_add,"contact_no"=>$contact_no,"email"=>$email,"password"=>$password,"username"=>$username);
                $res=$this->insert('customer_details',$arr);

                if($res)
                {
                    echo "<script>
					alert('Signup success');
					</script>";
                }
                else
                {
                    echo "<script>
					alert('Signup not success');
					</script>";
                }
            }    
            include_once('signup.php');
            break;
            
            case '/login':
            if(isset($_REQUEST['submit']))
            {
                $username=$_REQUEST['username'];
				$password=$_REQUEST['password'];
				
                $where=array("username"=>$username,"password"=>$password);
				$run=$this->select_where('customer_details',$where);
                $fetch=$run->fetch_object();
                $res=$run->num_rows; 
				if($res==1)
                {
                    $_SESSION['username']=$username;
                    $_SESSION['cust_id']=$fetch->cust_id;
                    echo "<script> 
                    alert('Login Success') 
                    window.location='index';
                    </script>";
                }
                else
                {
                    echo "<script> 
                    alert('Login Failed') 
                    window.location='login';
                    </script>";
                }
				
            }
            include_once('login.php');
            break;   
            
            case '/logout':
            unset($_SESSION['username']);
            echo "<script>
                 alert('Logout Success');
                 window.location='index';
                 </script>";
            break;

            case '/placeorder':
            include_once('placeorder.php');
            break;

            case '/checkout':
            $cust_id=$_REQUEST['cust_id'];
            $where=array("cust_id"=>$cust_id);
            $run=$this->select_where('customer_details',$where);
            $fetch=$run->fetch_object();

            $cate_id=$_REQUEST['cate_id'];
            $where1=array("cate_id"=>$cate_id);
            $run1=$this->select_where('category',$where1);
            $fetch1=$run1->fetch_object();

            $source=$_REQUEST['source'];
            $destination=$_REQUEST['destination'];
            $where2=array("source"=>$source,"destination"=>$destination);
            $run2=$this->select_where('city_rate',$where2);
            $fetch2=$run2->fetch_object();

            $gt_id=$_REQUEST['gt_id'];
            $where3=array("gt_id"=>$gt_id);
            $run3=$this->select_where('goods_type',$where3);
            $fetch3=$run3->fetch_object();

            $book_date=$_REQUEST['book_date'];
            $payment_type=$_REQUEST['payment_type'];
            
            if(isset($_REQUEST['submit']))
            {
                $cust_id=$_REQUEST['cust_id'];
                $cate_id=$_REQUEST['cate_id'];
                $source=$_REQUEST['source'];
                $destination=$_REQUEST['destination'];
                $gt_id=$_REQUEST['gt_id'];
                $book_date=$_REQUEST['book_date'];
                $payment_type=$_REQUEST['payment_type'];
                $price=$_REQUEST['price'];

                $arr=array("cust_id"=>$cust_id,"cate_id"=>$cate_id,"source"=>$source,"destination"=>$destination,"gt_id"=>$gt_id,"book_date"=>$book_date,"payment_type"=>$payment_type,"price"=>$price);
                $res=$this->insert('booking',$arr);
                if($res)
                {
                    echo  "<script>alert('Booking Success')
                            window.location='placeorder';
                          </script>";
                }
                else
                {
                    echo "<script>alert('Not success')</script>";
                }
            }
            
            include_once('checkout.php');
            break;

            default:
            include_once('404.php');
            break;
            

        }
    }
    
}
$obj=new control;
?>
    
