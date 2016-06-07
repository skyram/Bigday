<?php 
include('dashboard_header_top.php');

if($_SESSION['LOGIN'] == "TRUE" && $_SESSION['LOGIN_ID'] > 0)
{
  if($_SESSION['LOGIN_TYPE'] != "Vendor")
  {
    echo "<script>location.href='logout.php';</script>";
  }
}
else
{
    echo "<script>location.href='index.php';</script>";
}

$my_details = "SELECT `vendor_id` FROM ".TABLE_PREFIX."user_vendor_details WHERE user_id = '".$_SESSION['LOGIN_ID']."'";
$my_details = mysql_query($my_details) or die(mysql_error());
$my_details = mysql_fetch_array($my_details);


if(isset($_REQUEST['flag']) && $_REQUEST['flag'] == "2")
{  
    $chk_already_main = "SELECT `other_id` FROM ".TABLE_PREFIX."vendor_venue_others WHERE `vendor_id` = '".$my_details['vendor_id']."'";
    $chk_already_main = mysql_query($chk_already_main);
    $chk_num_main = mysql_num_rows($chk_already_main);

    if($_REQUEST['beverage_alcohol_provide'] == 'N')
    {
        $beverage_venue_supplied = 'N';
        $beverage_self_supplied = 'N';
    }
    else
    {
        $beverage_venue_supplied = $_REQUEST['beverage_venue_supplied'];
        $beverage_self_supplied = $_REQUEST['beverage_self_supplied'];
    }
    if($chk_num_main==0)
    {
        mysql_query("INSERT INTO ".TABLE_PREFIX."vendor_venue_others SET 
                        `vendor_id` = '".$my_details['vendor_id']."', 
                        `beverage_alcohol_provide`  = '".$_REQUEST['beverage_alcohol_provide']."', 
                        `beverage_venue_supplied` = '".$beverage_venue_supplied."', 
                        `beverage_self_supplied`  = '".$beverage_self_supplied."',
                        `self_supplied_price_type` = '".$_REQUEST['self_supplied_price_type']."',
                        `self_supplied_price`  = '".$_REQUEST['self_supplied_price']."'");
    }
    else
    {
        mysql_query("UPDATE ".TABLE_PREFIX."vendor_venue_others SET  
                        `beverage_alcohol_provide`  = '".$_REQUEST['beverage_alcohol_provide']."', 
                        `beverage_venue_supplied` = '".$beverage_venue_supplied."', 
                        `beverage_self_supplied`  = '".$beverage_self_supplied."',
                        `self_supplied_price_type` = '".$_REQUEST['self_supplied_price_type']."',
                        `self_supplied_price`  = '".$_REQUEST['self_supplied_price']."' 
                        WHERE `vendor_id` = '".$my_details['vendor_id']."'");
    }
    foreach($_REQUEST['reception_beverage_option_id'] as $k=>$val)
    {
        if($val > 0)
        {
            $chk_already = mysql_query("SELECT `reception_beverage_option_id` FROM ".TABLE_PREFIX."vendor_venue_reception_beverage_options WHERE 
                                        `vendor_id` = '".$my_details['vendor_id']."' AND `reception_beverage_option_name` = '".$_REQUEST['reception_beverage_option_name'][$k]."'");

            $chk_num = mysql_num_rows($chk_already);

            if($_REQUEST['reception_beverage_option_name'][$k]!="")
            {
                if($chk_num == 0)
                {
                    $sql_query = "UPDATE ".TABLE_PREFIX."vendor_venue_reception_beverage_options SET 
                                    `reception_beverage_option_name` = '".$_REQUEST['reception_beverage_option_name'][$k]."',
                                    `reception_beverage_option_package_included` = '".$_REQUEST['reception_beverage_option_package_included'][$k]."',
                                    `reception_per_beverage_option_price` = '".$_REQUEST['reception_per_beverage_option_price'][$k]."',
                                    `status` = '".$_REQUEST['status'][$k]."'
                                    WHERE `reception_beverage_option_id` = '".$val."'";
                }
                else
                {
                    $sql_query = "UPDATE ".TABLE_PREFIX."vendor_venue_reception_beverage_options SET 
                                    `reception_beverage_option_package_included` = '".$_REQUEST['reception_beverage_option_package_included'][$k]."',
                                    `reception_per_beverage_option_price` = '".$_REQUEST['reception_per_beverage_option_price'][$k]."',
                                    `status` = '".$_REQUEST['status'][$k]."'
                                    WHERE `reception_beverage_option_id` = '".$val."'";
                }

                mysql_query($sql_query) or die(mysql_error());
            }

            elseif($_REQUEST['reception_beverage_option_name'][$k]=="")
            {
                mysql_query("DELETE FROM ".TABLE_PREFIX."vendor_venue_reception_beverage_options WHERE `reception_beverage_option_id` = '".$val."'");
            }
        }

        else
        {
            $chk_already = mysql_query("SELECT `reception_beverage_option_id` FROM ".TABLE_PREFIX."vendor_venue_reception_beverage_options WHERE 
                                            `vendor_id` = '".$my_details['vendor_id']."' AND `reception_beverage_option_name` = '".$_REQUEST['reception_beverage_option_name'][$k]."'");
            $chk_num = mysql_num_rows($chk_already);
            if($_REQUEST['reception_beverage_option_name'][$k]!="" && $chk_num == 0)
            {
                $sql_query = "INSERT INTO ".TABLE_PREFIX."vendor_venue_reception_beverage_options SET 
                                `vendor_id` = '".$my_details['vendor_id']."',
                                `reception_beverage_option_name` = '".$_REQUEST['reception_beverage_option_name'][$k]."', 
                                `reception_beverage_option_package_included` = '".$_REQUEST['reception_beverage_option_package_included'][$k]."',
                                `reception_per_beverage_option_price` = '".$_REQUEST['reception_per_beverage_option_price'][$k]."',
                                `status` = '".$_REQUEST['status'][$k]."'";

                mysql_query($sql_query) or die(mysql_error());
            }
        }

    }
    echo "<script>location.href='dashboard-vendor-venue-reception-beverage-options.php?msg=uus1';</script>";
}
?>

<section class="dashboard-body">
    
    <?php include('leftbar_vendor.php');?>

    <div class="dashboard-main-area">

        <?php include('dashboard_header.php');?>

        <script type="text/javascript">
            $(document).on('click','#each_reception_beverage_option_add',function(){
                var last_count = $('.add_reception_beverage_option').length;
                var current_count = (last_count+1);

                $('.main tbody').append('<tr class="add_reception_beverage_option"><input type="hidden" name="reception_beverage_option_id[]" id="reception_beverage_option_id'+current_count+'" value="0"><td align="center"><input type="text" name="reception_beverage_option_name[]" id="reception_beverage_option_name'+current_count+'" value=""></td><td align="center"><select style="width:70px;" name="reception_beverage_option_package_included[]" id="reception_beverage_option_package_included'+current_count+'"><option Value="N">No</option><option Value="Y">Yes</option></select></td><td align="center"><input type="text" name="reception_per_beverage_option_price[]" id="reception_per_beverage_option_price'+current_count+'" Placeholder="" value=""></td><td align="center"><select style="width:70px;" name="status[]" id="status'+current_count+'"><option Value="N">Inactive</option><option Value="Y">Active</option></select></td></tr>');
            });

        </script>

        <div class="main-area">
            <h1>Reception Beverage Options</h1>
            <?php
            if($_REQUEST['msg']=="uus1")
            {
              ?>
              <div class='form-success'>Your Venue Reception beverage options have been successfully saved.</div>
              <?php
            }
            if($_REQUEST['msg']=="uus2")
            {
              ?>
              <div class='form-success'>Your Venue Reception beverage_options have been successfully updated</div>
              <?php
            }

            $row_venue_others = mysql_fetch_array(mysql_query("SELECT `beverage_alcohol_provide`,`beverage_venue_supplied`,`beverage_self_supplied`,`self_supplied_price_type`,`self_supplied_price` FROM ".TABLE_PREFIX."vendor_venue_others WHERE `vendor_id` = '".$my_details['vendor_id']."'"));
            ?>            

            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" onsubmit="return form_validation();">
                <input type="hidden" name="flag" value="2">
                <input type="hidden" name="vendor_id" value="<?php echo $my_details['vendor_id'];?>">

                <div class="form-rowh">
                    <label>Provide Alcohol <span>:</span></label>
                    <span class="radio slecc-boxx">
                        <input id="beverage_alcohol_provide1" type="radio" name="beverage_alcohol_provide" onclick="document.getElementById('hidden1').style.display='block'" <?php if($row_venue_others['beverage_alcohol_provide']=="Y"){echo "checked";}?> value="Y" >
                        <label class="check-radio" for="beverage_alcohol_provide1" >Yes</label>
                        <input id="beverage_alcohol_provide2" type="radio" name="beverage_alcohol_provide" onclick="document.getElementById('hidden1').style.display='none'; document.getElementById('hidden2').style.display='none'; document.getElementById('hidden3').style.display='none';" <?php if($row_venue_others['beverage_alcohol_provide']=="N" || !isset($row_venue_others['beverage_alcohol_provide']) || $row_venue_others['beverage_alcohol_provide']==""){echo "checked";}?> value="N">
                        <label class="check-radio" for="beverage_alcohol_provide2" >No</label>
                    </span>
                </div>

                <div id="hidden1" <?php if($row_venue_others['beverage_alcohol_provide']=="Y"){?>style="display:block;";<?php }else{?>style="display:none;";<?php }?>>
                    <div class="form-rowh">
                        <label>Venue-Supplied <span>:</span></label>
                        <span class="radio slecc-boxx">
                            <input id="beverage_venue_supplied1" type="radio" name="beverage_venue_supplied" onclick="document.getElementById('hidden2').style.display='block'" <?php if($row_venue_others['beverage_venue_supplied']=="Y"){echo "checked";}?> value="Y" >
                            <label class="check-radio" for="beverage_venue_supplied1" >Yes</label>
                            <input id="beverage_venue_supplied2" type="radio" name="beverage_venue_supplied" onclick="document.getElementById('hidden2').style.display='none'" <?php if($row_venue_others['beverage_venue_supplied']=="N" || !isset($row_venue_others['beverage_venue_supplied']) || $row_venue_others['beverage_venue_supplied']==""){echo "checked";}?> value="N">
                            <label class="check-radio" for="beverage_venue_supplied2" >No</label>
                        </span>
                    </div>

                    <div class="form-rowh">
                        <label>Self-Supplied  <span>:</span></label>
                        <span class="radio slecc-boxx">
                            <input id="beverage_self_supplied1" type="radio" name="beverage_self_supplied" onclick="document.getElementById('hidden3').style.display='block'" <?php if($row_venue_others['beverage_self_supplied']=="Y"){echo "checked";}?> value="Y" >
                            <label class="check-radio" for="beverage_self_supplied1" >Yes</label>
                            <input id="beverage_self_supplied2" type="radio" name="beverage_self_supplied" onclick="document.getElementById('hidden3').style.display='none'" <?php if($row_venue_others['beverage_self_supplied']=="N" || !isset($row_venue_others['beverage_self_supplied']) || $row_venue_others['beverage_self_supplied']==""){echo "checked";}?> value="N">
                            <label class="check-radio" for="beverage_self_supplied2" >No</label>
                        </span>
                    </div>
                </div>


                <div class="data-table" id="hidden2" <?php if($row_venue_others['beverage_venue_supplied']=="Y"){?>style="display:block;";<?php }else{?>style="display:none;";<?php }?>>
                    
                    <table width="100%" border="0" cellspacing="0" class="main">
                        <tr>
                            <th width="40%">Reception Beverage Options Name</th>
                            <th width="20%">Package Included</th>
                            <th width="30%" align="center">Price per glass/person (GBP)</th>
                            <th width="10%" align="center">Status</th>
                        </tr>
                        <?php

                        $sql_choose_service = "SELECT * FROM ".TABLE_PREFIX."vendor_venue_reception_beverage_options WHERE `vendor_id` = '".$my_details['vendor_id']."'";
                        $sql_choose_service = mysql_query($sql_choose_service) or die(mysql_error());
                        $num_choose_service = mysql_num_rows($sql_choose_service);
                        $count = 1;
                        if($num_choose_service > 0)
                        {
                            while($row_choose_service = mysql_fetch_array($sql_choose_service))
                            {
                                ?>
                                
                                <tr class="add_reception_beverage_option <?php if($count%2==0){?>odd<?php }?>">
                                    <input type="hidden" name="reception_beverage_option_id[]" id="reception_beverage_option_id<?php echo $count;?>" value="<?php echo $row_choose_service['reception_beverage_option_id'];?>">
                                    <td align="center"><input type="text" name="reception_beverage_option_name[]" id="reception_beverage_option_name<?php echo $count;?>" value="<?php echo $row_choose_service['reception_beverage_option_name'];?>"></td>
                                    <td align="center">
                                        <select style="width:70px;" name="reception_beverage_option_package_included[]" id="reception_beverage_option_package_included<?php echo $count;?>">
                                            <option Value="N" <?php if($row_choose_service['reception_beverage_option_package_included']=="N"){echo "selected";}?>>No</option>
                                            <option Value="Y" <?php if($row_choose_service['reception_beverage_option_package_included']=="Y"){echo "selected";}?>>Yes</option>
                                        </select>
                                    </td>
                                    <td align="center"><input type="text" name="reception_per_beverage_option_price[]" id="reception_per_beverage_option_price<?php echo $count;?>" Placeholder="" value="<?php echo $row_choose_service['reception_per_beverage_option_price'];?>"></td>
                                    <td align="center">
                                        <select style="width:70px;" name="status[]" id="status<?php echo $count;?>">
                                            <option Value="N" <?php if($row_choose_service['status']=="N"){echo "selected";}?>>Inactive</option>
                                            <option Value="Y" <?php if($row_choose_service['status']=="Y"){echo "selected";}?>>Active</option>
                                        </select>
                                    </td>
                                    <!--<td align="center"><span class="bold">-</span></td>-->
                                </tr>
                                <?php

                                $count++;
                            }
                        }

                        for($i=$count;$i<=1;$i++)
                        {
                            ?>
                            
                            <tr class="add_reception_beverage_option <?php if($i%2==0){?>odd<?php }?>">
                                <input type="hidden" name="reception_beverage_option_id[]" id="reception_beverage_option_id<?php echo $i;?>" value="0">
                                <td align="center"><input type="text" name="reception_beverage_option_name[]" id="reception_beverage_option_name<?php echo $i;?>" value=""></td>
                                <td align="center">
                                    <select style="width:70px;" name="reception_beverage_option_package_included[]" id="reception_beverage_option_package_included<?php echo $i;?>">
                                        <option Value="N">No</option>
                                        <option Value="Y">Yes</option>
                                    </select>
                                </td>
                                <td align="center"><input type="text" name="reception_per_beverage_option_price[]" id="reception_per_beverage_option_price<?php echo $i;?>" Placeholder="" value=""></td>
                                <td align="center">
                                    <select style="width:70px;" name="status[]" id="status<?php echo $i;?>">
                                        <option Value="N">Inactive</option>
                                        <option Value="Y">Active</option>
                                    </select>
                                </td>
                            </tr>
                            <?php 
                        }
                        ?>
                        <!--<tr>
                            <th width="30%">Grand Total</th>
                            <th width="12%" align="center"><?php echo $total_estimated_cost;?></th>
                            <th width="12%" align="center"><?php echo $total_actual_cost;?></th>
                            <th width="12%" align="center"><?php echo $total_paid_amount;?></th>
                            <th width="12%" align="center"><?php echo $total_due_amount;?></th>
                            <th width="6%" align="center"></th>
                            <th width="7%" align="center"></th>
                        </tr>-->
                        
                    </table>
                    <table width="100%" border="0" cellspacing="0" class="">
                        <tr>
                            <td width="7%" align="center" colspan="3"><input type="button" id="each_reception_beverage_option_add" value="Add New Item" class="butt-sub-butt"></td>
                        </tr>
                    </table> 

                </div>

                <div id="hidden3" <?php if($row_venue_others['beverage_self_supplied']=="Y"){?>style="display:block;";<?php }else{?>style="display:none;";<?php }?>> 
                    <div class="form-rowh">
                        <label style="width:220px;">Self-Supplied Price Type<span>:</span></label>
                        <select  name="self_supplied_price_type" id="self_supplied_price_type">
                            <option Value="Flat Price" <?php if($row_venue_others['self_supplied_price_type']=="Flat Price"){echo "selected";}?>>Flat Price</option>
                            <option Value="Priced Per Bottle" <?php if($row_venue_others['self_supplied_price_type']=="Priced Per Bottle"){echo "selected";}?>>Priced Per Bottle</option>
                        </select>
                    </div>
                    <div class="form-rowh">
                        <label style="width:220px;">Self-Supplied Price<span>:</span></label>
                        <input style="width:80px;" type="text" name="self_supplied_price" id="self_supplied_price" value="<?php echo $row_venue_others['self_supplied_price'];?>"> GBP
                    </div>
                </div>

                <div class="form-rowh fsrow-full textalign-center">
                        
                    <input type="submit" id="" value="Save" class="butt-sub-butt">
                </div>
                
            </form>
            
        </div>
            
    </div>
    <div class="clearfix"></div>
</section>
<script>
    initSample();
</script>
<?php include('footer.php');?>
