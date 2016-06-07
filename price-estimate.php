<?php 
include('header.php');
//echo "<pre>";
//print_r($_SESSION);

$sql_vendor = "SELECT `business_name`,`area_id`,`country_id`,`vendor_id`,`business_phone`,`business_email` FROM ".TABLE_PREFIX."user_vendor_details WHERE `vendor_id` = '".$_REQUEST['vid']."' AND `profile_complete` = 'Y'";
$sql_vendor = mysql_query($sql_vendor) or die(mysql_error());
$row_vendor = mysql_fetch_array($sql_vendor);

$area_name = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_areas WHERE `area_id` = '".$row_vendor['area_id']."'"));
//$city_name = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_cities WHERE `city_id` = '".$row_vendor['city_id']."'"));
//$country_name = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_countries WHERE `country_id` = '".$row_vendor['country_id']."'"));
$business_name = $row_vendor['business_name'];

if($_REQUEST['act']=="reset")
{
    unset($_SESSION['ceremony_selected']);
    unset($_SESSION['reception_selected']);
    unset($_SESSION['venue_service_id']);

    unset($_SESSION['step1']);
    unset($_SESSION['step2']);
    unset($_SESSION['step3']);
    unset($_SESSION['step4']);


    unset($_SESSION['weekday_id']);
    unset($_SESSION['season_id']);
    unset($_SESSION['guest_count_ceremony']);
    unset($_SESSION['guest_count_reception']);
    unset($_SESSION['selected_hours_reception']);
    unset($_SESSION['wedding_coordinator']);

    unset($_SESSION['ceremony_item_id']);

    unset($_SESSION['cocktail_hour']);
    unset($_SESSION['cocktail_item_id']);

    unset($_SESSION['reception_item_id']);
    unset($_SESSION['reception_menu_id']);
    unset($_SESSION['selected_beverage']);
    unset($_SESSION['reception_beverage_option_id']);
    unset($_SESSION['selected_perhead_drink']);
    unset($_SESSION['self_supplied_bottle_bring']);

    echo "<script>location.href='choose-services.php?vid=$_REQUEST[vid]';</script>";
}
?>
<script type="text/javascript">
    $(document).on('click','.favourite_click',function(){
      var login = "<?php echo $_SESSION['LOGIN'];?>";
      var login_id = "<?php echo $_SESSION['LOGIN_ID'];?>";
      var login_type = "<?php echo $_SESSION['LOGIN_TYPE'];?>";

      var fav_vendor_id = $(this).attr("alt");

      if(login == "TRUE" && parseInt(login_id) > 0 && login_type != "")
      {
        if(login_type=="User")
        {
          $.post('ajax/make_favourite.php',{fav_vendor_id:fav_vendor_id,type:'Vendor'},function(result){
            if(result==1)
            {
              alert('Thank you for making this your favourite.');
              return true;
            }
            else if(result==2)
            {
              alert('You have already make this your favourite.');
              return true;
            }
            else
            {
              alert('No article for this category.');
              return false;
            }
          });
        }
        else
        {
          alert("Only bride/groom can make favourite.");
          //$(".ratingForm-23456, .all-Overlay").fadeOut("slow"); 
          return false;
        }
      }
      else
      {
        alert("Log in to mark this venue as a favourite.");
        //$(".ratingForm-23456, .all-Overlay").fadeOut("slow"); 
        $("#login, .overlay2").fadeIn();
        return false;
      }

    });
</script>
<script type="text/javascript">
    function appointmentform_validation()
    {                           
        var req_email_patteren = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
        var contactno_patteren = /^[0-9\s]+$/;
        var alphabet_patteren = /^[a-zA-Z\s.]+$/;

        var preferred_wedding_date1 = $('#preferred_wedding_date1').val();
        var preferred_wedding_date2 = $('#preferred_wedding_date2').val();
        var preferred_appointment_date = $('#preferred_appointment_date').val();
        var appointment_firstname = $('#appointment_firstname').val();
        var appointment_lastname = $('#appointment_lastname').val();
        var appointment_email = $('#appointment_email').val(); 
        var appointment_phone = $('#appointment_phone').val();
        var appointment_message = $('#appointment_message').val();      

        if(preferred_wedding_date1 =="")
        {
            alert('Please Insert your Preferred Wedding Date #1.');
            $('#preferred_wedding_date1').focus();
            return false;
        }
        else if(preferred_wedding_date2 =="")
        {
            alert('Please Insert your Preferred Wedding Date #2.');
            $('#preferred_wedding_date2').focus();
            return false;
        }
        else if(preferred_appointment_date =="")
        {
            alert('Please Insert your Preferred Appointment Date.');
            $('#preferred_appointment_date').focus();
            return false;
        }
        else if(appointment_firstname =="")
        {
            alert('Please Insert your First Name.');
            $('#appointment_firstname').focus();
            return false;
        }
        else if(!alphabet_patteren.test(appointment_firstname))
        {
            alert('This Is Not a valid First Name.');
            $('#appointment_firstname').focus();
            return false;
        }
        else if(appointment_lastname =="")
        {
            alert('Please Insert your Last Name.');
            $('#appointment_lastname').focus();
            return false;
        }

        else if(!alphabet_patteren.test(appointment_lastname))
        {
            alert('This Is Not a valid Last Name.');
            $('#appointment_lastname').focus();
            return false;
        }
        else if(appointment_email == "")
        {
            alert('Email is required.');
            $('#appointment_email').focus();
            return false;

        }
        else if(!req_email_patteren.test(appointment_email))
        {
            alert('This is not a Valid Email, Please Correct This.');
            $('#appointment_email').focus();
            return false;

        } 
        else if(appointment_phone == "")
        {
            alert('Phone is required.');
            $('#appointment_phone').focus();
            return false;

        }
        else if(!contactno_patteren.test(appointment_phone))
        {
            alert('This is not a Valid Phone, Please Correct This.');
            $('#appointment_phone').focus();
            return false;

        } 
        else if(appointment_message == "")
        {
            alert('Give a message.');
            $('#appointment_message').focus();
            return false;

        }      

    }
</script>
<script type="text/javascript">
    $(document).on('click','#contact_info_show',function(){

        var contact_info = <?php echo stripslashes($row_vendor['business_phone']);?>;
        if(contact_info!="")
        {
          //$('#show_contact').toggle();
          $('#show_contact').html(contact_info);
        }
    });
</script>

<section class="vendor steps">
  	<h1><?php echo $business_name;?> <a href="javascript:void(0);"><?php echo $area_name['area_name'];?></a> </h1>
  	
    <?php include('instant_quote_header.php');?>
    
    <h2 class="setWd">Your Big Day Estimate Details
    	<a href="price-estimate.php?vid=<?php echo $_REQUEST['vid'];?>&act=reset" class="recalculate">
            <img src="images/back2.png" width="12" height="13" alt="back"> Re-calculate by choosing another option
        </a>
        <a class="back-to-venue-butt" <?php if($step4_link!=""){echo $step4_link;}elseif($step2_link!=""){echo $step2_link;}else{echo $step1_link;}?>>Back</a>
    </h2>
    
    <div class="estimate-left">
		<h3>Service Details :</h3>
        <div class="table setTableWidth">
            <?php
            if($_SESSION['weekday_id']>0)
            {
                $sql_choose_days = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_weekdays WHERE `weekday_id` = '".$_SESSION['weekday_id']."' AND `status` = 'Y'";
                $sql_choose_days = mysql_query($sql_choose_days);
                $row_choose_days = mysql_fetch_array($sql_choose_days);
                $weekday_price = $row_choose_days['weekday_price'];
                ?>
                <div class="row">
                    <div class="cell">Event Day</div>
                    <div class="cell">:</div>
                    <div class="cell"><?php echo $row_choose_days['weekday_name'];?></div>
                </div>
                <?php
            }

            if($_SESSION['season_id']>0)
            {
                $sql_month_exist = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_seasons WHERE `season_id` = '".$_SESSION['season_id']."' AND `status` = 'Y'";
                $sql_month_exist = mysql_query($sql_month_exist);
                $row_month_exist = mysql_fetch_array($sql_month_exist);
                $season_price = $row_month_exist['season_price'];
                ?>
                <div class="row">
                    <div class="cell">Season</div>
                    <div class="cell">:</div>
                    <div class="cell"><?php echo $row_month_exist['season_name'];?></div>
                </div>
                <?php
            }

            if($_SESSION['ceremony_selected'] == 'Yes')
            {
                if($_SESSION['guest_count_ceremony']>0)
                {
                    ?>
                    <div class="row">
                        <div class="cell">Guest Count for Ceremony</div>
                        <div class="cell">:</div>
                        <div class="cell"><?php echo $_SESSION['guest_count_ceremony'];?></div>
                    </div>
                    <?php
                }
            }

            if($_SESSION['reception_selected'] == 'Yes')
            {
                if($_SESSION['guest_count_reception']>0)
                {
                    ?>
                    <div class="row">
                        <div class="cell">Guest Count for Reception</div>
                        <div class="cell">:</div>
                        <div class="cell"><?php echo $_SESSION['guest_count_reception'];?></div>
                    </div>
                    <?php
                }
                if($_SESSION['selected_hours_reception']>0)
                {
                    ?>
                    <div class="row">
                        <div class="cell">Event time</div>
                        <div class="cell">:</div>
                        <div class="cell"><?php echo $_SESSION['selected_hours_reception'];?> Hours</div>
                    </div>
                    <?php
                }

                /*if($_SESSION['reception_menu_id']>0)
                {
                    $sql_choose_menus = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_reception_menus WHERE `reception_menu_id` = '".$_SESSION['reception_menu_id']."' AND `status` = 'Y'";
                    $sql_choose_menus = mysql_query($sql_choose_menus) or die(mysql_error());
                    $row_choose_menus = mysql_fetch_array($sql_choose_menus);
                    ?>
                    <div class="row">
                        <div class="cell">Menu Type </div>
                        <div class="cell">:</div>
                        <div class="cell"><?php echo $row_choose_menus['reception_menu_name'];?></div>
                    </div>
                    <?php
                }*/


                if(count($_SESSION['reception_menu_id']) > 0)
                {
                    $total_menu_total = 0;
                    ?>
                    <div class="row">
                        <div class="cell">Menu Type</div>
                        <div class="cell">:</div>
                        <div class="cell">
                            <?php
                            $m_no = 1;
                            foreach ($_SESSION['reception_menu_id'] as $k => $val) 
                            {
                                $each_service_total_price = 0;

                                $sql_choose_menus = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_reception_menus WHERE reception_menu_id = '".$val."'";
                                $sql_choose_menus = mysql_query($sql_choose_menus) or die(mysql_error());
                                $row_choose_menus = mysql_fetch_array($sql_choose_menus);

                                echo $row_choose_menus['reception_menu_name']."<br/>";
                                $m_no++;
                            }
                            ?>

                        </div>
                    </div>
                    <?php
                }


                if($_SESSION['selected_beverage']!="")
                {
                    if($_SESSION['selected_beverage']=="No")
                    {
                        $selected_beverage = 'I don’t need Beverage';
                    }
                    if($_SESSION['selected_beverage']=="venue_supplied")
                    {
                        $selected_beverage = 'Venue Provided Beverage Package';
                    }
                    if($_SESSION['selected_beverage']=="self_supplied")
                    {
                        $selected_beverage = 'Self-Supplied';
                    }
                    ?>
                    <div class="row">
                        <div class="cell">Beverage Package</div>
                        <div class="cell">:</div>
                        <div class="cell"><?php echo $selected_beverage;?></div>
                    </div>
                    <?php
                    if(count($_SESSION['reception_beverage_option_id']) > 0 && $_SESSION['selected_beverage']=="venue_supplied")
                    {
                        ?>
                        <div class="row">
                            <div class="cell">Beverage options</div>
                            <div class="cell">:</div>
                            <div class="cell">
                                <?php
                                $b_no = 1;
                                foreach($_SESSION['reception_beverage_option_id'] as $k => $val) 
                                {
                                    $each_service_total_price = 0;

                                    $sql_choose_options = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_reception_beverage_options WHERE reception_beverage_option_id = '".$val."'";
                                    $sql_choose_options = mysql_query($sql_choose_options) or die(mysql_error());
                                    $row_choose_options = mysql_fetch_array($sql_choose_options);

                                    echo $row_choose_options['reception_beverage_option_name']."<br/>";
                                    $b_no++;
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            ?>
            
        </div> 

        <h3>General Costs :</h3>
        <div class="table setTableWidth">
            <?php
            $service_included_hours = 0;
            $max_allowed_hours = 0;
            $per_extra_hour_price = 0;
            if(count($_SESSION['venue_service_id']) > 0)
            {
                $total_basic_rental = 0;
                ?>
                <div class="row">
                    <div class="cell">Services Required</div>
                    <div class="cell">:</div>
                    <div class="cell">
                        <?php
                        $s_no = 1;
                        foreach ($_SESSION['venue_service_id'] as $k => $val) 
                        {
                            $each_service_total_price = 0;

                            $sql_services = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_sevrices WHERE venue_service_id = '".$val."'";
                            $sql_services = mysql_query($sql_services) or die(mysql_error());
                            $row_services = mysql_fetch_array($sql_services);

                            if($row_services['service_name']=="Reception")
                            {
                                $service_included_hours = $row_services['service_included_hours'];
                                $max_allowed_hours = $row_services['max_allowed_hours'];
                                $per_extra_hour_price = $row_services['per_extra_hour_price'];
                                $cal_guest_num = $_SESSION['guest_count_reception'];
                            }
                            elseif($row_services['service_name']=="Ceremony")
                            {
                                $cal_guest_num = $_SESSION['guest_count_ceremony'];
                            }

                            if($row_services['service_price_type']=="FR")
                            {
                                $each_service_total_price = $row_services['service_price'];
                            } 
                            elseif($row_services['service_price_type']=="PP")
                            {
                                $each_service_total_price = $row_services['service_price_pp']*$cal_guest_num;
                            } 
                            elseif($row_services['service_price_type']=="BO")
                            {
                                $each_service_total_price = $row_services['service_price']+($row_services['service_price_pp']*$cal_guest_num);
                            } 

                            $total_basic_rental = $total_basic_rental+$each_service_total_price;

                            if($s_no == 1)
                            {
                                $service_price_note = $row_services['service_name'];
                            }
                            else
                            {
                                $service_price_note = $service_price_note." and ".$row_services['service_name'];
                            }
                            if($s_no == 1)
                            {
                                $service_price_note = $row_services['service_name'];
                            }


                            echo $row_services['service_type']." ".$row_services['service_name']."<br/>";
                            $s_no++;
                        }
                        ?>

                    </div>
                </div>
                <?php
            }
            ?>
        	
            <div class="row">
            	<div class="cell">Rental Fee</div>
                <div class="cell">:</div>
                <div class="cell">£<?php echo $total_basic_rental." (Price For ".$service_price_note.")";?></div>
            </div>
            <?php
            if($weekday_price > 0)
            {
                ?>
                <div class="row">
                    <div class="cell">Event Day Specific Fee</div>
                    <div class="cell">:</div>
                    <div class="cell">£<?php echo $weekday_price;?></div>
                </div>
                <?php
            }
            if($season_price > 0)
            {
                ?>
                <div class="row">
                    <div class="cell">Season Specific Fee</div>
                    <div class="cell">:</div>
                    <div class="cell">£<?php echo $season_price;?></div>
                </div>
                <?php
            }
            if($_SESSION['selected_hours_reception'] > $service_included_hours)
            {
                $extra_reservation_hours = $_SESSION['selected_hours_reception'] - $service_included_hours;
                $total_extra_reservation_price = ($per_extra_hour_price*$extra_reservation_hours);
                ?>
                <div class="row">
                    <div class="cell">Fees for additional hours</div>
                    <div class="cell">:</div>
                    <div class="cell">£<?php echo $total_extra_reservation_price;?></div>
                </div>
                <?php
            }

            $sql_venue_others = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_others WHERE `vendor_id` = '".$_REQUEST['vid']."'";
            $row_venue_others = mysql_fetch_array(mysql_query($sql_venue_others));
             
            if(isset($_SESSION['wedding_coordinator']))
            {
                $coordinator_rate = $row_venue_others['coordinator_rate'];
                if($row_venue_others['coordinator_package_included']=="Y")
                {
                    $package_included1 = " <span>(Included in Package)</span>";
                }   
                ?>
                <div class="row">
                    <div class="cell">Wedding coordination</div>
                    <div class="cell">:</div>
                    <div class="cell">£<?php echo $coordinator_rate.$package_included1;?></div>
                </div>
                <div class="row">
                    <div class="cell">On-site manager</div>
                    <div class="cell">:</div>
                    <div class="cell"><?php echo $_SESSION['wedding_coordinator'];?></div>
                </div>
                <?php
            }
            
            ?>        
        </div>

        <?php
        if($_SESSION['ceremony_selected'] == 'Yes')
        {
            ?>    
            <h3>Ceremony Options :</h3>
            
            <?php
            if(count($_SESSION['ceremony_item_id']) > 0 && $_SESSION['guest_count_ceremony'] >0)
            {
                $ceremony_item_total_price = 0;
                ?>
                <div class="table setTableWidth">
                    <?php
                    foreach ($_SESSION['ceremony_item_id'] as $k2 => $val2) 
                    {
                        $package_included2 = "";
                        $sql_ceremony_items = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_ceremony_items WHERE ceremony_item_id = '".$val2."'";
                        $sql_ceremony_items = mysql_query($sql_ceremony_items) or die(mysql_error());
                        $row_ceremony_items = mysql_fetch_array($sql_ceremony_items);

                        if($row_ceremony_items['ceremony_package_included']=="Y")
                        {
                            $package_included2 = " <span>(Included in Package)</span>";
                        } 
                        if($row_ceremony_items['ceremony_price_type']=="FR")
                        {
                            $ceremony_price_type = $row_ceremony_items['ceremony_per_item_price'];
                        }
                        else
                        {
                            $ceremony_price_type = ($row_ceremony_items['ceremony_per_item_price']*$_SESSION['guest_count_ceremony']);
                        }

                        $ceremony_item_total_price = $ceremony_item_total_price + $ceremony_price_type;
                        ?>
                        <div class="row">
                            <div class="cell"><?php echo $row_ceremony_items['ceremony_item_name'];?></div>
                            <div class="cell">:</div>
                            <div class="cell">£<?php echo $ceremony_price_type.$package_included2;?></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
        }
        if($_SESSION['reception_selected'] == 'Yes')
        {
            if($_SESSION['cocktail_hour']=="Yes")
            {
                ?>
                <h3>Cocktail Hour options :</h3>

                <?php
                if(count($_SESSION['cocktail_item_id']) > 0 && $_SESSION['guest_count_reception'] >0)
                {
                    $cocktail_item_total_price = 0;
                    ?>
                    <div class="table setTableWidth">
                        <?php
                        foreach ($_SESSION['cocktail_item_id'] as $k3 => $val3) 
                        {
                            $package_included3 = "";
                            $sql_cocktail_items = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_cocktail_items WHERE cocktail_item_id = '".$val3."'";
                            $sql_cocktail_items = mysql_query($sql_cocktail_items) or die(mysql_error());
                            $row_cocktail_items = mysql_fetch_array($sql_cocktail_items);
                            
                            if($row_cocktail_items['cocktail_package_included']=="Y")
                            {
                                $package_included3 = " <span>(Included in Package)</span>";
                            } 
                            if($row_cocktail_items['cocktail_price_type']=="FR")
                            {
                                $cocktail_price_type = $row_cocktail_items['cocktail_per_item_price'];
                            }
                            else
                            {
                                $cocktail_price_type = ($row_cocktail_items['cocktail_per_item_price']*$_SESSION['guest_count_reception']);
                            }

                            $cocktail_item_total_price = $cocktail_item_total_price+$cocktail_price_type;
                            ?>
                            <div class="row">
                                <div class="cell"><?php echo $row_cocktail_items['cocktail_item_name'];?></div>
                                <div class="cell">:</div>
                                <div class="cell">£<?php echo $cocktail_price_type.$package_included3;?></div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
            }

            if(count($_SESSION['reception_item_id']) > 0 && $_SESSION['guest_count_reception'] >0)
            {
                $reception_item_total_price = 0;
                ?>
                <h3>Reception Options :</h3>
                <div class="table setTableWidth">
                    <?php
                    foreach ($_SESSION['reception_item_id'] as $k4 => $val4) 
                    {
                        $package_included4 = "";
                        $sql_reception_items = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_reception_items WHERE reception_item_id = '".$val4."'";
                        $sql_reception_items = mysql_query($sql_reception_items) or die(mysql_error());
                        $row_reception_items = mysql_fetch_array($sql_reception_items);

                        if($row_reception_items['reception_package_included']=="Y")
                        {
                            $package_included4 = " <span>(Included in Package)</span>";
                        }

                        if($row_reception_items['reception_price_type']=="FR")
                        {
                            $reception_price_type = $row_reception_items['reception_per_item_price'];
                        }
                        else
                        {
                            $reception_price_type = ($row_reception_items['reception_per_item_price']*$_SESSION['guest_count_reception']);
                        }

                        $reception_item_total_price = $reception_item_total_price + $reception_price_type;
                        ?>
                        <div class="row">
                            <div class="cell"><?php echo $row_reception_items['reception_item_name'];?></div>
                            <div class="cell">:</div>
                            <div class="cell">£<?php echo $reception_price_type.$package_included4;?></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php

            }

            if(count($_SESSION['reception_menu_id']) > 0 && $_SESSION['guest_count_reception'] >0)
            {
                $menu_total_price = 0;
                ?>
                <h3>Food Options :</h3>
                <div class="table setTableWidth">
                    <?php
                    foreach ($_SESSION['reception_menu_id'] as $k5 => $val5) 
                    {
                        $package_included5 = "";
                        $sql_choose_menus = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_reception_menus WHERE reception_menu_id = '".$val5."'";
                        $sql_choose_menus = mysql_query($sql_choose_menus) or die(mysql_error());
                        $row_choose_menus = mysql_fetch_array($sql_choose_menus);
                        $menu_total_price = $menu_total_price + ($row_choose_menus['reception_per_menu_price']*$_SESSION['guest_count_reception']);

                        if($row_choose_menus['reception_menu_package_included']=="Y")
                        {
                            $package_included5 = " <span>(Included in Package)</span>";
                        }
                        ?>
                        <div class="row">
                            <div class="cell"><?php echo $row_choose_menus['reception_menu_name'];?></div>
                            <div class="cell">:</div>
                            <div class="cell">£<?php echo $row_choose_menus['reception_per_menu_price']*$_SESSION['guest_count_reception'].$package_included5;?></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php

            }

            if($_SESSION['selected_beverage']=="self_supplied")
            {
                if($row_venue_others['self_supplied_price_type']=="Priced Per Bottle")
                {
                    $total_beverage_price = $row_venue_others['self_supplied_price']*$_SESSION['self_supplied_bottle_bring'];
                    $total_beverage_price_type = " (Price for ".$_SESSION['self_supplied_bottle_bring']." bottles)";
                }
                else
                {
                    $total_beverage_price = $row_venue_others['self_supplied_price'];
                    $total_beverage_price_type = " (Flat price)";
                }
                ?>
                <h3>Beverage Options :</h3>
                <div class="table setTableWidth">
                    <div class="row">
                        <div class="cell">Self Supplied Arrangement cost</div>
                        <div class="cell">:</div>
                        <div class="cell">£<?php echo $total_beverage_price.$total_beverage_price_type;?></div>
                    </div>
                </div>
                <?php
            }
            elseif($_SESSION['selected_beverage']=="venue_supplied" && count($_SESSION['reception_beverage_option_id']) > 0 && $_SESSION['guest_count_reception'] >0 && $_SESSION['selected_perhead_drink'] >0)
            {
                $total_beverage_price = 0;
                ?>
                <h3>Beverage Options (Venue Supplied):</h3>
                <div class="table setTableWidth">
                    <?php
                    foreach ($_SESSION['reception_beverage_option_id'] as $k6 => $val6) 
                    {
                        $package_included6 = "";
                        $sql_choose_options = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_reception_beverage_options WHERE reception_beverage_option_id = '".$val6."'";
                        $sql_choose_options = mysql_query($sql_choose_options) or die(mysql_error());
                        $row_choose_options = mysql_fetch_array($sql_choose_options);
                        $total_beverage_price = $total_beverage_price + ($row_choose_options['reception_per_beverage_option_price']*$_SESSION['guest_count_reception']*$_SESSION['selected_perhead_drink']);

                        if($row_choose_options['reception_beverage_option_package_included']=="Y")
                        {
                            $package_included6 = " <span>(Included in Package)</span>";
                        }
                        ?>
                        <div class="row">
                            <div class="cell"><?php echo $row_choose_options['reception_beverage_option_name'];?></div>
                            <div class="cell">:</div>
                            <div class="cell">£<?php echo ($row_choose_options['reception_per_beverage_option_price']*$_SESSION['guest_count_reception']*$_SESSION['selected_perhead_drink']).$package_included6;?></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }  
                    
        }

        $total_guest_both = 0;
        $guest_count_title = "";
        $right_guest_count_title = "";

        if($_SESSION['ceremony_selected'] == 'Yes' && $_SESSION['guest_count_ceremony'] > 0)
        {
            $total_guest_both = $total_guest_both+$_SESSION['guest_count_ceremony'];
            $guest_count_title = "ceremony";
            $right_guest_count_title = "Estimate for ".$_SESSION['guest_count_ceremony']." ceremony guests";
        }
        if($_SESSION['reception_selected'] == 'Yes' && $_SESSION['guest_count_reception'] > 0)
        {
            $total_guest_both = $total_guest_both+$_SESSION['guest_count_reception'];
            if($guest_count_title!="")
            {
                $guest_count_title = $guest_count_title." and reception";
                $right_guest_count_title = $right_guest_count_title." and ".$_SESSION['guest_count_reception']." reception guests";
            }
            else
            {
                $guest_count_title = "reception";
                $right_guest_count_title = "Estimate for ".$_SESSION['guest_count_reception']." reception guests";
            }
        }


        $total_costing = $total_basic_rental+$weekday_price+$season_price+$total_extra_reservation_price+$coordinator_rate+$ceremony_item_total_price+$cocktail_item_total_price+$reception_item_total_price+$menu_total_price+$total_beverage_price;

        if($total_guest_both > 0)
        {
            $total_costing_perhead = floor($total_costing/$total_guest_both);
        }
        else
        {
            $total_costing_perhead = "No. of guest not given.";
        }
        
        ?>
        <div class="arr">
        		<div class="table setTableWidth no-border no-padding">
                    <div class="row">
                        <div class="cell">Estimate Per Venue</div>
                        <div class="cell">£<?php echo $total_costing;?> +VAT</div>
                    </div>
                    <div class="row">
                        <div class="cell">Price Per Person (<?php echo $guest_count_title;?>)</div>
                        <div class="cell">£<?php echo $total_costing_perhead;?></div>
                    </div>
                </div>
        </div>
        <a href="javascript:void(0);" class="submit-vendor setwidths popupsec">Request Appointment</a>
        <a class="back-to-venue-butt back-to-venue-butt-nnn" <?php if($step4_link!=""){echo $step4_link;}elseif($step2_link!=""){echo $step2_link;}else{echo $step1_link;}?>>Back</a>
        
        <div class="clearfix"></div>
        <div class="spot">
        	<h4>About the Big Day Estimate <sup>TM</sup></h4>
			<p>Big Day is a budget estimate tool based on options that you have selected and information provided to us by venues and their vendors. These are based on starting prices for each item. Some price(s) shown are an estimate provided by Big Day venue specialists based on the average starting prices for this item in this specific area. If you choose to upgrade certain amenities, rentals, guest count, and/or menu and beverage selections, then the pricing is subject to change at time of booking. Prices may also change based on availability. Please book an appointment with the venue to confirm all prices. Big Day is not an exact quote and you should not rely on these estimates as any final pricing.</p>
        </div>

    </div>    
    
    <div class="estimate-rightpanel">
    
        <h6><?php echo substr($business_name,0,30);?></h6>
        <div class="guests">
            
            <div class="guests-left">
                <span><?php echo $right_guest_count_title;?></span>
                <span>£<?php echo $total_costing;?></span>
                <span>Price Per Person  <span> £<?php echo $total_costing_perhead;?></span></span>
                <span><a href="venue-details.php?id=<?php echo $_REQUEST['vid'];?>">Venue Details</a></span>
            </div>
            <div class="guests-right">
                <ul>
                    <li><a href="javascript:void(0);" alt="<?php echo $row_vendor['vendor_id'];?>" class="favourite_click">Favourite</a></li>
                    <li>
                        <a href="javascript:void(0);" id="contact_info_show">
                            <span id="show_contact">Contact</span>
                            <span id="show_contact1" style="display:none;"><?php echo stripslashes($row_vendor['business_phone']);?></span>
                        </a> 
                    </li>
                    <!--<li><a href="javascript:void(0);">Send</a></li>-->
                </ul>
            </div>
            <div class="clearfix"></div>
     
        </div>
        <div class="guests">
            <h5>Request An Appointment</h5>
            <!--<input type="text" class="app-date datepicker" placeholder="Select Date">-->
            <p>Site tours must be made in advance. Please complete this form and leave a message for the venue.</p>
            <a href="#" class="submit-vendor popupsec">Request Appointment</a>
            <div class="clearfix"></div>
            <?php
            if($_SESSION['LOGIN'] == "TRUE" && $_SESSION['LOGIN_ID'] > 0 && $_SESSION['LOGIN_TYPE'] == "User")
            {
              ?>
              <a href="venue_price_estimate_pdf.php?dashboard=1&vid=<?php echo $_REQUEST['vid'];?>" class="save-to-big-day">Save Big Day Estimates On My Dashboard</a>
              <?php 
            }
            else
            {
              ?>
              <a href="venue_price_estimate_pdf.php?vid=<?php echo $_REQUEST['vid'];?>" class="save-to-big-day">Download Big Day Estimates</a>
              <?php
            }
            ?>
            
            <div class="clearfix"></div>
        </div>

        <a href="javascript:void(0);" class="back-to-venue setwidths contact-form-open">Contact Venue</a> 
        
        <h3>More Venues In Your Area</h3>
        <?php
        $sql_vendor_relevant = "SELECT * FROM ".TABLE_PREFIX."user_vendor_details WHERE 
                                  `vendor_id` != '".$_REQUEST['vid']."' AND 
                                  `profile_complete` = 'Y' AND 
                                  `country_id` = '".$row_vendor['country_id']."' ORDER BY `vendor_id` DESC LIMIT 3";
        $sql_vendor_relevant = mysql_query($sql_vendor_relevant) or die(mysql_error());
        while($row_vendor_relevant = mysql_fetch_array($sql_vendor_relevant))
        {
          $area_name = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_areas WHERE `area_id` = '".$row_vendor_relevant['area_id']."'"));
          $city_name = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_cities WHERE `city_id` = '".$row_vendor_relevant['city_id']."'"));
          $country_name = mysql_fetch_array(mysql_query("SELECT * FROM ".TABLE_PREFIX."vendor_countries WHERE `country_id` = '".$row_vendor_relevant['country_id']."'"));
          ?>
          <a  href="venue-details.php?id=<?php echo $row_vendor_relevant['vendor_id'];?>" class="side-image">
            <div class="image"><img width="360" height="198" alt="poc" src="<?php echo $row_vendor_relevant['featured_image'];?>"></div>
            <p><?php echo stripcslashes($row_vendor_relevant['business_name']);?></p> 
            <p><?php echo $area_name['area_name'];?> </p>
          </a> 
          <?php 
        }
        ?>
  
    </div>
    
    <div class="clearfix"></div>
    
</section>

<div class="overlay-bgg-appoint" >
	<div class="dialog-box-appoint-rty">
        <div class="dialog-box-appoint-scroll">
            <div class="closebutt-appoint">X</div>
                <h2>Appointment Request for <?php echo $business_name;?></h2>
                <div class="dialog-box-inner-appoint">
                    <?php 
                    if($_SESSION['LOGIN'] == "TRUE" && $_SESSION['LOGIN_ID'] > 0 && $_SESSION['LOGIN_TYPE'] == "User")
                    {
                     
                        $logged_user_details = mysql_fetch_array(mysql_query("SELECT `user_firstname`,`user_lastname`,`user_wedding_date`,`user_phone` FROM ".TABLE_PREFIX."user_client_details WHERE `user_id` = '".$_SESSION['LOGIN_ID']."'"));
                        $logged_user_email = mysql_fetch_array(mysql_query("SELECT `user_email` FROM ".TABLE_PREFIX."users WHERE `user_id` = '".$_SESSION['LOGIN_ID']."'"));    

                        if($logged_user_details['user_wedding_date']!=""){$preferred_wedding_date1 = date('d-m-Y',strtotime($logged_user_details['user_wedding_date']));}   
                        if($logged_user_details['user_firstname']!=""){$appointment_firstname = $logged_user_details['user_firstname'];}
                        if($logged_user_details['user_lastname']!=""){$appointment_lastname = $logged_user_details['user_lastname'];}
                        if($logged_user_details['user_phone']!=""){$appointment_phone = $logged_user_details['user_phone'];} 
                        if($logged_user_email['user_email']!=""){$appointment_email = $logged_user_email['user_email'];}         
                    }
                    ?>

                    <form method="POST" name="appointmentform" id="appointmentform" action="thank-you-insta-appointment.php" onsubmit="return appointmentform_validation();">
                        <input type="hidden" name="flag" value="appointmentform">
                        <input type="hidden" name="vid" value="<?php echo $_REQUEST['vid'];?>">
                        <div class="row-sec-box23 row-commen">
                    	    <h3>When are you planning to get maried?</h3>
                        
                    		<div class="pul-left">
                        		<h4>Preferred Wedding Date #1:</h4>
                                <input type="text" class="datepicker" name="preferred_wedding_date1" id="preferred_wedding_date1" value="<?php echo $preferred_wedding_date1;?>"/>
                            </div>
                            <div class="pul-right">
                                <h4>Preferred Wedding Date #2:</h4>
                                <input type="text" class="datepicker" name="preferred_wedding_date2" id="preferred_wedding_date2"/>
                            </div>
                            
                        </div>  

                        <div class="row-sec-box24 row-commen">
                        	<h3>Select a preferred appointment date for a site tour.</h3>
                    		<div class="fulll-widthh">
                        		<h4>Preferred Appointment Date #1:</h4>
                                <input type="text" class="datepicker" name="preferred_appointment_date" id="preferred_appointment_date"/>
                            </div>
                            <p>Site tours must be made in advance. Please complete this form and leave a message for the venue.</p>
                                
                        </div> 

                        <div class="row-sec-box25 row-commen">
                        	<h3>Your Contact Information</h3>
                        	<div class="fulll-widthh">
                                <div class="inner-roww">
                            		<input type="text" placeholder="First Name" name="appointment_firstname" id="appointment_firstname" value="<?php echo $appointment_firstname;?>"/>
                                    <input type="text" placeholder="Last Name" name="appointment_lastname" id="appointment_lastname"  value="<?php echo $appointment_lastname;?>"/>
                                    <input type="text" placeholder="Email"name="appointment_email" id="appointment_email" value="<?php echo $appointment_email;?>" />
                                    <input type="text" placeholder="Phone Number" name="appointment_phone" id="appointment_phone"  value="<?php echo $appointment_phone;?>"/>
                                </div>
                            </div>                       

                        </div>    

                        <!--<div class="row-sec-box26 row-commen">
                        	<h3>Create Account</h3>
                        	<div class="fulll-widthh">
                                <div class="inner-roww">
                            		<input type="password" placeholder="Password" />
                            		<input type="password" placeholder="Password Confirm" />
                                </div>
                            </div> 
                        </div>-->
                        
                        <div class="row-sec-box27 row-commen">
                        	<h3>Leave a message:</h3>
                    		<div class="fulll-widthh">
                            	<textarea placeholder="Text here" name="appointment_message" id="appointment_message"></textarea>
                            </div>

                        </div>
                        
                        <div class="row-commen">
                        
                            <p>Upon clicking submit. Big Day will send your contact information and your Big Day Estimate
                                <sup>TM</sup> to 
                                <span class="highlightText"><?php echo $business_name;?></span> 
                                and you have accepted our 
                                <a href="terms-of-use.php" target="_blank">Terms of Use</a> and 
                                <a href="privacy-policy.php"  target="_blank">Privacy Policy</a>.
                            </p>                    
                        
                            

                        </div>

                        <div class="row-sec-box27 row-commen">

                           <div class="buttonsecnn">

                   				<input type="button" class="close-butt-nn butt-des" value="Back" />
                                <input type="submit" class="butt-des" value="Request Appointment" />

                           </div>
                        </div>
                    </form>          
                </div> 
            </div> 
            <div class="clearfix"></div>     
        </div>
    </div>
</div>
<script>
    $(function() 
    {
        $( ".datepicker" ).datepicker({minDate: 0,dateFormat: 'dd-mm-yy'});
    });
</script>
<!--------------------------------------Contact Form------------------------------------------------------------>
    <div class="all-Overlay"></div>
    <div class="contactForm-23456" style="display:none">
      <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" name="venue-contact-form" id="venue-contact-form">
        <input type="hidden" name="venue_contact_email_to" value="<?php echo stripslashes($row_vendor['business_email']);?>">
        <input type="hidden" name="venue_vendor_id" value="<?php echo $row_vendor['vendor_id'];?>">
          <div class="rel"><img src="images/close-btn.jpg" width="39" height="39" alt="close"> </div>
        
        <div>
          <h2>Contact</h2>
          <div id="venue_contact_msg"></div>
        
          <div>First Name <input type="text" class="text" name="venue_contact_first_name" id="venue_contact_first_name"></div>
          <div>Last Name <input type="text" class="text" name="venue_contact_last_name" id="venue_contact_last_name"></div>
          <div>Email <input type="text" class="text" name="venue_contact_email" id="venue_contact_email"></div>
          <div>Phone No <input type="text" class="text" name="venue_contact_phone" id="venue_contact_phone"></div>
          <div>Pending Wedding date <input type="text" class="date datepicker" readonly name="venue_contact_wedding_date" id="venue_contact_wedding_date"></div>
          <div>Leave a Message <textarea name="venue_contact_message" id="venue_contact_message"></textarea></div>
          <div><input  type="button" id="venue_contact_button" class="insta-quote-btn" value="Send"></div>
        </div>
      </form>

    </div>
    <script>
        $(".contact-form-open").click(function(e) {
            $(".contactForm-23456, .all-Overlay").fadeIn();
        });
        $(".contactForm-23456 .rel img").click(function(e) {
            $(this).parent().parent().parent().fadeOut();
            $(".all-Overlay").fadeOut();
      });

      $(function() {
        $( ".datepicker" ).datepicker({minDate: 0,dateFormat: 'dd-mm-yy'});
      });

      $(document).on('click','#venue_contact_button',function(){
        var req_email_patteren = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
        var contactno_patteren = /^[0-9\s]+$/;
        var alphabet_patteren = /^[a-zA-Z\s.]+$/;

        var venue_contact_first_name = $('#venue_contact_first_name').val();
        var venue_contact_last_name = $('#venue_contact_last_name').val();
        var venue_contact_email = $('#venue_contact_email').val();
        var venue_contact_phone = $('#venue_contact_phone').val();
        var venue_contact_wedding_date = $('#venue_contact_wedding_date').val();
        var venue_contact_message = $('#venue_contact_message').val();

        if(venue_contact_first_name =="")
        {
            alert('Please Insert you first Name.');
            $('#venue_contact_first_name').focus();
            return false;
        }

        else if(!alphabet_patteren.test(venue_contact_first_name))
        {
            alert('This Is Not a valid first Name.');
            $('#venue_contact_first_name').focus();
            return false;
        }
        if(venue_contact_last_name =="")
        {
            alert('Please Insert you Last Name.');
            $('#venue_contact_last_name').focus();
            return false;
        }
        else if(!alphabet_patteren.test(venue_contact_last_name))
        {
            alert('This Is Not a valid Last Name.');
            $('#venue_contact_last_name').focus();
            return false;
        }
        else if(venue_contact_email == "")
        {
            alert('Email is required.');
            $('#venue_contact_email').focus();
            return false;
        }
        else if(!req_email_patteren.test(venue_contact_email))
        {
            alert('This is not a Valid Email, Please Correct This.');
            $('#venue_contact_email').focus();
            return false;
        }
        else if(venue_contact_phone == "")
        {
            alert('Phone No. is required.');
            $('#venue_contact_phone').focus();
            return false;
        }
        else if(!contactno_patteren.test(venue_contact_phone))
        {
            alert('This is not a Valid Contact Number, Please Correct This.');
            $('#venue_contact_phone').focus();
            return false;
        }
        else if(venue_contact_wedding_date == "")
        {
            alert('Please enter wedding date.');
            $('#venue_contact_wedding_date').focus();
            return false;

        }
        else if(venue_contact_message == "")
        {
            alert('Please enter some message.');
            $('#venue_contact_message').focus();
            return false;

        }
        else
        {
          $.post('ajax/venue_contact_form_submit.php',$("#venue-contact-form").serialize(),function(result){
            if(result==1)
            {
              
              $('#venue_contact_msg').html("<div class='form-success' id='venue_contact_success'>Thank you. We'll get back to you soon.</div>");
              $(".contactForm-23456, .all-Overlay").fadeOut();
              return true;
            }
            else if(result==2)
            {
              
              $('#venue_contact_msg').html("<div class='form-failed' id='venue_contact_failed'>Sorry.Please try again.</div>");
              return true;
            }
            else
            {
              $('#venue_contact_msg').html('<div class="form-failed" id="venue_contact_failed">Please fill up all the mendatory fields.</div>');
              return false;
            }
          });
        }
      });
    </script>

<!--------------------------------------Contact Form------------------------------------------------------------>

<?php include('footer.php');?>